<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class Cupom{
        
        public static function getAll($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT c.id_cupons, c.valor_desc
            FROM cupons c 
            INNER JOIN cupom_cliente ON cupom_cliente.id_cupom_fk = c.id_cupons
            AND cupom_cliente.email_cli_fk = :email AND cupom_cliente.cupom_usado = false';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $data);
            $stmt -> execute();
            
            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao se conectar com o banco de dados!');
            }
        }

    }