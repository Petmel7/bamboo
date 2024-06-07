async function signupSubmitForm() {
    const form = document.getElementById("signupForm");
    const formData = new FormData(form);
    const emailInput = formData.get('email');
    const password = formData.get('password');
    const repeatPassword = formData.get('repeat-password');

    if (!validateEmail(emailInput)) {
        const input = document.getElementById("email");
        alert("Email format is incorrect");
        addRedBorderToInputEmail(input);
        return;
    }

    if (password !== repeatPassword) {
        const passwordInputs = document.querySelectorAll('.password');
        alert("Passwords do not match");
        addRedBorderToInputPassword(passwordInputs);
        return;
    }

    try {
        const response = await fetch('php/signup.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            redirectToSignin();
        } else {
            alert("Registration failed");
            addErrorClassToRequiredInputs();
        }
    } catch (error) {
        console.error("Error during registration", error);
    }
}