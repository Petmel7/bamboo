
function generateSearchListItem(friends) {
    const friendsContainer = document.getElementById('friendsDataContainer');

    if (Array.isArray(friends)) {
        friends.forEach(friend => {
            friendsContainer.innerHTML += `
                <li class="friend-list__li">
                    <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
                        <img class="friend-list__img" src='hack/${friend.avatar}' alt='${friend.name}'>
                        <p class="friend-list__name">${friend.name}</p>
                    </a>
                </li>`;
        });
    } else {
        console.error('Invalid friends data:', friends);
    }
}
