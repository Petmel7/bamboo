<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$success = false;

if (isset($email) && isset($password)) {
    $user = findUser($email);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user']['id'] = $user['id'];
            $_SESSION['user']['name'] = $user['name'];
            $success = true;
        } else {
            $error_message = "Incorrect password";
        }
    } else {
        $error_message = "No user found with this email";
    }
}

echo json_encode(['success' => $success]);
