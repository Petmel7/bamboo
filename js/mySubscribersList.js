async function mySubscribersList(loggedInUserId) {
    try {
        const response = await fetch('hack/subscription/get_subscribers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: loggedInUserId
            }),
        });

        console.log('loggedInUserId', loggedInUserId);

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
mySubscribersList(loggedInUserId);