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

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    // Redirect to read_single.php to handle a single category request
    require 'read_single.php';
    exit();
}

// Retrieve All Categories
$result = $category->read();
$num = $result->rowCount();

// Check if any categories exist
if ($num > 0) {
    // Category Array (WITHOUT "data" wrapper)
    $categories_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $category_item = array(
            'id' => $id,
            'category' => $category
        );

        // Push to array
        array_push($categories_arr, $category_item);
    }

    // Convert to JSON & Output
    echo json_encode($categories_arr);
} else {
    // No Categories Found
    echo json_encode(
        array('message' => 'No Categories Found')
    );
}

?>
