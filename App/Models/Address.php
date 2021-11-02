<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class Address{
        private static $table = 'endereco';

        public static function getAll($email_cli){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT * FROM '.self::$table.' WHERE email_cli_fk = :email';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $email_cli);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Usuário sem endereços registrados!');
            }
        }

        public static function insert($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'INSERT INTO '.self::$table.'(email_cli_fk, rua, bairro, numero, cep, complemento, observacao) VALUES (:email, :rua, :bairro, :numero, :cep, :complemento, :observacao)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':rua', $data['rua']);
            $stmt->bindValue(':bairro', $data['bairro']);
            $stmt->bindValue(':numero', $data['numero']);
            $stmt->bindValue(':cep', $data['cep']);
            $stmt->bindValue(':complemento', $data['complemento']);
            $stmt->bindValue(':observacao', $data['observacao']);
            $stmt -> execute();

            if ($stmt->rowCount() > 0){
                return 'Endereço inserido com sucesso!';
            } else {
                throw new \Exception('Falha ao inserir endereço!'); 
            }
        }

        public static function delete($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'DELETE FROM '.self::$table.' WHERE id_endereco = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $data);
            $stmt -> execute();
            return 'Endereço deletado com sucesso!';
        }

        public static function put($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'UPDATE '.self::$table.' SET rua = :rua, bairro = :bairro, numero = :numero, cep = :cep, complemento = :complemento, observacao = :observacao WHERE id_endereco = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':rua', $data[1]['rua']);
            $stmt->bindValue(':bairro', $data[1]['bairro']);
            $stmt->bindValue(':numero', $data[1]['numero']);
            $stmt->bindValue(':cep', $data[1]['cep']);
            $stmt->bindValue(':complemento', $data[1]['complemento']);
            $stmt->bindValue(':observacao', $data[1]['observacao']);
            $stmt->bindValue(':id', $data[0][0]);
            $stmt -> execute(); 
          
            if ($stmt->rowCount() > 0){
                return 'Endereço atualizado com sucesso!';
            } else {
                throw new \Exception('Falha ao atualizar o endereço!'); 
            }
        }
    }
?>