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
        <h1 class="user-name">His friends</h1>

        <?php include_once __DIR__ . '/../components/html.php'; ?>
    </header>

    <section class="container">
        <form class="search-friend" id="searchForm">
            <input class="search-friend--add search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="hisSearchFriends(<?php echo $userData['id']; ?>)">
            <button class="subscription-buttons" type="button" onclick="redirectionHisSubscribers('<?php echo $userData['name']; ?>')">His subscribers</button>
        </form>
    </section>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="js/toggleDarkMode.js"></script>
    <script src="js/forwarding.js"></script>
    <script src="js/hisGetFriendsData.js"></script>
    <script src="js/generateFriendListItem.js"></script>
    <script>
        hisGetFriendsData(<?php echo $userData['id']; ?>);
    </script>
    <script src="js/hisSearchFriends.js"></script>
    <script src="js/generateGetElementById.js"></script>
    <script src="js/generateSearchListItem.js"></script>

</body>

</html>