
let currentPage = 1;
const limit = 10;

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayFriends(currentPage, limit);
    }
}

function nextPage() {
    currentPage++;
    displayFriends(currentPage, limit);
}

async function displayFriends(page, limit) {
    console.log('page, limit', page, limit);
    try {
        const response = await fetch(`server/src/actions/friends.php?page=${page}&limit=${limit}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            const friends = await response.json();
            console.log('friends', friends);
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

displayFriends(currentPage, limit);
