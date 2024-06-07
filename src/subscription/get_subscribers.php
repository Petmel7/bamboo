<?php
require_once '../actions/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_id'])) {
    $user_id = $data['user_id'];

    $subscribers = getSubscribers($user_id);

    header('Content-Type: application/json');
    echo json_encode($subscribers);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
