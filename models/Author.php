<?php

class Author {
    // Database Connection & Table Name
    private $conn;
    private $table = 'authors';

    // Author Properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Authors
    public function read() {
        $query = 'SELECT id, author FROM ' . $this->table . ' ORDER BY id ASC';

        // Prepare & Execute Statement
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Get Single Author by ID
    public function read_single($id) {
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt;
    }

    // Post an Author
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author', $this->author);
    
        return $stmt->execute();
    }

    // Update an Author
    public function update() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET author = :author
                  WHERE id = :id';
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);
    
        return $stmt->execute();
    }
    
    // Delete an Author
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
    
        return $stmt->execute();
    }
    
}

?>
