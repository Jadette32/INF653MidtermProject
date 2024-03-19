<?php
// read.php

// Set headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate Database object
$database = new Database();
$db = $database->connect();

// Instantiate Quote object
$quotes = new Quotes($db);

// Read quotes
$quotes_arr = $quotes->display_quotes();

// Check if any quotes found
if (!empty($quotes_arr)) {
    // Return JSON response with quotes array
    echo json_encode($quotes_arr);
} else {
    // No quotes found, return empty array
    echo json_encode(array());
}
