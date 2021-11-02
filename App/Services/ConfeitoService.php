<?php
    namespace App\Services;
    use App\Models\Confeito;

    class ConfeitoService{
        public function get($data){
            if ($data){
                return Confeito::getConfeito($data);
            } else {
                return Confeito::getAll();
            }
        }
    }
?>