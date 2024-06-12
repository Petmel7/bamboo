<?php

require_once __DIR__ . '/helpers.php';

if (isset($_SESSION['user']['id'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
        $avatar = $_FILES["avatar"];

        $userId = $_SESSION['user']['id'];

        $targetPath = uploadFile($avatar, "avatar");

        $conn = getPDO();
        $updateQuery = "UPDATE users SET avatar = :avatar WHERE id = :userId";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':avatar', $targetPath);
        $stmt->bindParam(':userId', $userId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Photo updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating photo"]);
        }

        $conn = null;
        exit;
    } else {
        echo json_encode(["success" => false, "message" => "Avatar file not uploaded"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User session not found"]);
}
