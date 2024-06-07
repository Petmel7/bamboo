async function deleteUserAllChat(currentUserId, recipientId, event) {
    event.preventDefault();
    const clickedUserId = event.target.dataset.userid;
    try {
        const response = await fetch('hack/messages/delete-all-messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                sender_id: currentUserId,
                recipient_id: clickedUserId
            }),
        });

        if (response.ok) {
            const result = await response.json();

            refreshPage();
        } else {
            console.log("response error");
        }

    } catch (error) {
        console.error('Error:', error);
    }
}

function refreshPage() {
    window.location.reload();
}