
const socket = new WebSocket(`ws://localhost:2346/?sender_id=${loggedInUserId}&recipient_id=${recipientId}`);

socket.onmessage = async function (event) {
    const messagesData = JSON.parse(event.data);
    console.log('Received message data:', messagesData);

    switch (true) {
        case messagesData.success !== undefined:
            const sendData = messagesData.success;

            if (Array.isArray(sendData.messages) && Array.isArray(sendData.users)) {
                const messages = sendData.messages;
                const users = sendData.users;

                await displayMessages(messages, users);

            } else {
                console.error('Invalid success data format', sendData);
            }
            break;

        case messagesData.delete !== undefined:
            const deleteData = messagesData.delete;

            if (Array.isArray(deleteData.messages) && Array.isArray(deleteData.users)) {
                const messages = deleteData.messages;
                const users = deleteData.users;

                console.log('Displaying messages for delete case');
                console.log('messages:', messages);
                console.log('users:', users);

                await displayMessages(messages, users);

            } else {
                console.error('Invalid delete data format', deleteData);
            }
            break;

        case messagesData.update !== undefined:
            const updateData = {
                messages: messagesData.update.messages,
                users: messagesData.update.users
            };

            if (Array.isArray(updateData.messages) && Array.isArray(updateData.users)) {
                const messages = updateData.messages;
                const users = updateData.users;

                await displayMessages(messages, users);

            } else {
                console.error('Invalid update data format', updateData);
            }
            break;

        case messagesData.add_image !== undefined:
            const imageData = messagesData.add_image;
            console.log('imageData', imageData);

            if (Array.isArray(imageData.messages) && Array.isArray(imageData.users)) {
                const messages = imageData.messages;
                const users = imageData.users;

                console.log('messages and users', messages, users);

                await displayMessages(messages, users);

            } else {
                console.error('Invalid add_image data format', imageData);
            }
            break;

        default:
            console.error('Unknown message format', messagesData);
            break;
    }
};

socket.onerror = function (error) {
    console.error('WebSocket error:', error);
};

socket.onclose = function () {
    console.log('WebSocket connection closed');
};

//sendMessages========================================================
async function sendMessages(recipientId, event) {
    event.preventDefault();
    const messageTextarea = document.getElementById('messageTextarea');
    const messageText = messageTextarea.value.trim();
    if (messageText === '') {
        alert('Please enter the text of the message.');
        return;
    }
    const message = {
        sender_id: loggedInUserId,
        recipient_id: parseInt(recipientId, 10),
        message_text: messageText
    };

    console.log('sendMessages->message', message);

    if (socket.readyState === WebSocket.OPEN) {
        socket.send(JSON.stringify(message));
    } else {
        alert('WebSocket connection is not open.');
    }
    messageTextarea.value = '';
}

//deleteMessage========================================================
async function deleteMessage(messageId, event) {
    event.preventDefault();
    try {
        const deleteMessage = {
            action: 'delete',
            sender_id: loggedInUserId,
            recipient_id: recipientId,
            message_id: messageId
        }

        console.log('Sending delete message to WebSocket:', deleteMessage);
        socket.send(JSON.stringify(deleteMessage));

    } catch (error) {
        console.error('Error:', error);
    }
}

//addImages========================================================
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("imagesButton").addEventListener("click", function () {
        addImages();
    });
});

async function addImages() {
    const imagesForm = document.getElementById('imagesForm');
    const formData = new FormData(imagesForm);

    formData.append('sender_id', loggedInUserId);
    formData.append('recipient_id', recipientId);

    try {
        const response = await fetch('server/src/messages/add_images.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.error) {
            console.log(result.error);
        } else {
            const addImages = {
                action: 'add_image',
                image_url: result.image_url,
                sender_id: loggedInUserId,
                recipient_id: recipientId
            };

            console.log('Sending add image to WebSocket:', addImages);
            socket.send(JSON.stringify(addImages));
        }

    } catch (error) {
        console.log("error", error);
    }
}

//updateMessages========================================================
function handleImageChange() {
    const fileInput = document.getElementById('addImages');
    const imagesButton = document.getElementById('imagesButton');
    const messageButton = document.getElementById('messageButton');

    if (fileInput.files.length > 0) {
        imagesButton.style.display = 'block';
        messageButton.style.display = 'none';
    } else {
        imagesButton.style.display = 'none';
        messageButton.style.display = 'block';
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('messageTextarea').addEventListener('click', function () {
        handleMesageChange()
    })
})

function handleMesageChange() {
    const imagesButton = document.getElementById('imagesButton');
    const messageButton = document.getElementById('messageButton');
    const messageTextarea = document.getElementById('messageTextarea');

    if (messageTextarea.value.trim() !== '') {
        messageButton.style.display = 'none';
        imagesButton.style.display = 'block';
    } else {
        messageButton.style.display = 'block';
        imagesButton.style.display = 'none';
    }
}

async function openUpdateFormAndCloseModal(messageId) {
    try {
        const response = await fetch('server/src/messages/get_update_messages.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: messageId }),
        });

        if (response.ok) {
            const data = await response.json();
            const messageText = data.message_text.message_text;

            initialMessageText = messageText;

            openUpdateForm(messageId, messageText);
            closeModal();

            const updateButton = document.getElementById('updateButton');
            updateButton.innerHTML = `
                <button id="disabledButton" class="message-button" type="..." disabled onclick="updateMessages(${messageId}, event)">Update</button>
            `;
        } else {
            console.error('Failed to get data from server');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

const updateTextarea = document.getElementById('updateTextarea');
updateTextarea.addEventListener('input', updateUpdateButtonState);

function updateUpdateButtonState() {
    const disabledButton = document.getElementById('disabledButton');
    const updatedMessageText = updateTextarea.value.trim();
    const isMessageChanged = updatedMessageText !== initialMessageText;

    isMessageChanged ? disabledButton.removeAttribute('disabled') : disabledButton.setAttribute('disabled', true);
}

function openUpdateForm(messageId, messageText) {
    const openEditForm = document.getElementById("openEditForm");
    const hideForm = document.getElementById("hideForm");
    const updateTextarea = document.getElementById('updateTextarea');

    openEditForm.style.display = 'block';
    hideForm.style.display = 'none';

    updateTextarea.value = messageText;
}

function closeUpdateForm() {
    const openEditForm = document.getElementById("openEditForm");
    const hideForm = document.getElementById("hideForm");

    openEditForm.style.display = 'none';
    hideForm.style.display = 'block';
}

async function updateMessages(messageId, event) {
    event.preventDefault();
    try {
        const updateTextarea = document.getElementById('updateTextarea');
        const updateMessageText = updateTextarea.value.trim();

        const updateMessages = {
            action: 'update',
            message_id: messageId,
            sender_id: loggedInUserId,
            recipient_id: recipientId,
            message_text: updateMessageText
        };

        console.log('Sending update to WebSocket:', updateMessages);

        socket.send(JSON.stringify(updateMessages));

        closeUpdateForm();

    } catch (error) {
        console.log("Error:", error);
    }
}

//displayMessages====================================================
async function displayMessages(messages, users) {
    console.log('displayMessages called');
    console.log('messages:', messages);
    console.log('users:', users);

    if (!Array.isArray(messages) || !Array.isArray(users)) {
        console.error('Invalid messages or users format', { messages, users });
        return;
    }

    const messagesContainer = document.getElementById('messagesContainer');

    let lastSenderId = null;
    let lastMessageTime = null;

    const messagesHTML = messages.map(message => {
        const senderId = message.sender_id;
        const isSender = senderId === loggedInUserId;
        const formattedTime = formatTime(message.sent_at);

        const { messageClass, displayStyle } = getMessageStyles(isSender);

        const currentTime = new Date(message.timestamp).getTime();
        const showAvatar = senderId !== lastSenderId || (currentTime - lastMessageTime > 60000);

        const { avatarDisplayStyle, marginLeftStyle, dynamicBorderStyle } = calculateStyles(showAvatar, isSender, displayStyle);

        const sender = users.find(user => user.id === senderId);

        if (!sender) {
            console.error('Sender not found:', senderId);
            return '';
        }

        if (showAvatar) {
            lastSenderId = senderId;
            lastMessageTime = currentTime;
        }

        const messageSentAtClass = message.message_text && message.message_text.length < 15 ? 'message-heder--sent_at' : 'message-header';

        const {
            backgroundSenderClass,
            backgroundClassMessages,
            recipientWhiteText,
            messageDateStyleDisplay,
            modalThemeStyle,
            mesageButtonStyle
        } = calculateStylesLocalStorage(isSender);

        const {
            encodedUsername,
            messageContent,
            backgroundImage,
            backgroundImageSize,
            backgroundSizeCover,
            imageButtonStyle,
            avatarSrc
        } = processMessageData(sender, message, recipientWhiteText);

        return `
        <li class="${messageClass}">
            <div class="messages">
                <a href='index.php?page=user&username=${encodedUsername}'>
                    <img style="display: ${avatarDisplayStyle}" id="messageImg" class="message-img" src='${avatarSrc}' alt='${sender.name}'>
                </a>
                <div class="search-friend--add message-body ${backgroundSenderClass} ${backgroundClassMessages}" style="margin-left: ${marginLeftStyle}; border-radius: ${dynamicBorderStyle}; ${backgroundImage}; ${backgroundImageSize}; ${backgroundSizeCover}">

                    <div class="${messageSentAtClass}">
                        ${messageContent}
                        <span class="${messageDateStyleDisplay} ${imageButtonStyle}">${formattedTime}</span>
                    </div>
                    <button class="message-delete--button delete-button ${mesageButtonStyle} ${imageButtonStyle}" onclick="openModalDelete(${message.id}, ${isSender})">&#8942;</button>
                    <div id="myModal" class="modal">
                        <div class="modal-content ${modalThemeStyle}" id="modalContent"></div>
                    </div>

                </div>
            </div>
        </li>`;
    }).join('');
    messagesContainer.innerHTML = messagesHTML;
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}
