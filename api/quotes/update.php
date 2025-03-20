<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Quote Object
$quote = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Validate required fields
$errors = [];

if (!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Set quote properties
$quote->id = $data->id;
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

// Check if quote exists
$query = 'SELECT id FROM quotes WHERE id = :id';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $quote->id);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    echo json_encode(['message' => 'No Quotes Found']);
    exit();
}

// Check if author_id exists
$query = 'SELECT id FROM authors WHERE id = :author_id';
$stmt = $db->prepare($query);
$stmt->bindParam(':author_id', $quote->author_id);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    $errors[] = 'author_id Not Found';
}

// Check if category_id exists
$query = 'SELECT id FROM categories WHERE id = :category_id';
$stmt = $db->prepare($query);
$stmt->bindParam(':category_id', $quote->category_id);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    $errors[] = 'category_id Not Found';
}

// If there are errors, return them in a single response
if (!empty($errors)) {
    echo json_encode(['message' => $errors]);
    exit();
}

// Update Quote
if ($quote->update()) {
    echo json_encode([
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author_id' => $quote->author_id,
        'category_id' => $quote->category_id
    ]);
} else {
    echo json_encode(['message' => 'Quote Not Updated']);
}

?>
