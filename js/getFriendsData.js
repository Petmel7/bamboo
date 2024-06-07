async function getFriendsData(loggedInUserId) {
    try {
        const response = await fetch('hack/subscription/get_subscriptions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: loggedInUserId
            }),
        });

        if (response.ok) {
            const friends = await response.json();

            generateFriendListItem(friends);

            return friends || [];
        } else {
            console.error('Failed to fetch user subscriptions');
            return [];
        }
    } catch (error) {
        console.error('Error in fetch request', error);
        return [];
    }
}
getFriendsData(loggedInUserId);