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
        $this->host = getenv('DATABASE_HOST') ?: 'dpg-cvd52mjv2p9s73cbgu3g-a';
        $this->port = getenv('DATABASE_PORT') ?: '5432';
        $this->db_name = getenv('DATABASE_NAME') ?: 'inf653_cpham_test';
        $this->username = getenv('DATABASE_USER') ?: 'inf653_cpham_test_user';
        $this->password = getenv('DATABASE_PASSWORD') ?: 'Opm5zR3aSjynxAwSkBM4o5x3P38cYLuR';
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

?>
