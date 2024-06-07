<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

checkGuest();
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <h1 class="bamboo">Bamboo</h1>
    <form id="signupForm" class="form" enctype="multipart/form-data">
        <p class="form-title">Signup</p>

        <label class="form-label" for="name">Name
            <input class="form-input req_" type="text" placeholder="Macaulay Culkin" name="name" aria-invalid="true">
        </label>

        <label class="form-label" for="">E-mail
            <input id="email" class="form-input req_" type="text" placeholder="MacaulayCulkin@gmail.com" name="email">
        </label>

        <label class="form-label" for="">Password
            <div class="custom-placeholder">
                <input class="form-input password req_" type="password" placeholder=" " name="password">
                <span class="placeholder-text">************</span>
            </div>
        </label>

        <label class="form-label" for="">Repeat password
            <div class="custom-placeholder">
                <input class="form-input password req_" type="password" placeholder=" " name="repeat-password">
                <span class="placeholder-text">************</span>
            </div>
        </label>

        <button type="button" onclick="signupSubmitForm()">Continue</button>

        <p class="form-account">I already have an <a href="index.php?page=signin">account</a></p>
    </form>

    <script src="js/forwarding.js"></script>
    <script src="js/validation.js"></script>
    <script src="js/signupSubmitForm.js"></script>

</body>

</html>