<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate Category Object
$category = new Category($db);

// Get Category ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(['message' => 'category_id Not Found']));

// Retrieve Category
$result = $category->read_single($id);
$num = $result->rowCount();

// Check if the category exists
if ($num > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    extract($row);

    // Create response array
    $category_item = array(
        'id' => $id,
        'category' => $category
    );

    // Output JSON
    echo json_encode($category_item);
} else {
    // Category Not Found
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
}

?>
