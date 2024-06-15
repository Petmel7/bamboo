<?php

require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentUserId = $data['sender_id'] ?? null;
    $otherUserId = $data['recipient_id'] ?? null;

    deleteAllmessages($currentUserId, $otherUserId);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
}
