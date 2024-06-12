<?php
require_once __DIR__ . '/helpers.php';

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

function markMessageAsViewed($currentUserId, $recipientId)
{
    try {
        $conn = getPDO();

        $sql = "UPDATE messages 
                SET viewed = 1 
                WHERE recipient_id = :currentUserId AND sender_id = :recipientId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->bindParam(':recipientId', $recipientId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'No messages updated'];
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return ['success' => false, 'message' => 'Error updating message status'];
    }
}
