<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class User{
        private static $table = 'cliente';

        public static function get($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'SELECT * FROM '.self::$table.' WHERE email_cli = :email AND senha_cli = :senha';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':senha', $data['senha']);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception('Nenhum usuário encontrado!');
            }
        }

        public static function insert($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'INSERT INTO '.self::$table.' (email_cli, nome_cli, cpf_cli, senha_cli, foto_cli) VALUES (:email, :nome, :cpf, :senha, :foto)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':nome', $data['nome']);
            $stmt->bindValue(':cpf', $data['cpf']);
            $stmt->bindValue(':senha', $data['senha']);
            $stmt->bindValue(':foto', $data['foto']);
            $stmt -> execute();

            if ($stmt->rowCount() > 0){
                return 'Usuário inserido com sucesso!';
            } else {
                throw new \Exception('Falha ao inserir usuário!'); 
            }
        }

        public static function deleteUser($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'DELETE FROM '.self::$table.' WHERE email_cli = :email';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $data);
            $stmt -> execute();
            return 'Usuário deletado com sucesso!';
        }
    }
?>