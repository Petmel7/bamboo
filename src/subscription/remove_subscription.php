<?php
require_once '../actions/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['subscriber_id']) && isset($data['target_user_id'])) {
    $subscriber_id = $data['subscriber_id'];
    $target_user_id = $data['target_user_id'];

    $success = removeSubscription($subscriber_id, $target_user_id);

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function removeSubscription($subscriber_id, $target_user_id)
{
    try {
        $conn = getPDO();

        $sql = "DELETE FROM subscriptions WHERE subscriber_id = :subscriber_id AND target_user_id = :target_user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':subscriber_id', $subscriber_id, PDO::PARAM_INT);
        $stmt->bindParam(':target_user_id', $target_user_id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {

        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}
