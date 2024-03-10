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
        $sql = "SELECT quotes.id, quotes.quote, authors.author AS author, categories.category AS category
                FROM quotes
                INNER JOIN authors ON quotes.author_id = authors.id
                INNER JOIN categories ON quotes.category_id = categories.id";

        // Prepare and execute the statement
        $stmt = $pdo->query($sql);

        // Check if quotes exist
        if ($stmt->rowCount() > 0) {
            // Fetch quotes from the database
            $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Set response data
            $response['quotes'] = $quotes;
            http_response_code(200);
        } else {
            // No quotes found
            $response['message'] = 'No quotes found';
            http_response_code(404);
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

ob_end_flush();

