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
if (!isset($data->id) || !isset($data->category)) {
    echo json_encode(['message' => 'Missing Required Parameters']);
    exit();
}

// Set category properties
$category->id = $data->id;
$category->category = $data->category;

// Check if category exists
$query = 'SELECT id FROM categories WHERE id = :id';
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $category->id);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    echo json_encode(['message' => 'category_id Not Found']);
    exit();
}

// Update Category
if ($category->update()) {
    echo json_encode([
        'id' => $category->id,
        'category' => $category->category
    ]);
} else {
    echo json_encode(['message' => 'Category Not Updated']);
}

?>
