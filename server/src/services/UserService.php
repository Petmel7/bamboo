<?php

function changePhoto($userId, $targetPath)
{
    $conn = getPDO();
    $updateQuery = "UPDATE users SET avatar = :avatar WHERE id = :userId";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':avatar', $targetPath);
    $stmt->bindParam(':userId', $userId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Photo updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating photo"]);
    }

    $conn = null;
    exit;
}

function deletePhoto($userId, $defaultImagePath)
{
    $conn = getPDO();

    $updateQuery = "UPDATE users SET avatar = '$defaultImagePath' WHERE id = $userId";

    if ($conn->query($updateQuery) === TRUE) {
        echo "Photo deleted successfully.";
    } else {
        echo "Error deleting photo " . implode(", ", $conn->errorInfo());
    }

    $conn = null;
}

function handleGetRequest($loggedInUsername, $limit, $offset)
{
    try {
        $conn = getPDO();

        $sql = "SELECT name, avatar FROM users WHERE name <> :loggedInUsername LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function deleteAllmessages($currentUserId, $otherUserId)
{
    if ($currentUserId !== null && $otherUserId !== null) {
        try {
            $conn = getPDO();

            $sql = "DELETE FROM `messages` WHERE (sender_id = :current_user_id AND recipient_id = :other_user_id) OR (sender_id = :other_user_id AND recipient_id = :current_user_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':current_user_id', $currentUserId, PDO::PARAM_INT);
            $stmt->bindParam(':other_user_id', $otherUserId, PDO::PARAM_INT);
            $stmt->execute();

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        } finally {
            if ($conn !== null) {
                $conn = null;
            }
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
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

function getUsers($users)
{
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

function getUpdateMessages($messageId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * 
        FROM messages
        WHERE id = :messageId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':messageId', $messageId);
        $stmt->execute();

        $messageText = $stmt->fetch(PDO::FETCH_ASSOC);

        return $messageText;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}

function getMessageAuthorsForCurrentUser($currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * 
        FROM messages
        WHERE recipient_id = :currentUserId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();

        $messageAuthors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $messageAuthors;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}

function getUnviewedMessagesCount($currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT COUNT(*) AS unviewed_messages_count 
                FROM messages 
                WHERE recipient_id = :currentUserId 
                AND viewed = 0";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['unviewed_messages_count'];
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return 0;
    }
}

function markMessageAsViewed($currentUserId, $recipientId)
{
    try {
        $conn = getPDO();

        $sql = "UPDATE messages 
                SET viewed = 1 
                WHERE recipient_id = :currentUserId AND sender_id = :recipientId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->bindParam(':recipientId', $recipientId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'No messages updated'];
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return ['success' => false, 'message' => 'Error updating message status'];
    }
}

function searchFriendsByName($name, $loggedInUsername)
{
    try {
        $conn = getPDO();
        $sql = "SELECT name, avatar FROM users WHERE name LIKE :search AND name <> :loggedInUsername";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
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

function searchHisSubscriptionsByName($name, $user_id)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.name, users.avatar
                FROM users
                INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
                WHERE subscriptions.subscriber_id = :user_id
                AND users.name LIKE :search";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
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

function searchSubscribersByName($name, $user_id)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.name, users.avatar
                FROM users
                INNER JOIN subscriptions ON users.id = subscriptions.subscriber_id
                WHERE subscriptions.target_user_id = :user_id
                AND users.name LIKE :search";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
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

function searchMyFriendsByName($name, $loggedInUsername)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.name, users.avatar FROM users
        INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
        WHERE users.name LIKE :search AND users.name <> :loggedInUsername
        AND subscriptions.subscriber_id = :loggedInUserId";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUserId', $_SESSION['user']['id'], PDO::PARAM_INT);
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

function searchMySubscribersByName($name, $loggedInUsername)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.name, users.avatar FROM users
        INNER JOIN subscriptions ON users.id = subscriptions.subscriber_id
        WHERE users.name LIKE :search AND users.name <> :loggedInUsername
        AND subscriptions.target_user_id = :loggedInUserId";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUserId', $_SESSION['user']['id'], PDO::PARAM_INT);
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

function addSubscription($subscriber_id, $target_user_id)
{
    try {
        $conn = getPDO();

        $sql = "INSERT INTO subscriptions (subscriber_id, target_user_id) VALUES (:subscriber_id, :target_user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':subscriber_id', $subscriber_id, PDO::PARAM_INT);
        $stmt->bindParam(':target_user_id', $target_user_id, PDO::PARAM_INT);
        $stmt->execute();

        return ['success' => true];
    } catch (PDOException $e) {
        return false;
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

function removeSubscription($subscriber_id, $target_user_id)
{
    try {
        $conn = getPDO();

        $sql = "DELETE FROM subscriptions WHERE subscriber_id = :subscriber_id AND target_user_id = :target_user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':subscriber_id', $subscriber_id, PDO::PARAM_INT);
        $stmt->bindParam(':target_user_id', $target_user_id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {

        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

// function saveMessage($senderId, $recipientId, $messageText)
// {
//     try {
//         $conn = getPDO();

//         $sent_at = date('Y-m-d H:i:s');

//         $sql = "INSERT INTO messages (sender_id, recipient_id, message_text, sent_at) 
//         VALUES (:sender_id, :recipient_id, :message_text, :sent_at)
//         ON DUPLICATE KEY UPDATE message_text = :message_text, sent_at = :sent_at";

//         $stmt = $conn->prepare($sql);

//         $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
//         $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
//         $stmt->bindParam(':message_text', $messageText, PDO::PARAM_STR);
//         $stmt->bindParam(':sent_at', $sent_at, PDO::PARAM_STR);

//         $stmt->execute();

//         return ['success' => 'Message sent successfully'];
//     } catch (PDOException $e) {
//         return ['error' => $e->getMessage()];
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }

// function saveMessageWithImage($targetFile, $senderId, $recipientId)
// {
//     try {
//         $conn = getPDO();

//         $sentAt = date('Y-m-d H:i:s');

//         $sql = "INSERT INTO messages (sender_id, recipient_id, image_url, sent_at) 
//         VALUES (:sender_id, :recipient_id, :image_url, :sent_at)";

//         $stmt = $conn->prepare($sql);

//         $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
//         $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
//         $stmt->bindParam(':image_url', $targetFile, PDO::PARAM_STR);
//         $stmt->bindParam(':sent_at', $sentAt, PDO::PARAM_STR);

//         $stmt->execute();

//         return ['success' => ['Message sent successfully']];
//     } catch (PDOException $e) {
//         return ['error' => $e->getMessage()];
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }
