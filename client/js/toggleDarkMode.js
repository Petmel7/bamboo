
function changeInputStyle(isDarkModeEnabled) {
    const searchFriendAdd = document.querySelector('.search-friend--add');
    if (searchFriendAdd) {
        isDarkModeEnabled ? (
            searchFriendAdd.classList.add('background-input')
        ) : (
            searchFriendAdd.classList.remove('background-input')
        );
    }
}

function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle('dark-mode');

    const isDarkModeEnabled = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkModeEnabled);

    const darkModeButton = document.getElementById('darkModeIcon');
    const whiteModeButton = document.getElementById('whiteModeIcon');

    darkModeButton.style.display = 'none';
    whiteModeButton.style.display = 'none';

    if (isDarkModeEnabled) {
        whiteModeButton.style.display = 'block';
        document.querySelectorAll('.change-color--title').forEach(function (title) {
            title.classList.add('white-text');
        });
    } else {
        darkModeButton.style.display = 'block';
        document.querySelectorAll('.change-color--title').forEach(function (title) {
            title.classList.remove('white-text');
        });
    }

    changeInputStyle(isDarkModeEnabled);
}

document.addEventListener('DOMContentLoaded', function () {
    const isDarkModeEnabled = localStorage.getItem('darkMode') === 'true';
    const darkModeButton = document.getElementById('darkModeIcon');
    const whiteModeButton = document.getElementById('whiteModeIcon');

    darkModeButton.style.display = 'none';
    whiteModeButton.style.display = 'none';

    if (isDarkModeEnabled) {
        document.body.classList.add('dark-mode');
        whiteModeButton.style.display = 'block';
        document.querySelectorAll('.change-color--title').forEach(function (title) {
            title.classList.add('white-text');
        });
    } else {
        darkModeButton.style.display = 'block';
    }

    changeInputStyle(isDarkModeEnabled);
});

function toggleDarkModeAndRefresh() {
    toggleDarkMode();
    location.reload();
}

