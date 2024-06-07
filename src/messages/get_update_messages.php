<?php

require_once __DIR__ . '../../actions/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $messageId = $data['id'];

    $messageText = getUpdateMessages($messageId);

    header('Content-Type: application/json');
    echo json_encode(['message_text' => $messageText]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function getUpdateMessages($messageId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * 
        FROM messages
        WHERE id = :messageId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':messageId', $messageId);
        $stmt->execute();

        $messageText = $stmt->fetch(PDO::FETCH_ASSOC);

        return $messageText;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
