<?php
$avatarPath = 'uploads/avatar_4367658739.jpg';

$pdo = getPDO();
$query = "INSERT INTO users (avatar) VALUES (:avatar)";
$params = [
    'avatar' => $avatarPath,
];

$stmt = $pdo->prepare($query);

try {
    $stmt->execute($params);
    echo json_encode(['success' => true, 'image' => 'Фото за замовчуванням завантажене успішно!']);
} catch (\Exception $e) {
    echo json_encode(['success' => false, 'image' => 'Помилка: ' . $e->getMessage()]);
}
