<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

if (isset($_SESSION['user']['id'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
        $avatar = $_FILES["avatar"];

        $userId = $_SESSION['user']['id'];
        $targetPath = uploadFile($avatar, "avatar");

        changePhoto($userId, $targetPath);
    } else {
        echo json_encode(["success" => false, "message" => "Avatar file not uploaded"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User session not found"]);
}
