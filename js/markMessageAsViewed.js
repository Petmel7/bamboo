async function markMessageAsViewed(recipientId, loggedInUserId) {
    try {
        const response = await fetch('hack/actions/mark-message-as-viewed.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                sender_id: recipientId,
                recipient_id: loggedInUserId
            })
        });

    } catch (error) {
        console.error('Error in fetch request', error);
    }
}

markMessageAsViewed(recipientId, loggedInUserId);