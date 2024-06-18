<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$loggedInUsername = getLoggedInUsername();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

$friends = handleGetRequest($loggedInUsername, $limit, $offset);

header('Content-Type: application/json');
echo json_encode($friends);
