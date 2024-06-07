async function hisGetSubscribersData(hisUserId) {
    try {
        const response = await fetch(`hack/subscription/get_his_subscribers.php?user_id=${hisUserId}`);
        console.log('hisUserId', hisUserId);

        if (response.ok) {
            const friends = await response.json();

            generateFriendListItem(friends);

        } else {
            console.error('Failed to fetch user subscriptions');
        }
    } catch (error) {
        console.error('Error in fetch request', error);
    }
}