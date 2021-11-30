<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class Recheio{
        private static $table = 'recheio_bolo';

        public static function getAll(){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT recheio_bolo AS nome_opcao, imagem_recheio AS imagem FROM '.self::$table;
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();
            $connPdo = null;

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao se conectar com o banco de dados!');
            }        }

        public static function getRecheio($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT * FROM '.self::$table.' WHERE UPPER(recheio_bolo) = UPPER(:nome)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':nome', $data[0]);
            $stmt->execute();
            $connPdo = null;

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Recheio inexistente no banco de dados!');
            }
        }

    }
?>