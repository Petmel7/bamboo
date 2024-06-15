<?php

require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['recipient_id']) && isset($data['sender_id'])) {
    $currentUserId = $data['recipient_id'];
    $recipientId = $data['sender_id'];

    $messageAuthors = markMessageAsViewed($currentUserId, $recipientId);

    header('Content-Type: application/json');
    echo json_encode(['message_authors' => $messageAuthors]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
