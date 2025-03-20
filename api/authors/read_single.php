<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate Author Object
$author = new Author($db);

// Get Author ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(['message' => 'author_id Not Found']));

// Retrieve Author
$result = $author->read_single($id);
$num = $result->rowCount();

// Check if the author exists
if ($num > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    extract($row);

    // Create response array
    $author_item = array(
        'id' => $id,
        'author' => $author
    );

    // Output JSON
    echo json_encode($author_item);
} else {
    // Author Not Found
    echo json_encode(
        array('message' => 'author_id Not Found')
    );
}

?>
