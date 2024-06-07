<?php
require_once __DIR__ . '/actions/helpers.php';

$currentUserId = currentUserId();

echo "<script>let currentUserId = " . json_encode($currentUserId) . ";</script>";

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <header class="user-header">
        <h1 class="user-name">Chats</h1>
        <div class="icon-block">
            <span class="mode-icon material-symbols-outlined" id="whiteModeIcon" onclick="toggleDarkModeAndRefresh()">wb_sunny</span>
            <span class="mode-icon--dark material-symbols-outlined" id="darkModeIcon" onclick="toggleDarkModeAndRefresh()">brightness_3</span>
        </div>
    </header>

    <section class="container">
        <ul class="messages-container" id="messagesContainer"></ul>
        <div class="no-messages" id="noMessageContainer"></div>
    </section>

    <script src="js/modal.js"></script>
    <script src="utils/utilities.js"></script>
    <script src="js/deleteUserAllChat.js"></script>
    <script src="js/getMessageForAuthorizedUser.js"></script>
    <script src="utils/style.js"></script>
    <script src="js/toggleDarkMode.js"></script>

</body>

</html>