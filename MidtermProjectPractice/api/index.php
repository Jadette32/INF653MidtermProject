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



// Define response format
$response = array();

// Include endpoint files
require_once 'authors/index.php';
require_once 'categories/index.php';
require_once 'quotes/index.php';

// Output response
echo json_encode($response);

ob_end_flush();

