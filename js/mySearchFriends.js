let timer;

async function mySearchFriends() {
    clearTimeout(timer);

    timer = setTimeout(async () => {
        const { searchInput, friendsContainer } = generateGetElementById();

        try {
            if (searchInput.trim() === '') {

                friendsContainer.innerHTML = '';
                await getFriendsData(loggedInUserId);
                return;
            }

            const response = await fetch('hack/search/search-my-friends.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: searchInput
                }),
            });

            if (response.ok) {
                const friends = await response.json();
                friendsContainer.innerHTML = '';

                generateSearchListItem(friends);

            } else {
                throw new Error('Network response was not ok.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Помилка');
        }
    }, 300);
};

