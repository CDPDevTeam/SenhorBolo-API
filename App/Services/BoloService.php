<?php
    namespace App\Services;
    use App\Models\Bolo;

    class BoloService{
        public function get($data){
            if($data){
                return Bolo::get($data);
            } else {
                return Bolo::getAll();
            }
        }

        public function post(){
            if(AuthService::check()){
                $postData = json_decode(file_get_contents('php://input'), true);
                return Bolo::insert($postData);
            } else {
                throw new \Exception ('Somente usuários cadastrados podem criar um bolo personalizado');
            }
        }
    }
?>