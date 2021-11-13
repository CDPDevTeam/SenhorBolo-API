<?php
    namespace App\Services;
    use App\Models\Pedido;

    class PedidoService{
        public function post($data){
            $postData = json_decode(file_get_contents('php://input'), true);
            if ($data[0] === 'item'){
                return Pedido::insertItem($postData);
            } else {
                return Pedido::insert($postData);
            }
        }

        public function get($userData){
            if($userData[0] === $userData[1]){
                return Pedido::get($userData[0]);
                } else {
                    throw new \Exception('Usuário diferente de sua chave JWT!');
            }
        }
    }
?>