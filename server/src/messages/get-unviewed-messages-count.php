<?php

require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient_id'])) {
    $currentUserId = $data['recipient_id'];

    $unviewedMessagesCount = getUnviewedMessagesCount($currentUserId);

    header('Content-Type: application/json');
    echo json_encode(['unviewed_messages_count' => $unviewedMessagesCount]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
