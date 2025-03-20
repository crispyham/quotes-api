<?php

class Database {
    private $conn;

    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;

    public function __construct() {
        $this->host = getenv('DATABASE_HOST') ?: 'localhost';
        $this->port = getenv('DATABASE_PORT') ?: '5432';
        $this->db_name = getenv('DATABASE_NAME') ?: 'quotesdb';
        $this->username = getenv('DATABASE_USER') ?: 'postgres';
        $this->password = getenv('DATABASE_PASSWORD') ?: 'password';
    }

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Database Connection Error']);
            exit();
        }

        return $this->conn;
    }
}

?>
