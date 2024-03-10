<?php

require_once('../config/database.php');


// Define response array
$response = array();

try {
    // Create a new instance of the Database class
    $database = new database();
    $pdo = $database->pdo;

    // Get category ID from query parameters
    $category_id = isset($_GET['id']) ? $_GET['id'] : null;

    // Check if the request is a GET request
    if ($method === 'GET') {
        // Check if category ID is provided
        if ($category_id) {
            // Prepare SQL statement
            $sql = "SELECT * FROM categories WHERE id = :id";

            // Prepare and execute the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $category_id);
            $stmt->execute();

            // Check if category exists
            if ($stmt->rowCount() > 0) {
                // Fetch category from the database
                $category = $stmt->fetch(PDO::FETCH_ASSOC);

                // Set response data
                $response['category'] = $category;
                http_response_code(200);
            } else {
                // Category not found
                $response['message'] = 'Category not found';
                http_response_code(404);
            }
        } else {
            // Missing required parameter
            $response['message'] = 'Missing required parameter';
            http_response_code(400);
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


