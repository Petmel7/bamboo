<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

checkGuest();
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../components/head.php'; ?>

<body>
    <h1 class="bamboo">Bamboo</h1>

    <form id="signinForm" class="form">
        <p class="form-title">Signin</p>

        <label class="form-label" for="">E-mail
            <input class="form-input req_" type="text" placeholder="MacaulayCulkin@gmail.com" name="email">
        </label>

        <label class="form-label" for="">Password
            <div class="custom-placeholder">
                <input class="form-input req_" type="password" placeholder="" name="password">
                <span class="placeholder-text">************</span>
            </div>
        </label>

        <button type="button" onclick="signinSubmitForm()">Continue</button>

        <p class="form-account">I don't have an <a href="index.php?page=signup">account</a> yet</p>
    </form>

    <script src="js/forwarding.js"></script>
    <script src="js/signinSubmitForm.js"></script>
    <script src="js/validation.js"></script>

</body>

</html>