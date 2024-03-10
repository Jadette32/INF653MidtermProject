<?php
ob_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}
// Include database configuration
require_once('config/database.php');

// Define response array
$response = array();

try {
    // Create a new instance of the Database class
    $database = new database();
    $pdo = $database->pdo;

    // Check if the request is a GET request
    if ($method === 'GET') {
        // Prepare SQL statement
        $sql = "SELECT * FROM categories";

        // Prepare and execute the statement
        $stmt = $pdo->query($sql);

        // Check if categories exist
        if ($stmt->rowCount() > 0) {
            // Fetch categories from the database
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Set response data
            $response['categories'] = $categories;
            http_response_code(200);
        } else {
            // No categories found
            $response['message'] = 'No categories found';
            http_response_code(404);
        }
    } else {
        // Method not allowed
        $response['message'] = 'Method not allowed';
        http_response_code(405);
    }
} catch (PDOException $e) {
    // Handle any database connection errors
    $response['error'] = 'Database connection error: ' . $e->getMessage();
    http_response_code(500); // Set response status code to 500 (Internal Server Error)
}

// Output response
echo json_encode($response);

ob_end_flush();

