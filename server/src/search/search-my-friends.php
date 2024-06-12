<?php
require_once '../actions/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($_SESSION['user']['name'])) {
    $name = $data['name'];
    $loggedInUsername = $_SESSION['user']['name'];

    $results = searchMyFriendsByName($name, $loggedInUsername);

    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function searchMyFriendsByName($name, $loggedInUsername)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.name, users.avatar FROM users
        INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
        WHERE users.name LIKE :search AND users.name <> :loggedInUsername
        AND subscriptions.subscriber_id = :loggedInUserId";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUserId', $_SESSION['user']['id'], PDO::PARAM_INT);
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
