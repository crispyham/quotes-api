<?php

class Database {
    private $conn;

    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;

    public function __construct() {
        // Read environment variables
        $this->host = getenv('DATABASE_HOST');
        $this->port = getenv('DATABASE_PORT');
        $this->db_name = getenv('DATABASE_NAME');
        $this->username = getenv('DATABASE_USER');
        $this->password = getenv('DATABASE_PASSWORD');
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
            error_log('Database Connection Error: ' . $e->getMessage()); // Logs error in Render logs
            echo json_encode(['message' => 'Database Connection Error']);
            exit();
        }

        return $this->conn;
    }
}

