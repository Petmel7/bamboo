<?php
require_once __DIR__ . '../../actions/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($_SESSION['user']['name'])) {
    $name = $data['name'];
    $loggedInUsername = $_SESSION['user']['name'];

    $results = searchMyFriendsByName($name, $loggedInUsername);

    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
