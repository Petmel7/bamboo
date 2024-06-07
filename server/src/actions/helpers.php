<?php

session_start();

require_once __DIR__ . '/config.php';

function getPDO(): PDO
{
    try {
        return new \PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8;dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
    } catch (\PDOException $e) {
        die('Conection error: ' . $e->getMessage());
    }
}

function redirect(string $path)
{
    header(header: "location: $path");
    die();
}

function uploadFile(array $file, string $prefix = ''): string
{
    $uploadPath = __DIR__ . '/../uploads';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = $prefix . '_' . time() . ".$ext";

    if (!move_uploaded_file($file['tmp_name'], "{$uploadPath}/{$fileName}")) {
        die('Помилка при загрузці файла на сервер!');
    }

    return "uploads/$fileName";
}

function findUser(string $email): array|bool
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE `email` = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(mode: \PDO::FETCH_ASSOC);
}

function currentUser(): array|bool
{
    $pdo = getPDO();

    if (!isset($_SESSION['user'])) {
        return false;
    }

    $userId = $_SESSION['user']['id'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch(mode: \PDO::FETCH_ASSOC);
}

function currentUserId(): ?int
{
    $pdo = getPDO();

    if (!isset($_SESSION['user']['id'])) {
        return null;
    }

    return $_SESSION['user']['id'];
}

function getUserDataByUsername($username): ?array
{
    $conn = getPDO();

    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$username]);

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($userData === false) {
        return null;
    }

    return $userData;
}

function logout(): void
{
    unset($_SESSION['user']['id']);
}

function checkAuth(): void
{
    if (!isset($_SESSION['user']['id'])) {

        redirect('index.php?page=signin');
    }
}

function checkGuest(): void
{
    if (isset($_SESSION['user']['id'])) {

        redirect('index.php?page=home');
    }
}

function getLoggedInUsername(): string|null
{
    return isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
}

//=====================================================
function getSubscriptions($user_id)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.* FROM users
                INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
                WHERE subscriptions.subscriber_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {

        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function getSubscribers($user_id)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.* FROM users
                INNER JOIN subscriptions ON users.id = subscriptions.subscriber_id
                WHERE subscriptions.target_user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {

        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function saveMessage($senderId, $recipientId, $messageText)
{
    try {
        $conn = getPDO();

        $sent_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO messages (sender_id, recipient_id, message_text, sent_at) 
        VALUES (:sender_id, :recipient_id, :message_text, :sent_at)
        ON DUPLICATE KEY UPDATE message_text = :message_text, sent_at = :sent_at";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
        $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
        $stmt->bindParam(':message_text', $messageText, PDO::PARAM_STR);
        $stmt->bindParam(':sent_at', $sent_at, PDO::PARAM_STR);

        $stmt->execute();

        return ['success' => 'Message sent successfully'];
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function getMessagesByRecipient($senderId, $recipientId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * FROM messages WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$senderId, $recipientId, $recipientId, $senderId]);

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $messages;
    } catch (PDOException $e) {

        return 'Error: ' . $e->getMessage();
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function getUserById($userId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * FROM users WHERE id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    } catch (PDOException $e) {

        return ['error' => 'Database error: ' . $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function getAllUsers()
{
    $users = [];
    try {
        $conn = getPDO();
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $users['error'] = 'Database error: ' . $e->getMessage();
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
    return $users;
}

function saveMessageWithImage($targetFile, $senderId, $recipientId)
{
    try {
        $conn = getPDO();

        $sentAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO messages (sender_id, recipient_id, image_url, sent_at) 
        VALUES (:sender_id, :recipient_id, :image_url, :sent_at)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
        $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $targetFile, PDO::PARAM_STR);
        $stmt->bindParam(':sent_at', $sentAt, PDO::PARAM_STR);

        $stmt->execute();

        return ['success' => ['Message sent successfully']];
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}
