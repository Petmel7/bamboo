<?php
require_once '../actions/helpers.php';

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

try {
    $conn = getPDO();

    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users['error'] = 'Database error: ' . $e->getMessage();
} finally {
    if ($conn !== null) {
        $conn = null;
    }
}

$responseData = [
    'messages' => $messages,
    'users' => $users
];

header('Content-Type: application/json');
echo json_encode(['success' => $responseData]);
