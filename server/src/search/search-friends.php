<?php

require_once '../actions/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($_SESSION['user']['name'])) {
    $name = $data['name'];
    $loggedInUsername = $_SESSION['user']['name'];

    $results = searchFriendsByName($name, $loggedInUsername);

    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function searchFriendsByName($name, $loggedInUsername)
{
    try {
        $conn = getPDO();
        $sql = "SELECT name, avatar FROM users WHERE name LIKE :search AND name <> :loggedInUsername";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
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
