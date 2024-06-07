async function comfirmSubmit() {
    try {
        const response = await fetch('hack/actions/delete-photo.php', {
            method: 'POST'
        })
        if (response.ok) {
            closeModal();
            redirectToHome();
        } else {
            alert('There was an error deleting the photo')
        }
    } catch (error) {
        console.log('error', error);
    }
}
