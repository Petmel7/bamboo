<?php

require_once __DIR__ . '../../vendor/autoload.php';
require_once __DIR__ . '../../src/actions/helpers.php';

use Workerman\Worker;

$ws_worker = new Worker('websocket://0.0.0.0:2346');

$connectedUsers = [];
$connectionUserMap = [];

$ws_worker->onConnect = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    echo "Connection open\n";
    $connection->onWebSocketConnect = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
        if (isset($_GET['sender_id']) && isset($_GET['recipient_id'])) {
            $senderId = $_GET['sender_id'];
            $recipientId = $_GET['recipient_id'];

            $connectedUsers[$connection->id] = $connection;
            $connectionUserMap[$connection->id] = [$senderId, $recipientId];

            echo "User $senderId connected as sender\n";
            echo "User $recipientId connected as recipient\n";

            $messages = getMessagesByRecipient($senderId, $recipientId);
            $users = getAllUsers();

            $responseData = [
                'messages' => $messages,
                'users' => $users
            ];

            $connection->send(json_encode(['success' => $responseData]));
        } else {
            echo "No sender or recipient ID provided\n";
        }
    };
};

$ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
    $message = json_decode($data, true);

    if (isset($message['action']) && $message['action'] === 'delete' && isset($message['message_id'])) {
        $messageId = $message['message_id'];

        try {
            $conn = getPDO();

            $sql = "DELETE FROM `messages` WHERE id = :message_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->execute();

            $senderId = $message['sender_id'];
            $recipientId = $message['recipient_id'];

            $messages = getMessagesByRecipient($senderId, $recipientId);
            $users = getAllUsers();

            $responseData = [
                'messages' => $messages,
                'users' => $users
            ];

            foreach ($connectedUsers as $userConnection) {
                if (isset($connectionUserMap[$userConnection->id])) {
                    list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
                    if ($senderId == $senderIdMap && $recipientId == $recipientIdMap) {
                        $userConnection->send(json_encode(['delete' => $responseData]));
                    }
                }
            }
        } catch (PDOException $e) {
            echo "Error deleting message: " . $e->getMessage();
        } finally {
            if ($conn !== null) {
                $conn = null;
            }
        }
    } elseif (isset($message['action']) && $message['action'] === 'update' && isset($message['message_id'], $message['sender_id'], $message['recipient_id'], $message['message_text'])) {
        $messageId = $message['message_id'];
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        try {
            $conn = getPDO();
            $sql = "UPDATE `messages` SET message_text = :message_text WHERE id = :message_id AND sender_id = :sender_id AND recipient_id = :recipient_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':message_text', $messageText, PDO::PARAM_STR);
            $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
            $stmt->execute();

            $messages = getMessagesByRecipient($senderId, $recipientId);
            $users = getAllUsers();

            $responseData = [
                'action' => 'update',
                'message_id' => $messageId,
                'message_text' => $messageText,
                'messages' => $messages,
                'users' => $users
            ];

            foreach ($connectedUsers as $userConnection) {
                if (isset($connectionUserMap[$userConnection->id])) {
                    list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
                    if (($senderIdMap == $senderId && $recipientIdMap == $recipientId) || ($senderIdMap == $recipientId && $recipientIdMap == $senderId)) {
                        $userConnection->send(json_encode($responseData));
                    }
                }
            }
        } catch (PDOException $e) {
            // Відправити JSON-відповідь з помилкою
            $connection->send(json_encode(['error' => "Error updating message: " . $e->getMessage()]));
        } finally {
            if ($conn !== null) {
                $conn = null;
            }
        }
    } elseif (!isset($message['action']) && isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        saveMessage($senderId, $recipientId, $messageText);

        $messages = getMessagesByRecipient($senderId, $recipientId);
        $users = getAllUsers();

        $responseData = [
            'messages' => $messages,
            'users' => $users
        ];

        foreach ($connectedUsers as $userConnection) {
            if (isset($connectionUserMap[$userConnection->id])) {
                list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
                if (($senderId == $senderIdMap && $recipientId == $recipientIdMap) || ($senderId == $recipientIdMap && $recipientId == $senderIdMap)) {
                    $userConnection->send(json_encode(['success' => $responseData]));
                }
            }
        }
    }
};

$ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    if (isset($connectionUserMap[$connection->id])) {
        list($senderId, $recipientId) = $connectionUserMap[$connection->id];

        unset($connectedUsers[$connection->id]);
        unset($connectionUserMap[$connection->id]);

        echo "Connection closed for sender $senderId and recipient $recipientId\n";
    } else {
        echo "Connection closed\n";
    }
};

Worker::runAll();
