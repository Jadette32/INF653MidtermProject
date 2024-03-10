<?php

require_once('../config/database.php');



// Get POST data
$data = json_decode(file_get_contents("php://input"));

// Check if data is not empty
if (!empty($data->author)) {
    try {
        // Create a new instance of the Database class
        $database = new database();
        $pdo = $database->pdo;

        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO authors (author) VALUES (:author)");

        // Bind parameters
        $stmt->bindParam(':author', $data->author);

        // Execute the statement
        if ($stmt->execute()) {
            // Author successfully created
            echo json_encode(array('message' => 'Author created successfully'));
        } else {
            // Failed to create author
            echo json_encode(array('error' => 'Failed to create author'));
        }
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo json_encode(array('error' => 'Database connection error: ' . $e->getMessage()));
    }
} else {
    // Missing required parameters
    echo json_encode(array('error' => 'Missing required parameters'));
}


