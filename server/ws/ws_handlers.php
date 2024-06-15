<?php

// function onConnectHandler($connection, &$connectedUsers, &$connectionUserMap)
// {
//     echo "Connection open\n";
//     $connection->onWebSocketConnect = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
//         if (isset($_GET['sender_id']) && isset($_GET['recipient_id'])) {
//             $senderId = $_GET['sender_id'];
//             $recipientId = $_GET['recipient_id'];

//             $connectedUsers[$connection->id] = $connection;
//             $connectionUserMap[$connection->id] = [$senderId, $recipientId];

//             echo "User $senderId connected as sender\n";
//             echo "User $recipientId connected as recipient\n";

//             $messages = getMessagesByRecipient($senderId, $recipientId);
//             $users = getAllUsers();

//             $responseData = [
//                 'messages' => $messages,
//                 'users' => $users
//             ];

//             $connection->send(json_encode(['success' => $responseData]));
//         } else {
//             echo "No sender or recipient ID provided\n";
//         }
//     };
// }

// function onMessageHandlerDelete($messageId, $senderId, $recipientId, &$connectedUsers, &$connectionUserMap)
// {
//     try {
//         $conn = getPDO();

//         $sql = "DELETE FROM `messages` WHERE id = :message_id AND sender_id = :sender_id AND recipient_id = :recipient_id";
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
//         $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
//         $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
//         $stmt->execute();

//         $messages = getMessagesByRecipient($senderId, $recipientId);
//         $users = getAllUsers();

//         $responseData = [
//             'messages' => $messages,
//             'users' => $users
//         ];

//         echo "Connected Users: " . json_encode($connectedUsers) . "\n";
//         echo "Sending delete message to users...\n";

//         foreach ($connectedUsers as $userConnection) {
//             if (isset($connectionUserMap[$userConnection->id])) {
//                 list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
//                 if ($senderId == $senderIdMap && $recipientId == $recipientIdMap) {
//                     echo "Sending delete message to connection ID: " . $userConnection->id . "\n";
//                     $userConnection->send(json_encode(['delete' => $responseData]));
//                 }
//             }
//         }
//     } catch (PDOException $e) {
//         echo "Error deleting message: " . $e->getMessage();
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }

// function onMessageHandlerAddImage($imageUrl, $senderId, $recipientId, &$connectedUsers, &$connectionUserMap)
// {
//     try {
//         $conn = getPDO();

//         saveMessageWithImage($imageUrl, $senderId, $recipientId);

//         $messages = getMessagesByRecipient($senderId, $recipientId);
//         $users = getAllUsers();

//         $responseData = [
//             'image_url' => $imageUrl,
//             'messages' => $messages,
//             'users' => $users
//         ];

//         foreach ($connectedUsers as $userConnection) {
//             if (isset($connectionUserMap[$userConnection->id])) {
//                 list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
//                 if ($senderId == $senderIdMap && $recipientId == $recipientIdMap) {
//                     $userConnection->send(json_encode(['add_image' => $responseData]));
//                 }
//             }
//         }
//     } catch (PDOException $e) {
//         echo "Error adding image: " . $e->getMessage();
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }

// function onMessageHandlerUpdate($messageId, $senderId, $recipientId, $messageText, &$connectedUsers, &$connectionUserMap)
// {
//     try {
//         $conn = getPDO();
//         $sql = "UPDATE `messages` SET message_text = :message_text WHERE id = :message_id AND sender_id = :sender_id AND recipient_id = :recipient_id";
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':message_text', $messageText, PDO::PARAM_STR);
//         $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
//         $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
//         $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
//         $stmt->execute();

//         $messages = getMessagesByRecipient($senderId, $recipientId);
//         $users = getAllUsers();

//         $responseData = [
//             'message_id' => $messageId,
//             'message_text' => $messageText,
//             'messages' => $messages,
//             'users' => $users
//         ];

//         foreach ($connectedUsers as $userConnection) {
//             if (isset($connectionUserMap[$userConnection->id])) {
//                 list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
//                 if (($senderIdMap == $senderId && $recipientIdMap == $recipientId) || ($senderIdMap == $recipientId && $recipientIdMap == $senderId)) {
//                     $userConnection->send(json_encode(['update' => $responseData]));
//                 }
//             }
//         }
//     } catch (PDOException $e) {
//         echo "Error updating message: " . $e->getMessage();
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }

// function onMessageHandlerSend($senderId, $recipientId, $messageText, &$connectedUsers, &$connectionUserMap, $connection)
// {
//     try {
//         $conn = getPDO();

//         saveMessage($senderId, $recipientId, $messageText);

//         $messages = getMessagesByRecipient($senderId, $recipientId);
//         $users = getAllUsers();

//         $responseData = [
//             'messages' => $messages,
//             'users' => $users
//         ];

//         foreach ($connectedUsers as $userConnection) {
//             if (isset($connectionUserMap[$userConnection->id])) {
//                 list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
//                 if (($senderId == $senderIdMap && $recipientId == $recipientIdMap) || ($senderId == $recipientIdMap && $recipientId == $senderIdMap)) {
//                     $userConnection->send(json_encode(['success' => $responseData]));
//                 }
//             }
//         }
//     } catch (PDOException $e) {
//         echo "Error sending message: " . $e->getMessage();
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }

// function onCloseHandler($connection, &$connectedUsers, &$connectionUserMap)
// {
//     if (isset($connectionUserMap[$connection->id])) {
//         list($senderId, $recipientId) = $connectionUserMap[$connection->id];

//         unset($connectedUsers[$connection->id]);
//         unset($connectionUserMap[$connection->id]);

//         echo "Connection closed for sender $senderId and recipient $recipientId\n";
//     } else {
//         echo "Connection closed\n";
//     }
// }








function onConnectHandler($connection, &$connectedUsers, &$connectionUserMap)
{
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
}

function onMessageHandlerDelete($messageId, $senderId, $recipientId, &$connectedUsers, &$connectionUserMap)
{
    try {
        $conn = getPDO();

        $sql = "DELETE FROM `messages` WHERE id = :message_id AND sender_id = :sender_id AND recipient_id = :recipient_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
        $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
        $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
        $stmt->execute();

        $messages = getMessagesByRecipient($senderId, $recipientId);
        $users = getAllUsers();

        $responseData = [
            'messages' => $messages,
            'users' => $users
        ];

        echo "Connected Users: " . json_encode(array_keys($connectedUsers)) . "\n";
        echo "Sending delete message to users...\n";

        foreach ($connectedUsers as $userConnection) {
            if (isset($connectionUserMap[$userConnection->id])) {
                list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
                if ($senderId == $senderIdMap && $recipientId == $recipientIdMap) {
                    echo "Sending delete message to connection ID: " . $userConnection->id . "\n";
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
}

function onMessageHandlerAddImage($imageUrl, $senderId, $recipientId, &$connectedUsers, &$connectionUserMap)
{
    try {
        $conn = getPDO();

        saveMessageWithImage($imageUrl, $senderId, $recipientId);

        $messages = getMessagesByRecipient($senderId, $recipientId);
        $users = getAllUsers();

        $responseData = [
            'image_url' => $imageUrl,
            'messages' => $messages,
            'users' => $users
        ];

        foreach ($connectedUsers as $userConnection) {
            if (isset($connectionUserMap[$userConnection->id])) {
                list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
                if ($senderId == $senderIdMap && $recipientId == $recipientIdMap) {
                    $userConnection->send(json_encode(['add_image' => $responseData]));
                }
            }
        }
    } catch (PDOException $e) {
        echo "Error adding image: " . $e->getMessage();
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function onMessageHandlerUpdate($messageId, $senderId, $recipientId, $messageText, &$connectedUsers, &$connectionUserMap)
{
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
            'message_id' => $messageId,
            'message_text' => $messageText,
            'messages' => $messages,
            'users' => $users
        ];

        foreach ($connectedUsers as $userConnection) {
            if (isset($connectionUserMap[$userConnection->id])) {
                list($senderIdMap, $recipientIdMap) = $connectionUserMap[$userConnection->id];
                if (($senderIdMap == $senderId && $recipientIdMap == $recipientId) || ($senderIdMap == $recipientId && $recipientIdMap == $senderId)) {
                    $userConnection->send(json_encode(['update' => $responseData]));
                }
            }
        }
    } catch (PDOException $e) {
        echo "Error updating message: " . $e->getMessage();
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function onMessageHandlerSend($senderId, $recipientId, $messageText, &$connectedUsers, &$connectionUserMap, $connection)
{
    try {
        $conn = getPDO();

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
    } catch (PDOException $e) {
        echo "Error sending message: " . $e->getMessage();
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function onCloseHandler($connection, &$connectedUsers, &$connectionUserMap)
{
    if (isset($connectionUserMap[$connection->id])) {
        list($senderId, $recipientId) = $connectionUserMap[$connection->id];

        unset($connectedUsers[$connection->id]);
        unset($connectionUserMap[$connection->id]);

        echo "Connection closed for sender $senderId and recipient $recipientId\n";
    } else {
        echo "Connection closed\n";
    }
}
