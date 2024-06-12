<?php

require_once __DIR__ . '/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient_id'])) {
    $currentUserId = $data['recipient_id'];

    $messageAuthors = getMessageAuthorsForCurrentUser($currentUserId);

    header('Content-Type: application/json');
    echo json_encode(['message_authors' => $messageAuthors]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function getMessageAuthorsForCurrentUser($currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * 
        FROM messages
        WHERE recipient_id = :currentUserId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();

        $messageAuthors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $messageAuthors;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}
