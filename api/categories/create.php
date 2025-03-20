<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include database and model
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Category Object
$category = new Category($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Validate required fields
if (!isset($data->category)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Set category property
$category->category = $data->category;

// Create Category
if ($category->create()) {
    echo json_encode([
        'id' => $db->lastInsertId(),
        'category' => $category->category
    ]);
} else {
    echo json_encode(['message' => 'Category Not Created']);
}

?>
