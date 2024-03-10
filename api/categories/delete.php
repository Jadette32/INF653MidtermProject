<?php

require_once('../config/database.php');


// Check request method
$method = $_SERVER['REQUEST_METHOD'];

// Get category ID from query parameters
$category_id = isset($_GET['id']) ? $_GET['id'] : null;

// Define response array
$response = array();

// Check if the request is a DELETE request
if ($method === 'DELETE') {
    // Check if category ID is provided
    if ($category_id) {
        try {
            // Create a new instance of the Database class
            $database = new database();
            $pdo = $database->pdo;

            // Prepare SQL statement
            $sql = "DELETE FROM categories WHERE id = :id";

            // Prepare and execute the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $category_id);

            if ($stmt->execute()) {
                $response['message'] = 'Category deleted successfully';
                http_response_code(200); // Set response status code to 200 (OK)
            } else {
                $response['message'] = 'Failed to delete category';
                http_response_code(500); // Set response status code to 500 (Internal Server Error)
            }
        } catch (PDOException $e) {
            // Handle any database connection errors
            $response['error'] = 'Database connection error: ' . $e->getMessage();
            http_response_code(500); // Set response status code to 500 (Internal Server Error)
        }
    } else {
        // Missing required parameter
        $response['error'] = 'Missing required parameter';
        http_response_code(400); // Set response status code to 400 (Bad Request)
    }
} else {
    // Method not allowed
    $response['error'] = 'Method not allowed';
    http_response_code(405); // Set response status code to 405 (Method Not Allowed)
}

// Output response
echo json_encode($response);

