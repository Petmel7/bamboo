<?php
require_once __DIR__ . '../../actions/helpers.php';

$loggedInUserId = currentUserId();

echo "<script>let loggedInUserId = " . json_encode($loggedInUserId) . ";</script>";

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '../../components/head.php'; ?>

<body>
    <header class="user-header">
        <h1 class="user-name">My friends</h1>

        <?php include_once __DIR__ . '../../components/html.php'; ?>
    </header>
    <section class="container">
        <form class="search-friend" id="searchForm">
            <input class="search-friend--add search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="mySearchFriends()">
            <button class="my-friends__button" type="button" onclick="redirectToMySubscribers()">Subscribers</button>
        </form>
    </section>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <script src="client/js/toggleDarkMode.js"></script>
    <script src="client/api/getFriendsData.js"></script>
    <script src="client/js/forwarding.js"></script>
    <script src="client/js/generateFriendListItem.js"></script>
    <script src="client/api/mySearchFriends.js"></script>
    <script src="client/api/mySearchSubscribers.js"></script>
    <script src="client/js/generateSearchListItem.js"></script>
    <script src="client/js/generateGetElementById.js"></script>
</body>

</html>