<?php
function onConnectHandler($connection)
{
    echo "Connection open\n";
    $connection->onWebSocketConnect = function ($connection) {
        global $connectedUsers, $connectionUserMap;

        if (isset($_GET['sender_id']) && isset($_GET['recipient_id'])) {
            $senderId = $_GET['sender_id'];
            $recipientId = $_GET['recipient_id'];

            echo "Sender ID: $senderId, Recipient ID: $recipientId\n";

            $connectedUsers[$senderId] = $connection;
            $connectedUsers[$recipientId] = $connection;

            $connectionUserMap[$connection->id] = [$senderId, $recipientId];

            echo "User $senderId connected as sender\n";
            echo "User $recipientId connected as recipient\n";

            // Завантаження та відправка повідомлень при підключенні
            // sendInitialMessages($connection, $senderId, $recipientId);
        } else {
            echo "No sender or recipient ID provided\n";
        }
    };
}

function onMessageHandler($connection, $data, &$connectedUsers, &$connectionUserMap)
{
    $message = json_decode($data, true);
    // var_dump($message);
    if ($message !== null && isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        if (saveMessage($senderId, $recipientId, $messageText)) {
            $responseMessage = ['echo_message' => [$message]];
            var_dump($responseMessage);

            // Відправка повідомлення отримувачу
            if (isset($connectedUsers[$recipientId])) {
                $recipientConnection = $connectedUsers[$recipientId];
                $recipientConnection->send(json_encode($responseMessage));
                echo "Message sent to recipient with ID $recipientId\n";
            } else {
                echo "Recipient with ID $recipientId is not connected\n";
            }

            // Відправка повідомлення відправнику
            if (isset($connectedUsers[$senderId])) {
                $senderConnection = $connectedUsers[$senderId];
                $senderConnection->send(json_encode($responseMessage));
                echo "Message sent to sender with ID $senderId\n";
            } else {
                echo "Sender with ID $senderId is not connected\n";
            }
        } else {
            echo "Failed to save message\n";
        }
    } else {
        echo "Invalid message format or missing data\n";
    }
}

function onCloseHandler($connection, &$connectedUsers, &$connectionUserMap)
{
    if (isset($connectionUserMap[$connection->id])) {
        list($senderId, $recipientId) = $connectionUserMap[$connection->id];

        unset($connectionUserMap[$connection->id]);
        unset($connectedUsers[$senderId]);
        unset($connectedUsers[$recipientId]);

        echo "Connection closed for sender $senderId and recipient $recipientId\n";
    } else {
        echo "Connection closed\n";
    }
}

// function sendInitialMessages($connection, $senderId, $recipientId)
// {
//     try {
//         $messages = getMessagesByRecipient($senderId, $recipientId);
//         $users = getAllUsers();

//         $responseData = [
//             'messages' => $messages,
//             'users' => $users
//         ];

//         $connection->send(json_encode(['success' => $responseData]));
//     } catch (Exception $e) {
//         $connection->send(json_encode(['error' => $e->getMessage()]));
//     }
// }
