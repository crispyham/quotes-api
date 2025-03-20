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
if (!isset($data->id)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Set quote ID
$quote->id = $data->id;

// Check if quote exists
$query = 'SELECT id FROM quotes WHERE id = :id';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $quote->id);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    echo json_encode(['message' => 'No Quotes Found']);
    exit();
}

// Delete Quote
if ($quote->delete()) {
    echo json_encode(['id' => $quote->id]);
} else {
    echo json_encode(['message' => 'Quote Not Deleted']);
}

?>
