<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class Pedido{
        private static $table = 'pedido';

        public static function insert($data){
            $date = date('Y-m-d');
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'INSERT INTO '.self::$table.'(email_cli_fk, id_endereco_fk, id_cupons_fk, data_compra, data_entrega, status_compra, ecommerce) 
            VALUES (:email, :endereco, :cupom, :dataCompra, :dataEntrega, :statusCompra, :ecommerce)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':endereco', $data['endereco']);
            $stmt->bindValue(':cupom', $data['cupom']);
            $stmt->bindValue(':dataCompra', $date);
            $stmt->bindValue(':dataEntrega', date('Y-m-d', strtotime($date. ' + 4 days')));
            $stmt->bindValue(':statusCompra', $data['statusCompra']);
            $stmt->bindValue(':ecommerce', true);
            $stmt -> execute();

            if ($stmt->rowCount() > 0){
                return 'Pedido inserido com sucesso!';
            } else {
                throw new \Exception('Falha ao inserir pedido!'); 
            }
        }

        public static function insertItem($data){
            $date = date('Y-m-d');
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'INSERT INTO qtde_pedido (id_prod_fk, id_pedido_fk, valor_unitario, qtde_pedido)
            VALUES (:idProduto, :idPedido, :valorUnitario, :qtdePedido)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':idProduto', $data['idProduto']);
            $stmt->bindValue(':idPedido', $data['idPedido']);
            $stmt->bindValue(':valorUnitario', $data['valorUnitario']);
            $stmt->bindValue(':qtdePedido', $data['qtdePedido']);
            $stmt -> execute();

            if ($stmt->rowCount() > 0){
                return 'Item do pedido '.$data['idPedido'].' inserido com sucesso!';
            } else {
                throw new \Exception('Falha ao inserir endereço!'); 
            }
        }

        public static function get($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT produto.nome_prod, LOWER(produto.foto_prod) AS foto_prod,
            qtde_pedido.qtde_pedido,
            SUM(qtde_pedido.qtde_pedido * qtde_pedido.valor_unitario) AS total_prod
            FROM pedido, qtde_pedido, produto 
            WHERE pedido.id_pedido = :idPedido
            AND qtde_pedido.id_prod_fk = produto.id_prod
            GROUP BY produto.nome_prod, produto.foto_prod, qtde_pedido.qtde_pedido';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':idPedido', $data);
            $stmt -> execute();

            
            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Pedido inexistente no banco de dados!');
            }
        }

    }