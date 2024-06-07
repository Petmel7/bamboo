async function signinSubmitForm() {
    const form = document.getElementById("signinForm");
    const formData = new FormData(form);
    try {
        const response = await fetch('php/signin.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            redirectToHome();
        } else {
            alert("Authentication failed");
            addErrorClassToRequiredInputs();
        }
    } catch (error) {
        console.error("Помилка при аутентифікації", error);
    }
}