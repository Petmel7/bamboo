<?php

if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'signup':
            require './server/src/signup-form.php';
            break;
        case 'home':
            require './server/src/home.php';
            break;
        case 'user':
            if (isset($_GET['username'])) {
                $username = $_GET['username'];

                require './server/src/user-page.php';
            }
            break;
        case 'friends-list':
        case 'my-friends-list':
        case 'my-subscribers-list':
        case 'his-friends-list':
        case 'his-subscribers-list':
        case 'user-page-messages':
        case 'message-page':

            require "./server/src/{$_GET['page']}.php";
            break;
        default:
            require './server/src/signin-form.php';
            break;
    }
} else {
    require './server/src/signin-form.php';
}


// if (isset($_GET['page'])) {
//     if ($_GET['page'] === 'signup') {
//         require './hack/signup-form.php';

//     } elseif ($_GET['page'] === 'home') {
//         require './hack/home.php';

//     } elseif ($_GET['page'] === 'user' && isset($_GET['username'])) {
//         $username = $_GET['username'];
//         require './hack/user-page.php';

//     } elseif ($_GET['page'] === 'friends-list') {
//         require './hack/friends-list.php';

//     } elseif ($_GET['page'] === 'my-friends-list') {
//         require './hack/my-friends-list.php';

//     } elseif ($_GET['page'] === 'my-subscribers-list') {
//         require './hack/my-subscribers-list.php';

//     } elseif ($_GET['page'] === 'his-friends-list') {
//         require './hack/his-friends-list.php';

//     } elseif ($_GET['page'] === 'his-subscribers-list') {
//         require './hack/his-subscribers-list.php';

//     } elseif ($_GET['page'] === 'user-page-messages') {
//         require './hack/user-page-messages.php';

//     } elseif ($_GET['page'] === 'message-page') {
//         require './hack/message-page.php';

//     } else {
//         require './hack/signin-form.php';
//     }
// } else {
//     require './hack/signin-form.php';
// }
