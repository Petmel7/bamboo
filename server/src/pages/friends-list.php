<?php
require_once __DIR__ . '../../actions/helpers.php';
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '../../components/head.php'; ?>

<body>
    <header class="user-header">
        <h1 class="user-name">Friends list</h1>

        <?php include_once __DIR__ . '../../components/html.php'; ?>
    </header>

    <section class="container">
        <form class="search-friend" id="searchForm">
            <input class="search-friend--add search-friend__input" type="text" id="searchInput" name="searchInput" placeholder="Search" required oninput="searchFriends()">
            <button class="my-friends__button" type="button" onclick="redirectToMyFriends()">My friends</button>
        </form>
    </section>

    <ul class="friend-list" id="friendsDataContainer"></ul>

    <div id="paginationContainer">
        <button id="prevPage">Previous</button>
        <button id="nextPage">Next</button>
    </div>


    <!-- <div>
        <button id="prevPage">Previous</button>
        <button id="nextPage">Next</button>
    </div> -->

    <!-- <div>
        <button id="prevPage" onclick="prevPage()">Previous</button>
        <button id="nextPage" onclick="nextPage()">Next</button>
    </div> -->


    <script src="client/js/toggleDarkMode.js"></script>
    <script src="client/api/displayFriends.js"></script>
    <script src="client/js/generateFriendListItem.js"></script>
    <script src="client/api/searchFriends.js"></script>
    <script src="client/js/forwarding.js"></script>
    <script src="client/js/generateSearchListItem.js"></script>
    <script src="client/js/generateGetElementById.js"></script>
</body>

</html>