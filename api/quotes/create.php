<?php

require_once('../config/database.php');


// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

// Define response array
$response = array();

// Check request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the request is a POST request
if ($method === 'POST') {
    try {
        // Create a new instance of the Database class
        $database = new database();
        $pdo = $database->pdo;

        // Check if required fields are provided
        if (isset($data['quote']) && isset($data['author_id']) && isset($data['category_id'])) {
            // Prepare SQL statement
            $sql = "INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";

            // Prepare and execute the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':quote', $data['quote']);
            $stmt->bindParam(':author_id', $data['author_id']);
            $stmt->bindParam(':category_id', $data['category_id']);

            if ($stmt->execute()) {
                $response['message'] = 'Quote created successfully';
                http_response_code(201);
            } else {
                // Error creating quote
                $response['message'] = 'Error creating quote';
                http_response_code(500);
            }
        } else {
            // Missing required parameters
            $response['message'] = 'Missing required parameters';
            http_response_code(400);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        $response['message'] = 'Database connection error: ' . $e->getMessage();
        http_response_code(500);
    }
} else {
    // Method not allowed
    $response['message'] = 'Method not allowed';
    http_response_code(405);
}

// Output response
echo json_encode($response);


