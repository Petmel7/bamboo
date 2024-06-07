async function changePhoto() {
    const form = document.getElementById('photoForm');
    const formData = new FormData(form);
    try {
        const response = await fetch('src/actions/change-photo.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            const userAvatar = document.getElementById('userAvatar');
            userAvatar.src = 'src/' + result.avatar;

            redirectToHome();
        } else {
            alert("Failed to change photo");
        }
    } catch (error) {
        console.log("error", error);
    }
}

