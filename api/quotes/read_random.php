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

// Get filters from query
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Retrieve quotes matching filters
$result = $quote->read($author_id, $category_id);

// Get all matching quotes
$quotes = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $quotes[] = array(
        'id' => $id,
        'quote' => $quote,
        'author' => $author_name,
        'category' => $category_name
    );
}

// Return 1 random quote
if (count($quotes) > 0) {
    $random_quote = $quotes[array_rand($quotes)];
    echo json_encode($random_quote);
} else {
    echo json_encode(['message' => 'No Quotes Found']);
}
