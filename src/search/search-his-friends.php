<?php
require_once '../actions/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($data['user_id'])) {
    $name = $data['name'];
    $user_id = $data['user_id'];

    $hisSubscriptions = searchHisSubscriptionsByName($name, $user_id);

    header('Content-Type: application/json');
    echo json_encode($hisSubscriptions);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function searchHisSubscriptionsByName($name, $user_id)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.name, users.avatar
                FROM users
                INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
                WHERE subscriptions.subscriber_id = :user_id
                AND users.name LIKE :search";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}
