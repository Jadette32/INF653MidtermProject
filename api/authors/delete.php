<?php

require_once('../config/database.php');



// Get author ID from request parameters
$author_id = isset($_GET['id']) ? $_GET['id'] : null;

// Check if author ID is provided
if ($author_id !== null) {
    try {
        // Create a new instance of the Database class
        $database = new database();
        $pdo = $database->pdo;

        // Prepare the SQL statement
        $stmt = $pdo->prepare("DELETE FROM authors WHERE id = :author_id");

        // Bind parameters
        $stmt->bindParam(':author_id', $author_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Author successfully deleted
            echo json_encode(array('message' => 'Author deleted successfully'));
        } else {
            // Failed to delete author
            echo json_encode(array('error' => 'Failed to delete author'));
        }
    } catch (PDOException $e) {
        // Handle any database connection errors
        echo json_encode(array('error' => 'Database connection error: ' . $e->getMessage()));
    }
} else {
    // Missing author ID parameter
    echo json_encode(array('error' => 'Author ID parameter is missing'));
}


