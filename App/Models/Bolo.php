<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class Bolo{
        private static $table = 'produto';

        public static function getAll(){
            $connPdo = DatabaseConnector::getConnection();
            $sql =  'SELECT produto.id_prod,  produto.nome_prod, LOWER(produto.foto_prod) AS foto_prod,
            produto.categoria_prod_fk, categoria_produto.preco_catprod FROM produto, categoria_produto
            WHERE produto.categoria_prod_fk = categoria_produto.nome_catprod ORDER BY produto.id_prod';   
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao carregar os bolos!');
            }
        }

        public static function getSearch($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT produto.id_prod, produto.nome_prod, LOWER(produto.foto_prod) AS foto_prod,
            produto.categoria_prod_fk, categoria_produto.preco_catprod 
            FROM produto, categoria_produto WHERE produto.categoria_prod_fk = categoria_produto.nome_catprod 
            AND produto.nome_prod ILIKE \'%'.$data[0].'%\' ORDER BY produto.nome_prod';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao carregar os bolos!');
            }
        }

        // Bolos aleatórios para a tela de produto do app
        public static function getRecommendation(){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT produto.id_prod,  produto.nome_prod, LOWER(produto.foto_prod) AS foto_prod,
            produto.categoria_prod_fk, categoria_produto.preco_catprod 
            FROM produto, categoria_produto
            WHERE produto.categoria_prod_fk = categoria_produto.nome_catprod 
            ORDER BY random() LIMIT 4';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao carregar os bolos!');
            }
        }

        // GET a partir do ID do bolo
        public static function get($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT produto.id_prod, produto.nome_prod, LOWER(produto.foto_prod) AS foto_prod,
            produto.categoria_prod_fk, categoria_produto.preco_catprod 
            FROM produto, categoria_produto WHERE produto.categoria_prod_fk = categoria_produto.nome_catprod 
            AND produto.nome_prod ILIKE \'%'.$data[0].'%\' ORDER BY produto.nome_prod';
            $stmt = $connPdo->prepare($sql);
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