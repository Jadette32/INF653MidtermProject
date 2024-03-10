<?php

require_once('../config/database.php');



// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Decode the JSON data sent in the request body
    $data = json_decode(file_get_contents("php://input"));

    // Check if the required fields are present
    if (empty($data->id) || empty($data->author)) {
        // If any required fields are missing, return an error response
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required parameters'));
        exit();
    }

    try {
        // Create a new instance of the Database class
        $database = new database();
        $pdo = $database->pdo;

        // Prepare the SQL statement for updating the author record
        $stmt = $pdo->prepare("UPDATE authors SET author = :author WHERE id = :id");

        // Bind parameters
        $stmt->bindParam(':author', $data->author);
        $stmt->bindParam(':id', $data->id);

        // Execute the SQL statement
        $stmt->execute();

        // Check if any rows were affected
        if ($stmt->rowCount() > 0) {
            // If the update was successful, return a success response
            echo json_encode(array('message' => 'Author updated successfully'));
        } else {
            // If no rows were affected, return a not found response
            http_response_code(404);
            echo json_encode(array('error' => 'No authors found to update'));
        }
    } catch (PDOException $e) {
        // Handle any database connection errors
        http_response_code(500);
        echo json_encode(array('error' => 'Database connection error: ' . $e->getMessage()));
    }
} else {
    // If the request method is not PUT, return a method not allowed response
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}


