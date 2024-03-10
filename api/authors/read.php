<?php

require_once('../config/database.php');



try {
    // Create a new instance of the Database class
    $database = new database();
    $pdo = $database->pdo;

    // Now you can use $pdo to perform database operations
    // For example:
    $stmt = $pdo->prepare("SELECT * FROM authors");
    $stmt->execute();
    $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the result as JSON
    echo json_encode($authors);
} catch (PDOException $e) {
    // Handle any database connection errors
    echo json_encode(array('error' => 'Database connection error: ' . $e->getMessage()));
}


