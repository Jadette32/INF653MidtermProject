<?php

class Database {
    private $conn;

    public function __construct() {
        $host = "dpg-cnj1b88l6cac739bfbng-a.oregon-postgres.render.com";
        $port = "5432";
        $dbname = "quotesdb_0r3e";
        $username = "quotesdb_0r3e_user";
        $password = "czCiY34fhKSeVUt9dFdEPEtHurFkxgMI";

        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

        try {
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
    }

    public function connect() {
        return $this->conn;
    }
}

?>
