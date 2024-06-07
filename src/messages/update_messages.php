<?php
require_once '../actions/helpers.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageId = $data['message_id'] ?? null;

    if ($messageId !== null) {
        try {
            $conn = getPDO();

            $sql = "UPDATE `messages` SET message_text = :message_text WHERE id = :message_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':message_text', $data['message_text'], PDO::PARAM_STR);
            $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->execute();

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        } finally {
            if ($conn !== null) {
                $conn = null;
            }
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
}
