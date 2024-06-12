<?php
require_once '../actions/helpers.php';

$user_id = $_GET['user_id'] ?? null;

if ($user_id !== null) {
    $hisSubscribers = getSubscribers($user_id);

    header('Content-Type: application/json');
    echo json_encode($hisSubscribers);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
