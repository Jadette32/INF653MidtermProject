<?php

require_once('../config/database.php');


// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $data = json_decode(file_get_contents("php://input"));

    // Define response array
    $response = array();

    // Check if category field is provided
    if (isset($data->category)) {
        try {
            // Create a new instance of the Database class
            $database = new database();
            $pdo = $database->pdo;

            // Prepare SQL statement
            $sql = "INSERT INTO categories (category) VALUES (:category)";

            // Prepare and execute the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':category', $data->category);

            if ($stmt->execute()) {
                $response['message'] = 'Category created successfully';
                http_response_code(201); // Set response status code to 201 (Created)
            } else {
                $response['message'] = 'Failed to create category';
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


