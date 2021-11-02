<?php
    namespace App\Services;
    use App\Models\User;

    class UserService{
        public function post(){
            $postData = json_decode(file_get_contents('php://input'), true);
            return User::INSERT($postData);
        }

        public function put($userData){
            $jsonData = json_decode(file_get_contents('php://input'), true);
            if(AuthService::check()){
                $userEmail = AuthService::getEmail();
                if($userEmail === $userData[0]){
                    $_PUT = array($userEmail, $jsonData);
                    return User::put($_PUT);
                } else {
                    throw new \Exception ('Email não bate com a key jwt');
                }
            } else {
                throw new \Exception ('Acesso não autorizado');
            }

        }

        public function delete($userData){
            if($userData){
                return User::deleteUser($userData);
            }
        }
    }