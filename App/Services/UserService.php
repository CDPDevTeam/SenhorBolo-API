<?php
    namespace App\Services;
    use App\Models\User;

    class UserService{

        public function get(){
            return 'Para logar, realize um POST com os dados do usuário';
        }

        public function post(){
            $postData = json_decode(file_get_contents('php://input'), true);
            if ($postData['cadastro'] === true){
                return User::insert($postData);
            } else {
                return User::get($postData);
            }
        }

        public function put(){

        }

        public function delete($userData){
            if($userData){
                return User::deleteUser($userData);
            }
        }
    }