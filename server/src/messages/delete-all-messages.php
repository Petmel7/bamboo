<?php
require_once '../actions/helpers.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentUserId = $data['sender_id'] ?? null;
    $otherUserId = $data['recipient_id'] ?? null;

    if ($currentUserId !== null && $otherUserId !== null) {
        try {
            $conn = getPDO();

            $sql = "DELETE FROM `messages` WHERE (sender_id = :current_user_id AND recipient_id = :other_user_id) OR (sender_id = :other_user_id AND recipient_id = :current_user_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':current_user_id', $currentUserId, PDO::PARAM_INT);
            $stmt->bindParam(':other_user_id', $otherUserId, PDO::PARAM_INT);
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
