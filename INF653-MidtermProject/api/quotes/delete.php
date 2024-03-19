<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quotes = new Quotes($db);

$data = json_decode(file_get_contents("php://input"));

// Check if ID is provided in the request
if (!empty($data->id)) {
    $quotes->id = $data->id;

    if($quotes->delete()) {
        // Return a single JSON object with the deleted quote id
        echo json_encode(
            array('id' => $quotes->id)
        );
    } else {
        // Return an error message if the quote deletion fails
        http_response_code(500); // Internal Server Error
        echo json_encode(
            array('message' => 'Failed to delete quote')
        );
    }
} else {
    // Return an error message if ID is not provided
    http_response_code(400); // Bad Request
    echo json_encode(
        array('message' => 'Missing ID parameter')
    );
}
?>
