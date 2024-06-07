<?php
require_once __DIR__ . '/actions/helpers.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $userData = getUserDataByUsername($username);
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>

    <header class="user-header">
        <h1 class="user-name">His subscribers</h1>

        <?php include_once __DIR__ . '/../components/html.php'; ?>
    </header>

    <section class="container">
        <form class="search-friend" id="searchForm">
            <input class="search-friend--add search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="hisSearchSubscribers(<?php echo $userData['id']; ?>)">
        </form>
    </section>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="js/toggleDarkMode.js"></script>
    <script src="js/hisGetSubscribersData.js"></script>
    <script src="js/generateFriendListItem.js"></script>
    <script>
        hisGetSubscribersData(<?php echo $userData['id']; ?>);
    </script>
    <script src="js/search-friends.js"></script>
    <script src="js/hisSearchSubscribers.js"></script>
    <script src="js/generateGetElementById.js"></script>
    <script src="js/generateSearchListItem.js"></script>

</body>

</html>