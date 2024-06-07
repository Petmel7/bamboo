
const friendsForm = document.getElementById('friendsForm');

async function displayFriends() {
    try {
        const response = await fetch('hack/actions/friends.php', {
            method: 'POST'
        });

        if (response.ok) {
            const friendsText = await response.text();

            const friends = JSON.parse(friendsText);

            generateFriendListItem(friends);
            
        } else {
            throw new Error('Network response was not ok.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Помилка');
    }
}

displayFriends();
