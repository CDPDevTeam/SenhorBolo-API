<?php
    namespace App\Services;
    use App\Common\Environment;

    class AuthService{
        public function login(){
            if(isset($_POST['email'])){
                $header =[
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];
    
                $payload = [
                    'email' => $_POST['email']
                ];
    
                $header = json_encode($header);
                $payload = json_encode($payload);
    
                $header = self::base64UrlEncode($header);
                $payload = self::base64UrlEncode($payload);
    
                $sign = hash_hmac('sha256', $header . '.' . $payload, $_ENV['JWT_KEY'], true);
                $sign = self::base64UrlEncode($sign);
    
                $token = $header.'.'.$payload.'.'.$sign;
    
                return $token;
            } 
            throw new \Exception('Usuário inexistente');
        }

        public static function check(){
            $http_header = apache_request_headers();
            if(isset($http_header['Authorization'])){
                $bearer = explode(' ', $http_header['Authorization']);
    
                $token = explode('.', $bearer[1]);
                $header = $token[0];
                $payload = $token[1];
                $sign = $token[2];
                
                $valid = hash_hmac('sha256', $header . "." . $payload, $_ENV['JWT_KEY'], true);
                $valid = self::base64UrlEncode($valid);

                return $valid === $sign;
            }
        }

        public static function getEmail(){
            $http_header = apache_request_headers();
            $bearer = explode(' ', $http_header['Authorization']);

            $token = explode('.', $bearer[1]);
            $email = self::base64UrlDecode($token[1]);

            $email = json_decode($email, true);
            return $email['email'];
        }

        private static function base64UrlEncode($data)
        {
            return strtr( base64_encode($data), '+/=', '-_,' );
        }

        private static function base64UrlDecode($data){
            return base64_decode(strtr($data, '-_,', '+/='));
        }
    }
?>