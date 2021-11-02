<?php
    namespace App\Services;
    use App\Models\Cobertura;

    class CoberturaService{
        public function get($data){
            if ($data){
                return Cobertura::getCobertura($data);
            } else {
                return Cobertura::getAll();
            }
        }
    }
?>