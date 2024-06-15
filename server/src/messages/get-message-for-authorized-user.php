<?php

require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient_id'])) {
    $currentUserId = $data['recipient_id'];

    $messageAuthors = getMessageAuthorsForCurrentUser($currentUserId);

    header('Content-Type: application/json');
    echo json_encode(['message_authors' => $messageAuthors]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
