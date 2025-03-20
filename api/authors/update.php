<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Author Object
$author = new Author($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Validate required fields
if (!isset($data->id) || !isset($data->author)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Set author properties
$author->id = $data->id;
$author->author = $data->author;

// Check if author exists
$query = 'SELECT id FROM authors WHERE id = :id';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $author->id);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    echo json_encode(['message' => 'author_id Not Found']);
    exit();
}

// Update Author
if ($author->update()) {
    echo json_encode([
        'id' => $author->id,
        'author' => $author->author
    ]);
} else {
    echo json_encode(['message' => 'Author Not Updated']);
}

?>
