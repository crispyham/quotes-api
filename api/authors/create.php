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
if (!isset($data->author)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Set author property
$author->author = $data->author;

// Create Author
if ($author->create()) {
    echo json_encode([
        'id' => $db->lastInsertId(),
        'author' => $author->author
    ]);
} else {
    echo json_encode(['message' => 'Author Not Created']);
}

?>
