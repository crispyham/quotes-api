<?php

class Quote {
    // Database Connection & Table Name
    private $conn;
    private $table = 'quotes';

    // Quote Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author_name;
    public $category_name;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Quotes (with optional filters)
    public function read($author_id = null, $category_id = null, $id = null) {
        $query = 'SELECT 
                    q.id, 
                    q.quote, 
                    a.author AS author_name, 
                    c.category AS category_name 
                  FROM ' . $this->table . ' q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id';

        // Add filtering conditions
        $conditions = [];
        if ($id) {
            $conditions[] = 'q.id = :id';
        }
        if ($author_id) {
            $conditions[] = 'q.author_id = :author_id';
        }
        if ($category_id) {
            $conditions[] = 'q.category_id = :category_id';
        }

        // If any conditions exist, append them to the query
        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $query .= ' ORDER BY q.id ASC';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        if ($id) $stmt->bindParam(':id', $id);
        if ($author_id) $stmt->bindParam(':author_id', $author_id);
        if ($category_id) $stmt->bindParam(':category_id', $category_id);

        // Execute Query
        $stmt->execute();

        return $stmt;
    }

    //Post a Quote
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
    
        return $stmt->execute();
    }    

    // Update a Quote
    public function update() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET quote = :quote, author_id = :author_id, category_id = :category_id
                  WHERE id = :id';
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
    
        return $stmt->execute();
    }

    // Delete a Quote
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
    
        return $stmt->execute();
    }
    

}

?>
