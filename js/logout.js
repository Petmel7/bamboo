async function logout(event) {
    event.preventDefault();
    try {
        const response = await fetch('hack/actions/logout.php', {
            method: 'POST'
        });

        if (response.ok) {
            redirectToSignin();
        } else {
            console.error('Failed to logout:', response.statusText);
        }
    } catch (error) {
        console.error('Error during logout:', error);
    }
}