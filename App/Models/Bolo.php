<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class Bolo{
        private static $table = 'produto';

        public static function getAll(){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT * FROM '.self::$table;
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao carregar os bolos!');
            }
        }

        public static function get($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT * FROM '.self::$table.' WHERE id_prod = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $data[0]);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao carregar os bolos!');
            }
        }

        public static function insert($data){
            if ($data['confeito'] === ''){
                $data['confeito'] = null;
            }
            if ($data['recheio'] === ''){
                $data['recheio'] = null;
            }
            if ($data['cobertura'] === ''){
                $data['cobertura'] = null;
            }

            $connPdo = DatabaseConnector::getConnection();
            $sql = 'INSERT INTO '.self::$table.' (confeito_bolo_fk, massa_bolo_fk, recheio_bolo_fk, cobertura_bolo_fk, categoria_prod_fk, nome_prod, foto_prod) VALUES (:confeito, :massa, :recheio, :cobertura, :categoria, :nome, :foto)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':confeito', $data['confeito']);
            $stmt->bindValue(':massa', $data['massa']);
            $stmt->bindValue(':recheio', $data['recheio']);
            $stmt->bindValue(':cobertura', $data['cobertura']);
            $stmt->bindValue(':categoria', 'Bolo personalizado');
            $stmt->bindValue(':nome', $data['nome']);
            $stmt->bindValue(':foto', $data['foto']);
            $stmt -> execute();

            if ($stmt->rowCount() > 0){
                return 'Bolo personalizado inserido com sucesso!';
            } else {
                throw new \Exception('Falha ao cadastrar o bolo personalizado!'); 
            }
        }

    }
?>