<!DOCTYPE html>
<html lang="en">

<head>
    <title>LOGIN - SprintBoard</title>
    <link rel="shortcut icon" href="icon.jpg" type="image/jpg">
    <link rel="shortcut icon" href="icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" />
    <link rel="stylesheet" href="login.css">
</head>
<?php
require 'connect.php';

if (isset($_SESSION['login_id'])) {
    header('Location: home.php');
    exit;
}

require './google_login/vendor/autoload.php';

// Creating new google client instance
$client = new Google_Client();

// Enter your Client ID
$client->setClientId('960963656376-180pmm572qc6lkfrpaaojdllrcmnne7c.apps.googleusercontent.com');
// Enter your Client Secrect
$client->setClientSecret('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');
// Enter the Redirect URL
$client->setRedirectUri('http://localhost/WEBDEV/SprintBoard/login.php');

// Adding those scopes which we want to get (email & profile Information)
$client->addScope("email");
$client->addScope("profile");


if (isset($_GET['code'])) :

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token["error"])) {

        $client->setAccessToken($token['access_token']);

        // getting profile information
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        // Storing data into database
        $id = mysqli_real_escape_string($db_connection, $google_account_info->id);
        $full_name = mysqli_real_escape_string($db_connection, trim($google_account_info->name));
        $email = mysqli_real_escape_string($db_connection, $google_account_info->email);
        $profile_pic = mysqli_real_escape_string($db_connection, $google_account_info->picture);

        // checking user already exists or not
        $get_user = mysqli_query($db_connection, "SELECT `google_id` FROM `users` WHERE `google_id`='$id'");
        if (mysqli_num_rows($get_user) > 0) {

            $_SESSION['login_id'] = $id;
            header('Location: index.php');
            exit;
        } else {

            // if user not exists we will insert the user
            $insert = mysqli_query($db_connection, "INSERT INTO `users`(`google_id`,`name`,`email`,`profile_image`) VALUES('$id','$full_name','$email','$profile_pic')");

            if ($insert) {
                $_SESSION['login_id'] = $id;
                header('Location: index.php');
                exit;
            } else {
                echo "Sign up failed!(Something went wrong).";
            }
        }
    } else {
        header('Location: login.php');
        exit;
    }

else :
    // Google Login Url = $client->createAuthUrl(); 
?>
    <html>

    <body class="img js-fullheight" style="background-image: url(mybg.jpg); backdrop-filter: blur(0.05rem);">


        <div class="login">
            <header>
                <center>
                    <h2 style="color: #fff; padding-bottom: 1rem;">SprintBoard</h2>
                </center>
            </header> <br>
            <a style="text-decoration: none;" class="login-btn submit" href="<?php echo $client->createAuthUrl(); ?>"><i class="fa fa-google social-icon" aria-hidden="true"></i>&nbsp;<span style="color: #111;"><b>Sign in with Google</b></span></a>
        </div>
    </body>

    </html>

<?php endif; ?>