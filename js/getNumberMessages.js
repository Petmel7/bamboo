
async function getNumberMessagesAll(currentUserId) {
    try {
        const response = await fetch('hack/actions/get-unviewed-messages-count.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                recipient_id: currentUserId
            }),
        });

        if (response.ok) {
            const messages = await response.json();
            const unviewedMessagesCount = messages.unviewed_messages_count;

            const badgeElement = document.querySelector('.badge');

            badgeElement.textContent = unviewedMessagesCount > 0 ? (unviewedMessagesCount > 10 ? '9+' : unviewedMessagesCount) : '';

            badgeElement.style.display = unviewedMessagesCount > 0 ? 'block' : 'none';

        } else {
            console.error('No messages found');
        }
    } catch (error) {
        console.error('Error in fetch request', error);
    }
}

getNumberMessagesAll(currentUserId);
