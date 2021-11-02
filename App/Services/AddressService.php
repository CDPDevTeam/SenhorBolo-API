<?php
    namespace App\Services;
    use App\Models\Address;

    class AddressService{
        public function get($userData){
            if($userData[0] === $userData[1]){
            return Address::getAll($userData[0]);
            } else {
                throw new \Exception('Usuário diferente de sua chave JWT!');
            }
        }

        public function post(){
            $postData = json_decode(file_get_contents('php://input'), true);
            return Address::insert($postData);
        }

        public function delete($addressID){
            return Address::delete($addressID[0]);
        }

        public function put($addressID){
            $jsonData = json_decode(file_get_contents('php://input'), true);
            $putData = array($addressID, $jsonData);
            return Address::put($putData);
        }
    }
?>