<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class Bolo{
        private static $table = 'produto';

        public static function getAll(){
            $connPdo = DatabaseConnector::getConnection();
            $sql =  'SELECT produto.id_prod,  produto.nome_prod, LOWER(produto.foto_prod) AS foto_prod,
            produto.categoria_prod_fk, categoria_produto.preco_catprod FROM produto, categoria_produto
            WHERE produto.categoria_prod_fk = categoria_produto.nome_catprod AND produto.categoria_prod_fk != \'Bolo personalizado\' ORDER BY produto.id_prod';   
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
            AND produto.categoria_prod_fk != \'Bolo personalizado\' 
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
            AND produto.categoria_prod_fk != \'Bolo personalizado\' 
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
        public static function getCategory($category){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT produto.id_prod, produto.nome_prod, LOWER(produto.foto_prod) AS foto_prod,
            produto.categoria_prod_fk, categoria_produto.preco_catprod 
            FROM produto, categoria_produto WHERE produto.categoria_prod_fk = categoria_produto.nome_catprod 
            AND produto.categoria_prod_fk = :categoria ORDER BY produto.nome_prod';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':categoria', $category[1]);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Erro ao carregar os bolos!');
            }
        }



        public static function insert($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT produto.id_prod, produto.nome_prod, LOWER(produto.foto_prod) AS foto_prod,
            produto.categoria_prod_fk, categoria_produto.preco_catprod 
            FROM produto, categoria_produto  WHERE produto.categoria_prod_fk = categoria_produto.nome_catprod 
            AND produto.categoria_prod_fk = \'Bolo personalizado\'
            AND produto.confeito_bolo_fk = :confeito AND produto.recheio_bolo_fk = :recheio
            AND produto.cobertura_bolo_fk = :cobertura AND produto.massa_bolo_fk = :massa
            AND produto.nome_prod = :nome AND produto.foto_prod = :foto LIMIT 1';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':confeito', $data['confeito']);
            $stmt->bindValue(':massa', $data['massa']);
            $stmt->bindValue(':recheio', $data['recheio']);
            $stmt->bindValue(':cobertura', $data['cobertura']);
            $stmt->bindValue(':nome', 'Bolo personalizado');
            $stmt->bindValue(':foto', 'personalizado.png');
            $stmt -> execute();

            if ($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                $connPdo = null;
                $connPdoInsert = DatabaseConnector::getConnection();
                $sqlInsert = 'INSERT INTO '.self::$table.' (confeito_bolo_fk, massa_bolo_fk, recheio_bolo_fk, cobertura_bolo_fk, categoria_prod_fk, nome_prod, foto_prod) VALUES (:confeito, :massa, :recheio, :cobertura, :categoria, :nome, :foto)';
                $stmtInsert = $connPdoInsert->prepare($sqlInsert);
                $stmtInsert->bindValue(':nome', 'Bolo personalizado');
                $stmtInsert->bindValue(':foto', 'personalizado.png');
                $stmtInsert->bindValue(':confeito', $data['confeito']);
                $stmtInsert->bindValue(':massa', $data['massa']);
                $stmtInsert->bindValue(':recheio', $data['recheio']);
                $stmtInsert->bindValue(':cobertura', $data['cobertura']);
                $stmtInsert->bindValue(':categoria', 'Bolo personalizado');
                $stmtInsert -> execute();
                if($stmtInsert->rowCount() > 0){
                    return self::insert($data);
                } else {
                    return 'Erro ao cadastrar o bolo';
                }
            }
        }

    }
?>