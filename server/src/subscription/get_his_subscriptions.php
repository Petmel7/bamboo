<?php

require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$user_id = $_GET['user_id'] ?? null;

if ($user_id !== null) {
    $hisSubscriptions = getSubscriptions($user_id);

    header('Content-Type: application/json');
    echo json_encode($hisSubscriptions);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
