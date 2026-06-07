
<?php

class DB {

    public static function connect(){
        try {

            $host = "localhost";
            $port = "5432";
            $dbname = "saas";
            $user = "postgres";
            $password = "Pichincha.2021";

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            $pdo = new PDO($dsn, $user, $password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $e){
            die("Error DB: " . $e->getMessage());
        }
    }

}