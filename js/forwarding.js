function forwarding() {
    window.location.href = 'index.php?page=friends-list';
}

function redirectToMyFriends() {
    window.location.href = 'index.php?page=my-friends-list';
}

function redirectToMySubscribers() {
    window.location.href = 'index.php?page=my-subscribers-list';
}

function redirectionHisFriends(username) {
    window.location.href = `index.php?page=his-friends-list&username=${encodeURIComponent(username)}`;
}

function redirectionHisSubscribers(username) {
    window.location.href = `index.php?page=his-subscribers-list&username=${encodeURIComponent(username)}`;
}

function redirectionToMessages(username) {
    window.location.href = `index.php?page=user-page-messages&username=${encodeURIComponent(username)}`;
}

function redirectToMyMtssages() {
    window.location.href = 'index.php?page=message-page';
}

function redirectToSignin() {
    window.location.href = 'index.php?page=signin';
}

function redirectToHome() {
    window.location.href = 'index.php?page=home';
}




