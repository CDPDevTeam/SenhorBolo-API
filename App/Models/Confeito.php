<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class Confeito{
        private static $table = 'confeito_bolo';

        public static function getAll(){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT confeito_bolo AS nome_opcao, LOWER(imagem_confeito) AS imagem FROM '.self::$table;
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();
            $connPdo = null;

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao se conectar com o banco de dados!');
            }
        }

        public static function getConfeito($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT * FROM '.self::$table.' WHERE UPPER(confeito_bolo) = UPPER(:nome)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':nome', $data[0]);
            $stmt->execute();
            $connPdo = null;

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Confeito inexistente no banco de dados!');
            }
        }

    }
?>