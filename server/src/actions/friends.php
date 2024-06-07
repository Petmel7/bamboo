<?php

require_once __DIR__ . '/helpers.php';

$loggedInUsername = getLoggedInUsername();
$friends = handleGetRequest($loggedInUsername);

header('Content-Type: application/json');
echo json_encode($friends);

function handleGetRequest($loggedInUsername)
{
    try {
        $conn = getPDO();

        $sql = "SELECT name, avatar FROM users WHERE name <> ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$loggedInUsername]);

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
