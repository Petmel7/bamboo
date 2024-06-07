function getMessageStyles(isSender) {
    const messageClass = isSender ? 'message-sender' : 'message-recipient';
    const displayStyle = isSender ? 'none' : 'block';

    return {
        messageClass,
        displayStyle
    }
}

function calculateStyles(showAvatar, isSender, displayStyle) {

    const avatarDisplayStyle = showAvatar ? displayStyle : 'none';
    const marginLeftStyle = showAvatar ? '0px' : '50px';
    const borderStyle = showAvatar ? '0 10px 10px 10px' : '10px';
    const borderStyleSender = showAvatar ? '10px 0 10px 10px' : '10px';
    const dynamicBorderStyle = isSender ? borderStyleSender : borderStyle;

    return {
        avatarDisplayStyle,
        marginLeftStyle,
        borderStyle,
        dynamicBorderStyle
    };
}

function calculateStylesLocalStorage(isSender) {
    const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';

    const textColorClass = isDarkModeEnabled ? 'white-text' : '';
    const backgroundSenderClass = isDarkModeEnabled ? 'background-sender--class' : '';
    const backgroundClassMessages = isDarkModeEnabled ? 'background-messages' : '';
    const recipientWhiteText = isDarkModeEnabled ? 'recipient-white-text' : '';
    const messageDateStyle = isDarkModeEnabled ? 'message-date--style' : 'message-date';
    const messageDateStyleDisplay = isSender ? 'message-date' : messageDateStyle;
    const modalThemeStyle = isDarkModeEnabled ? 'modal-content--dark' : 'modal-content--white';
    const mesageButtonStyle = (isSender && isDarkModeEnabled) ? '' : 'message-button--light';

    return {
        textColorClass,
        backgroundSenderClass,
        backgroundClassMessages,
        recipientWhiteText,
        messageDateStyleDisplay,
        modalThemeStyle,
        mesageButtonStyle
    }
}

function processMessageData(sender, message, recipientWhiteText) {
    const encodedUsername = encodeURIComponent(sender.name);
    const avatarSrc = sender ? `hack/${sender.avatar}` : '';
    const imageSrc = `hack/${message.image_url}`;
    const messageContent = message.image_url ? '' : `<p class="change-color--title message-content ${recipientWhiteText}">${message.message_text}</p>`;
    const backgroundImage = message.image_url ? `background-image: url(${imageSrc})` : '';
    const backgroundImageSize = message.image_url ? 'max-width: 60%; width: 100%; max-height: 60%; height: 200px;' : '';
    const backgroundSizeCover = message.image_url ? 'background-size: cover; background-position: center center;' : '';
    const imageButtonStyle = message.image_url ? 'image-button--style' : '';
    // const imageTimeStyle = message.image_url ? 'image-time--style' : '';

    return {
        encodedUsername,
        avatarSrc,
        imageSrc,
        messageContent,
        backgroundImage,
        backgroundImageSize,
        backgroundSizeCover,
        imageButtonStyle
        // imageTimeStyle
    };
}