<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '../../services/UserService.php';

$loggedInUsername = getLoggedInUsername();

$friends = handleGetRequest($loggedInUsername);

header('Content-Type: application/json');
echo json_encode($friends);
