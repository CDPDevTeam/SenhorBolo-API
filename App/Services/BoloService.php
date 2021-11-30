<?php
    namespace App\Services;
    use App\Models\Bolo;

    class BoloService{
        public function get($data){
            if($data){
                if($data[0] === 'recomendacao'){
                    return Bolo::getRecommendation();
                } else if($data[0] === 'categoria'){
                    return Bolo::getCategory($data);
                } else {
                    return Bolo::getSearch($data);
                }
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