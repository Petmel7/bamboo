<?php

require_once __DIR__ . '../../vendor/autoload.php';
require_once __DIR__ . '../../src/actions/helpers.php';
require_once __DIR__ . '../../../server/src/services/UserService.php';
require_once __DIR__ . '../../ws/ws_handlers.php';

use Workerman\Worker;

$ws_worker = new Worker('websocket://0.0.0.0:2346');

$connectedUsers = [];
$connectionUserMap = [];

$ws_worker->onConnect = function ($connection) use (&$connectedUsers, &$connectionUserMap) {

    onConnectHandler($connection, $connectedUsers, $connectionUserMap);
};

$ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
    $message = json_decode($data, true);

    if (isset($message['action'])) {
        switch ($message['action']) {
            case 'delete':
                if (isset($message['message_id'], $message['sender_id'], $message['recipient_id'])) {
                    $messageId = $message['message_id'];
                    $senderId = $message['sender_id'];
                    $recipientId = $message['recipient_id'];

                    onMessageHandlerDelete($messageId, $senderId, $recipientId, $connectedUsers, $connectionUserMap);
                }
                break;

            case 'add_image':
                if (isset($message['image_url'], $message['sender_id'], $message['recipient_id'])) {
                    $imageUrl = $message['image_url'];
                    $senderId = $message['sender_id'];
                    $recipientId = $message['recipient_id'];

                    onMessageHandlerAddImage($imageUrl, $senderId, $recipientId, $connectedUsers, $connectionUserMap);
                }
                break;

            case 'update':
                if (isset($message['message_id'], $message['sender_id'], $message['recipient_id'], $message['message_text'])) {
                    $messageId = $message['message_id'];
                    $senderId = $message['sender_id'];
                    $recipientId = $message['recipient_id'];
                    $messageText = $message['message_text'];

                    onMessageHandlerUpdate($messageId, $senderId, $recipientId, $messageText, $connectedUsers, $connectionUserMap);
                }
                break;

            default:
                echo "Unknown action: " . $message['action'];
        }
    } else if (isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        onMessageHandlerSend($senderId, $recipientId, $messageText, $connectedUsers, $connectionUserMap, $connection);
    } else {
        echo "Invalid message format.";
    }
};

$ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    onCloseHandler($connection, $connectedUsers, $connectionUserMap);
};

Worker::runAll();
