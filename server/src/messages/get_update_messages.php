<?php

require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $messageId = $data['id'];

    $messageText = getUpdateMessages($messageId);

    header('Content-Type: application/json');
    echo json_encode(['message_text' => $messageText]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
