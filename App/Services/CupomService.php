<?php
    namespace App\Services;
    use App\Models\Cupom;

    class CupomService{
        public function get($userData){
            if($userData[0] === $userData[1]){
                return Cupom::getAll($userData[0]);
                } else {
                    throw new \Exception('Usuário diferente de sua chave JWT!');
            }
        }
    }
?>