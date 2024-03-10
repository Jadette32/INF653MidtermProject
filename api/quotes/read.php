<?php

require_once('../config/database.php');


// Define response array
$response = array();

try {
    // Create a new instance of the Database class
    $database = new database();
    $pdo = $database->pdo;

    // Get quote ID from query parameters
    $quote_id = isset($_GET['id']) ? $_GET['id'] : null;

    // Check if the request is a GET request
    if ($method === 'GET') {
        // Check if quote ID is provided
        if ($quote_id) {
            // Prepare SQL statement
            $sql = "SELECT quotes.id, quotes.quote, authors.author AS author, categories.category AS category
                    FROM quotes
                    INNER JOIN authors ON quotes.author_id = authors.id
                    INNER JOIN categories ON quotes.category_id = categories.id
                    WHERE quotes.id = :id";

            // Prepare and execute the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $quote_id);
            $stmt->execute();

            // Check if quote exists
            if ($stmt->rowCount() > 0) {
                // Fetch quote from the database
                $quote = $stmt->fetch(PDO::FETCH_ASSOC);

                // Set response data
                $response['quote'] = $quote;
                http_response_code(200);
            } else {
                // Quote not found
                $response['message'] = 'Quote not found';
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
    // Handle database connection errors
    $response['message'] = 'Database connection error: ' . $e->getMessage();
    http_response_code(500);
}

// Output response
echo json_encode($response);


