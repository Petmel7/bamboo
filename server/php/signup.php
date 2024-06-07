<?php

require_once __DIR__ . '/../hack/actions/helpers.php';

$uploadPath = '../../uploads';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $repeatPassword = $_POST['repeat-password'] ?? null;

    if (empty($name) || empty($email) || empty($password) || empty($repeatPassword)) {
        echo json_encode(['success' => false, 'message' => 'Заповніть всі поля']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Неправильний формат електронної пошти']);
        exit;
    }

    if ($password !== $repeatPassword) {
        echo json_encode(['success' => false, 'message' => 'Паролі не співпадають']);
        exit;
    }

    $avatarPath = 'uploads/avatar_4367658739.jpg';

    $pdo = getPDO();
    $query = "INSERT INTO users (name, email, avatar, password) VALUES (:name, :email, :avatar, :password)";
    $params = [
        'name' => $name,
        'email' => $email,
        'avatar' => $avatarPath,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ];

    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute($params);
        echo json_encode(['success' => true, 'message' => 'Registration was successful']);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
