let timer;

async function hisSearchFriends(hisUserId) {
    clearTimeout(timer);

    timer = setTimeout(async () => {
        const { searchInput, friendsContainer } = generateGetElementById();

        try {
            if (searchInput.trim() === '') {

                friendsContainer.innerHTML = '';
                await hisGetFriendsData(hisUserId);
                return;
            }

            const response = await fetch('server/src/search/search-his-friends.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: searchInput,
                    user_id: hisUserId
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
