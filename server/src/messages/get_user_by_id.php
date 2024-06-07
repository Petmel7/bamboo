<?php

require_once '../actions/helpers.php';

try {
    $conn = getPDO();

    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($users);
} catch (PDOException $e) {

    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} finally {
    if ($conn !== null) {
        $conn = null;
    }
}
