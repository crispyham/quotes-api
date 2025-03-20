<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote Object
$quote = new Quote($db);

// Get Quote ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(['message' => 'No Quotes Found']));

// Retrieve Quote
$result = $quote->read(null, null, $id);
$num = $result->rowCount();

// Check if the quote exists
if ($num > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    extract($row);

    // Create response array
    $quote_item = array(
        'id' => $id,
        'quote' => $quote,
        'author' => $author_name,
        'category' => $category_name
    );

    // Output JSON
    echo json_encode($quote_item);
} else {
    // Quote Not Found
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}

?>
