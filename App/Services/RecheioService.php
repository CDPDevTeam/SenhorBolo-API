<?php
    namespace App\Services;
    use App\Models\Recheio;

    class RecheioService{
        public function get($data){
            if ($data){
                return Recheio::getRecheio($data);
            } else {
                return Recheio::getAll();
            }
        }
    }
?>