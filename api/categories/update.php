<?php

require_once('../config/database.php');


// Define response array
$response = array();

try {
    // Create a new instance of the Database class
    $database = new database();
    $pdo = $database->pdo;

    // Get PUT data
    parse_str(file_get_contents("php://input"), $data);

    // Get category ID from PUT data
    $category_id = isset($data['id']) ? $data['id'] : null;

    // Check if the request is a PUT request
    if ($method === 'PUT') {
        // Check if category ID is provided
        if ($category_id) {
            // Check if category field is provided
            if (isset($data['category'])) {
                // Prepare SQL statement
                $sql = "UPDATE categories SET category = :category WHERE id = :id";

                // Prepare and execute the statement
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':category', $data['category']);
                $stmt->bindParam(':id', $category_id);

                if ($stmt->execute()) {
                    $response['message'] = 'Category updated successfully';
                    http_response_code(200);
                } else {
                    $response['message'] = 'Failed to update category';
                    http_response_code(500);
                }
            } else {
                // Missing required parameter
                $response['message'] = 'Missing category parameter';
                http_response_code(400);
            }
        } else {
            // Missing required parameter
            $response['message'] = 'Missing ID parameter';
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


