<?php
    namespace App\Services;
    use App\Common\databaseConnector;

    class AuthService{
        public function login(){
            $_POST = json_decode(file_get_contents('php://input'), true);
            if(isset($_POST['email']) && isset($_POST['senha'])){
                $connPdo = DatabaseConnector::getConnection();
                $sql = 'SELECT nome_cli, cpf_cli, foto_cli FROM cliente WHERE email_cli = :email AND senha_cli = :senha';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':email', $_POST['email']);
                $stmt->bindValue(':senha', $_POST['senha']);
                $stmt -> execute();

                if ($stmt->rowCount() > 0){
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
        
                    $sign = hash_hmac('sha256', $header . '.' . $payload,  $_ENV['JWT_KEY'], true);
                    $sign = self::base64UrlEncode($sign);
        
                    $token = $header.'.'.$payload.'.'.$sign;
        
                    $jsonData = array("key" => $token, "usuario" => $stmt->fetchAll(\PDO::FETCH_ASSOC));
                    return $jsonData;

                } else {
                    throw new \Exception('Usuário inexistente no banco de dados.'); 
                }
            } 
            throw new \Exception('Faça um POST com o email e senha do usuário!');
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
            $b64 = base64_encode($data);
            if ($b64 === false) {
                return false;
            }
            $url = strtr($b64, '+/', '-_');
            return rtrim($url, '=');
        }

        private static function base64UrlDecode($data, $strict = false){
            $b64 = strtr($data, '-_', '+/');
            return base64_decode($b64, $strict);
        }
    }
?>