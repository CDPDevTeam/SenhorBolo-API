<?php
    namespace App\Common;

    class DatabaseConnector{
        public static function getConnection(){
            $drive = $_ENV['DB_DRIVE'];
            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $db = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];

            try{
                return new \PDO($drive.':host='.$host.'; port='.$port.'; dbname='.$db, $user, $pass);
            } catch (\PDOException $e){
                exit($e->getMessage());
            }
        }
    }