<?php
require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['subscriber_id']) && isset($data['target_user_id'])) {
    $subscriber_id = $data['subscriber_id'];
    $target_user_id = $data['target_user_id'];

    $success = removeSubscription($subscriber_id, $target_user_id);

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
