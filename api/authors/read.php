<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate Author Object
$author = new Author($db);

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    // Redirect to read_single.php to handle a single author request
    require 'read_single.php';
    exit();
}

// Retrieve All Authors
$result = $author->read();
$num = $result->rowCount();

// Check if any authors exist
if ($num > 0) {
    // Author Array (WITHOUT "data" wrapper)
    $authors_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $author_item = array(
            'id' => $id,
            'author' => $author
        );

        // Push to array
        array_push($authors_arr, $author_item);
    }

    // Convert to JSON & Output
    echo json_encode($authors_arr);
} else {
    // No Authors Found
    echo json_encode(
        array('message' => 'No Authors Found')
    );
}

?>
