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

            // Seleciona todas as compras do cliente
            $sql = 'SELECT id_pedido, status_compra
            FROM pedido WHERE email_cli_fk = :email';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $data);
            $stmt -> execute();

            $pedidos = [];
            
            if($stmt->rowCount() > 0){

                $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                
                // Para cada compra do cliente, os produtos são adicionados 
                for($i = 0; $i < sizeof($row); $i++){
                    $pedidos[$i] = $row[$i];

                    // Seleção dos produtos, de acordo com o ID do pedido
                    $sqlItem = 'SELECT
                    b.id_prod,
                    b.nome_prod,
                    b.categoria_prod_fk,
                    LOWER(b.foto_prod) AS foto_prod,
                    qtde.qtde_pedido,
                    SUM (qtde.qtde_pedido * qtde.valor_unitario) AS total_prod
                    FROM
                        produto b
                    INNER JOIN qtde_pedido qtde
                        ON b.id_prod = qtde.id_prod_fk
                    INNER JOIN pedido p
                        ON p.id_pedido = qtde.id_pedido_fk AND p.id_pedido ='.$pedidos[$i]["id_pedido"].'
                    GROUP BY id_pedido, id_prod, qtde.qtde_pedido
                    ORDER BY id_pedido DESC';
                    $stmtItem = $connPdo->prepare($sqlItem);
                    $stmtItem -> execute();

                    $itemPedido = $stmtItem->fetchAll(\PDO::FETCH_ASSOC);
                    $pedidos[$i]["itens_pedido"] = $itemPedido; // Atribuição dos itens ao pedido
                }
                return $pedidos;
            } else {
                throw new \Exception('Pedido inexistente no banco de dados!');
            }
        }

    }