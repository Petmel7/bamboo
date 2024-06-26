
<?php

require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_id'])) {
    $user_id = $data['user_id'];

    $subscriptions = getSubscriptions($user_id);

    header('Content-Type: application/json');
    echo json_encode($subscriptions);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
