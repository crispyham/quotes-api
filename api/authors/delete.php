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
if (!isset($data->id)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Set author ID
$author->id = $data->id;

// Check if author exists
$query = 'SELECT id FROM authors WHERE id = :id';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $author->id);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    echo json_encode(['message' => 'author_id Not Found']);
    exit();
}

// Check if the author is used in quotes
$query = 'SELECT id FROM quotes WHERE author_id = :id';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $author->id);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    echo json_encode(['message' => 'Cannot delete author. Quotes exist for this author.']);
    exit();
}

// Delete Author
if ($author->delete()) {
    echo json_encode(['id' => $author->id]);
} else {
    echo json_encode(['message' => 'Author Not Deleted']);
}

?>
