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

require_once('config/database.php');

try {
    $database = new Database();
    $pdo = $database->pdo;

    $stmt = $pdo->prepare("SELECT * FROM authors");
    $stmt->execute();
    $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($authors);
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Database connection error: ' . $e->getMessage()));
}
ob_end_flush();

