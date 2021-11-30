<?php
    namespace App\Common;

    class DatabaseConnector{
        public static function getConnection(){

            GLOBAL $_ENV;
            /*
            $drive = $_ENV['DB_DRIVE'];
            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $db = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];*/

            /*
            $drive = "pgsql";
            $host = "motty.db.elephantsql.com";
            $port = "5432";
            $db = "vmnrcpwz";
            $user = "vmnrcpwz";
            $pass ="uN9-oJ2RFBzZF9sEFeHEdQcMNb0EJA2L";*/

            $drive = "pgsql";
            $host = "ec2-34-203-114-67.compute-1.amazonaws.com";
            $port = "5432";
            $db = "d20gs8fi9etl55";
            $user = "ywqpycucbiguqa";
            $pass ="c0e978116cdbbb710871d3605b9591e25d69bcd53eec83591eb359b81c99e177";

            try{
                return new \PDO($drive.':host='.$host.'; port='.$port.'; dbname='.$db, $user, $pass);
            } catch (\PDOException $e){
                exit($e->getMessage());
            }
        }
    }
?>