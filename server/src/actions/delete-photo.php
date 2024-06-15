<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

if (isset($_SESSION['user']['id'])) {

    $userId = $_SESSION['user']['id'];
    $defaultImagePath = 'uploads/avatar_4367658739.jpg';

    deletePhoto($userId, $defaultImagePath);
} else {
    echo "User session not found.";
}
