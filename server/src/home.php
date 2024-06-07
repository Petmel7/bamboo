<?php
require_once __DIR__ . '/actions/helpers.php';

checkAuth();

$user = currentUser();

$currentUserId = currentUserId();

echo "<script>let currentUserId = " . json_encode($currentUserId) . ";</script>";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <header class="user-header">
        <h1 class="user-name">Home</h1>

        <?php include_once __DIR__ . '/../components/html.php'; ?>
    </header>

    <div class="account">
        <div class="account-block">
            <img id="userAvatar" class="account-img" src="hack/<?php echo $user['avatar']; ?>" width="200px" height="200px" alt="<?php echo $user['name']; ?>">
        </div>

        <button class="material-symbols-outlined photo-camera" onclick="openModal()">photo_camera</button>

        <div id="myModal" class="modal"></div>

        <h1 id="accountTitle" class="change-color--title account-title"><?php echo $user['name']; ?></h1>

        <div class="me-messages__block">
            <button onclick="redirectToMyMtssages()" class="me-messages">
                <p class="me-messages--text">Messages</p>
                <span class="material-symbols-outlined" style="font-size: 18px;">notifications</span>
                <span class="badge"></span>
            </button>
            <button class="friends" type="button" onclick="forwarding()">Search friends</button>
        </div>

        <button class="account-button material-symbols-outlined" onclick="logout(event)">logout</button>

    </div>

    <script src="js/toggleDarkMode.js"></script>
    <script src="js/comfirmSubmit.js"></script>
    <script src="js/logout.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/changePhoto.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/getNumberMessages.js"></script>

</body>

</html>