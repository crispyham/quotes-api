<?php

class Category {
    // Database Connection & Table Name
    private $conn;
    private $table = 'categories';

    // Category Properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Categories
    public function read() {
        $query = 'SELECT id, category FROM ' . $this->table . ' ORDER BY id ASC';

        // Prepare & Execute Statement
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Get Single Category by ID
    public function read_single($id) {
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt;
    }

    // Post a Category
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);
    
        return $stmt->execute();
    }

    // Update a Category
    public function update() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET category = :category
                  WHERE id = :id';
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);
    
        return $stmt->execute();
    }
    
    // Delete a Category
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
    
        return $stmt->execute();
    }
    
    
}

?>
