<?php

require_once('../config/database.php');


// Set response headers for CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get PUT data
$data = json_decode(file_get_contents("php://input"), true);

// Define response array
$response = array();

try {
    // Create a new instance of the Database class
    $database = new database();
    $pdo = $database->pdo;

    // Get quote ID from PUT data
    $quote_id = isset($data['id']) ? $data['id'] : null;

    // Check if the request is a PUT request
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Check if quote ID is provided
        if ($quote_id) {
            // Check if required fields are provided
            if (isset($data['quote']) && isset($data['author_id']) && isset($data['category_id'])) {
                // Prepare SQL statement
                $sql = "UPDATE quotes SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id";

                // Prepare and execute the statement
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':quote', $data['quote']);
                $stmt->bindParam(':author_id', $data['author_id']);
                $stmt->bindParam(':category_id', $data['category_id']);
                $stmt->bindParam(':id', $quote_id);

                if ($stmt->execute()) {
                    $response['message'] = 'Quote updated successfully';
                    http_response_code(200);
                } else {
                    // Error updating quote
                    $response['message'] = 'Error updating quote';
                    http_response_code(500);
                }
            } else {
                // Missing required parameters
                $response['message'] = 'Missing required parameters';
                http_response_code(400);
            }
        } else {
            // Missing quote ID
            $response['message'] = 'Missing quote ID';
            http_response_code(400);
        }
    } else {
        // Method not allowed
        $response['message'] = 'Method not allowed';
        http_response_code(405);
    }
} catch (PDOException $e) {
    // Handle database connection errors
    $response['message'] = 'Database connection error: ' . $e->getMessage();
    http_response_code(500);
}

// Output response
echo json_encode($response);


