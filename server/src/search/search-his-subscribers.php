<?php
require_once __DIR__ . '../../actions/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($data['user_id'])) {
    $name = $data['name'];
    $user_id = $data['user_id'];

    $hisSubscriptions = searchSubscribersByName($name, $user_id);

    header('Content-Type: application/json');
    echo json_encode($hisSubscriptions);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
