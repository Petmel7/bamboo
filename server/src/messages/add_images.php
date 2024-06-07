<?php

require_once __DIR__ . '../../actions/helpers.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imageFile = $_FILES['image'];
    $senderId = $_POST['sender_id'];
    $recipientId = $_POST['recipient_id'];

    $targetFile = uploadFile($imageFile, "image_url");

    if ($targetFile) {

        echo json_encode(['image_url' => $targetFile]);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
