<?php

require_once('../config/database.php');


// Define response array
$response = array();

// Check request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the request is a DELETE request
if ($method === 'DELETE') {
    try {
        // Create a new instance of the Database class
        $database = new database();
        $pdo = $database->pdo;

        // Get quote ID from query parameters
        $quote_id = isset($_GET['id']) ? $_GET['id'] : null;

        // Check if quote ID is provided
        if ($quote_id) {
            // Prepare SQL statement
            $sql = "DELETE FROM quotes WHERE id = :id";

            // Prepare and execute the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $quote_id);

            if ($stmt->execute()) {
                // Check if any rows were affected
                if ($stmt->rowCount() > 0) {
                    // Quote deleted successfully
                    $response['message'] = 'Quote deleted successfully';
                    http_response_code(200);
                } else {
                    // No quote found to delete
                    $response['message'] = 'No quote found to delete';
                    http_response_code(404);
                }
            } else {
                // Error deleting quote
                $response['message'] = 'Error deleting quote';
                http_response_code(500);
            }
        } else {
            // Missing required parameter
            $response['message'] = 'Missing required parameter';
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


