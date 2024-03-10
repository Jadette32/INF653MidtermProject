<?php

class Database {
    private $host = "dpg-cnj1b88l6cac739bfbng-a.oregon-postgres.render.com";
    private $dbname = "quotesdb_0r3e";
    private $username = "quotesdb_0r3e_user";
    private $password = "czCiY34fhKSeVUt9dFdEPEtHurFkxgMI";
    public $pdo;

    public function __construct() {
        try {
            $dsn = "pgsql:host={$this->host};dbname={$this->dbname}";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}

