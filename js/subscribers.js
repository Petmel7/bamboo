
async function subscribe(targetUserId) {
    try {
        const response = await fetch('hack/subscription/add_subscription.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                subscriber_id: loggedInUserId,
                target_user_id: targetUserId
            }),
        });

        if (response.ok) {
            console.log('responseSubscribe', response);
            updateButtons(true);
        } else {
            alert('Failed to subscribe');
        }
    } catch (error) {
        console.log(error);
        alert('Error in fetch request');
    }
}

async function unsubscribe(targetUserId) {
    try {
        const response = await fetch('hack/subscription/remove_subscription.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                subscriber_id: loggedInUserId,
                target_user_id: targetUserId
            }),
        });

        if (response.ok) {
            console.log('responseUnsubscribe', response);
            updateButtons(false);
        } else {
            alert('Failed to unsubscribe');
        }
    } catch (error) {
        console.log(error);
        alert('Error in fetch request');
    }
}

async function getCurrentUserSubscriptions(targetUserId) {
    try {
        const response = await fetch('hack/subscription/get_subscriptions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: loggedInUserId,
                target_user_id: targetUserId
            }),
        });

        if (response.ok) {
            const subscriptions = await response.json();

            const subscribedUserIds = subscriptions.map(user => user.id);

            const isSubscribed = subscribedUserIds.includes(targetUserId);

            updateButtons(isSubscribed);

            return subscribedUserIds || [];
        } else {
            console.error('Failed to fetch user subscriptions');
            return [];
        }
    } catch (error) {
        console.error('Error in fetch request', error);
        return [];
    }
}

function updateButtons(isSubscribed) {
    const subscribeButton = document.getElementById('subscribeButton');
    const unsubscribeButton = document.getElementById('unsubscribeButton');

    isSubscribed ? (
        subscribeButton.style.display = 'none',
        unsubscribeButton.style.display = 'block'
    ) : (
        unsubscribeButton.style.display = 'none',
        subscribeButton.style.display = 'block'
    );

} 
