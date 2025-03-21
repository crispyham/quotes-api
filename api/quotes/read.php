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

// Get Query Parameters
$id = isset($_GET['id']) ? $_GET['id'] : null;
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Bonus Get Random Quote
$random = isset($_GET['random']) && $_GET['random'] === 'true';

// If only id is set and not asking for random, return specific quote
if ($id && !$author_id && !$category_id && !$random) {
    require 'read_single.php';
    exit();
}

// If random is requested (with or without filters), route to random handler
if ($random) {
    require 'read_random.php';
    exit();
}

// Retrieve Quotes
$result = $quote->read($author_id, $category_id, $id);

// Get Row Count
$num = $result->rowCount();

// Check if any quotes exist
if ($num > 0) {
    // Quote Array
    $quotes_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
    
        $quote_item = array(
            'id' => $id,
            'quote' => $quote,
            'author' => $author_name,
            'category' => $category_name
        );
        
        // Push to "data"
        array_push($quotes_arr, $quote_item);
    }
    
    // Convert to JSON & Output
    echo json_encode($quotes_arr);
    
} else {
    // No Quotes Found
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}

?>
