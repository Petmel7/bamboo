<?php

// require_once __DIR__ . '/helpers.php';
// require_once __DIR__ . '../../services/UserService.php';

// $loggedInUsername = getLoggedInUsername();

// $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
// $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;

// $friends = handleGetRequest($loggedInUsername, $page, $limit);

// header('Content-Type: application/json');
// echo json_encode($friends);


require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$loggedInUsername = getLoggedInUsername();

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
$offset = ($page - 1) * $limit;

$friends = handleGetRequest($loggedInUsername, $limit, $offset);

header('Content-Type: application/json');
echo json_encode($friends);

function handleGetRequest($loggedInUsername, $limit, $offset)
{
    try {
        $conn = getPDO();

        $sql = "SELECT name, avatar FROM users WHERE name <> :loggedInUsername LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}
