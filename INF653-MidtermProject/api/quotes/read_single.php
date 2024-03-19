<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quotes = new Quotes($db);

if (isset($_GET['id'])) {
    $quotes->id = $_GET['id'];
    $quotes->read_single();

    if (!empty($quotes->quote)) {
        $quotes_arr = array(
            'id' => $quotes->id,
            'quote' => $quotes->quote,
            'author' => $quotes->author,
            'category' => $quotes->category
        );
        echo json_encode($quotes_arr, JSON_NUMERIC_CHECK);
    } else {
        echo json_encode(array('message' => 'No Quote Found'));
    }
} elseif (isset($_GET['author_id'])) {
    $quotes->author_id = $_GET['author_id'];
    $quotes_arr = $quotes->read_single();
    echo json_encode($quotes_arr, JSON_NUMERIC_CHECK);
} elseif (isset($_GET['category_id'])) {
    $quotes->category_id = $_GET['category_id'];
    $quotes_arr = $quotes->read_single();
    echo json_encode($quotes_arr, JSON_NUMERIC_CHECK);
} else {
    echo json_encode(array('message' => 'No Parameters Provided'));
}
?>
