
let currentPage = 1;
const limit = 10;

document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        displayFriends(currentPage, limit);
    }
});

document.getElementById('nextPage').addEventListener('click', () => {
    currentPage++;
    displayFriends(currentPage, limit);
});

async function displayFriends(page = 1, limit = 10) {
    try {
        const response = await fetch('server/src/actions/friends.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ page, limit })
        });

        if (response.ok) {
            const friends = await response.json();
            if (Array.isArray(friends)) {
                generateFriendListItem(friends);
            } else {
                console.error('Received data is not an array:', friends);
                alert('Помилка отримання даних');
            }
        } else {
            throw new Error('Network response was not ok.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Помилка');
    }
}

function generateFriendListItem(friends) {
    const friendsContainer = document.getElementById('friendsDataContainer');
    friendsContainer.innerHTML = '';

    const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
    const textColorClass = isDarkModeEnabled ? 'white-text' : '';

    const friendsHTML = friends.map(friend => `
        <li class="friend-list__li">
            <a href='index.php?page=user&username=${encodeURIComponent(friend.name)}'>
                <img class="friend-list__img" src='server/src/${friend.avatar}' alt='${friend.name}'>
                <p class="change-color--title friend-list__name ${textColorClass}">${friend.name}</p>
            </a>
        </li>
    `).join('');

    friendsContainer.insertAdjacentHTML('beforeend', friendsHTML);
}

// Initial load
displayFriends(currentPage, limit);



// function prevPage() {
//     document.getElementById('prevPage');

//     currentPage++;
//     displayFriends(currentPage);
// }

// function nextPage() {
//     document.getElementById('nextPage');

//     if (currentPage > 1) {
//         currentPage--;
//         displayFriends(currentPage);
//     }
// }