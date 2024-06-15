<?php

require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$message = json_decode(file_get_contents('php://input'), true);

$messages = [];
$users = [];

if (isset($message['sender_id'], $message['recipient_id'])) {
    $senderId = $message['sender_id'];
    $recipientId = $message['recipient_id'];

    $messages = getMessagesByRecipient($senderId, $recipientId);
} else {
    $messages['error'] = 'Invalid request';
}

getUsers($users);

$responseData = [
    'messages' => $messages,
    'users' => $users
];

header('Content-Type: application/json');
echo json_encode(['success' => $responseData]);
