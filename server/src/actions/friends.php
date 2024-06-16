<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$loggedInUsername = getLoggedInUsername();

$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;

$friends = handleGetRequest($loggedInUsername, $page, $limit);

header('Content-Type: application/json');
echo json_encode($friends);
