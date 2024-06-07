
function generateFriendListItem(friends) {
    const friendsContainer = document.getElementById('friendsDataContainer');
    friendsContainer.innerHTML = '';

    const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
    const textColorClass = isDarkModeEnabled ? 'white-text' : '';

    const friendsHTML = friends.map(friend => `
        <li class="friend-list__li">
            <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
                <img class="friend-list__img" src='hack/${friend.avatar}' alt='${friend.name}'>
                <p class="change-color--title friend-list__name ${textColorClass}">${friend.name}</p>
            </a>
        </li>
    `).join('');

    friendsContainer.insertAdjacentHTML('beforeend', friendsHTML);
}
