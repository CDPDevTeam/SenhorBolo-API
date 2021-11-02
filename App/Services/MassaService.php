<?php
    namespace App\Services;
    use App\Models\Massa;

    class MassaService{
        public function get($data){
            if ($data){
                return Massa::getMassa($data);
            } else {
                return Massa::getAll();
            }
        }
    }
?>