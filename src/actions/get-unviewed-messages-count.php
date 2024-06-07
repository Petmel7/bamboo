<?php
require_once __DIR__ . '/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient_id'])) {
    $currentUserId = $data['recipient_id'];

    $unviewedMessagesCount = getUnviewedMessagesCount($currentUserId);

    header('Content-Type: application/json');
    echo json_encode(['unviewed_messages_count' => $unviewedMessagesCount]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function getUnviewedMessagesCount($currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT COUNT(*) AS unviewed_messages_count 
                FROM messages 
                WHERE recipient_id = :currentUserId 
                AND viewed = 0";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['unviewed_messages_count'];
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return 0;
    }
}
