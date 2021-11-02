<?php
    namespace App\Models;
    use App\Common\databaseConnector;

    class User{
        private static $table = 'cliente';

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

        public static function put($data){
            $connPdo = DatabaseConnector::getConnection();
            $sql = 'UPDATE '.self::$table.' SET nome_cli = :nome, cpf_cli = :cpf, senha_cli = :senha, foto_cli= :foto WHERE email_cli = :email';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':nome', $data[1]['nome']);
            $stmt->bindValue(':cpf', $data[1]['cpf']);
            $stmt->bindValue(':senha', $data[1]['senha']);
            $stmt->bindValue(':foto', $data[1]['foto']);
            $stmt->bindValue(':email', $data[0]);
            $stmt -> execute(); 
          
            if ($stmt->rowCount() > 0){
                return 'Usuário atualizado com sucesso!';
            } else {
                throw new \Exception('Falha ao atualizar o usuário!'); 
            }
        }
    }
?>