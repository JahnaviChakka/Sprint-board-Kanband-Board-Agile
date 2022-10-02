<?php
require 'connect.php';

if (!isset($_SESSION['login_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['login_id'];

$get_user = mysqli_query($db_connection, "SELECT * FROM `users` WHERE `google_id`='$userId'");

if (mysqli_num_rows($get_user) > 0) {
    $user = mysqli_fetch_assoc($get_user);
} else {
    header('Location: logout.php');
    exit;
}

//THE KEY FOR ENCRYPTION AND DECRYPTION
$key = 'qkwjdiw239&&jdafweihbrhnan&^%$ggdnawhd4njshjwuuO';
//ENCRYPT FUNCTION
function encryptthis($data, $key)
{
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

//DECRYPT FUNCTION
function decryptthis($data, $key)
{
    $encryption_key = base64_decode($key);
    list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2), 2, null);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $site = $_POST['site'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userId = $_SESSION['login_id'];
    $en_pass = encryptthis($password, $key);

    $db_connection->query("INSERT INTO passwords(site, username, password, user_id) VALUES ('{$site}', '{$username}', '{$en_pass}', {$userId});");
    header("Location:managepassword.php");
}

$db_connection->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>reCALL</title>
    <link rel="shortcut icon" href="icon.jpg" type="image/jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./organizetask.css">
</head>

<body>

    <!-- Vertical navbar -->
    <div class="vertical-nav" id="sidebar">
        <div class="py-4 px-3 mb-4">
            <div class="media d-flex align-items-center">
                <img style="border-radius: 3rem;" src="<?php echo $user['profile_image']; ?>" alt="<?php echo $user['name']; ?>">
                <div class="media-body">
                    <h4 style="padding-left: 1rem; color:white;" class="m-0"><?php echo $user['name']; ?></h4>
                </div>
            </div>
        </div>



        <ul class="nav flex-column mb-0">
            <li class="nav-item">
                <a href="index.php" class="nav-link text-light">
                    <i class="fa fa-home mr-3 text-light fa-fw"></i>
                    Home
                </a>
            </li>
        </ul>
        <br>


        <p class="text-white font-weight-bold text-uppercase px-3 small pb-4 mb-0">Operations</p>

        <ul class="nav flex-column text-white mb-0">
            <li class="nav-item">
                <a href="createnotes.php" class="nav-link text-white">
                    <i class="fa fa-pencil-square-o mr-3 text-white fa-fw"></i>
                    Notes Making
                </a>
            </li>
            <li class="nav-item">
                <a href="managepassword.php" class="nav-link text-white">
                    <i class="fa fa-lock mr-3 text-white fa-fw"></i>
                    Manage Passwords
                </a>
            </li>

            <li class="nav-item">
                <a href="organizetask.php" class="nav-link text-white">
                    <i class="fa fa-window-restore mr-3 text-white fa-fw"></i>
                    Organize Tasks
                </a>
            </li>
        </ul>


        <div class="mt-5 text-center">
            <button type="button" class="btn btn-light"><a href="logout.php" style="color:black;text-decoration: none;">Logout</a></button>
        </div>
    </div>
    <!-- End vertical navbar -->


    <!-- Page content holder -->
    <div class="page-content p-5" id="content">
        <!-- Toggle button -->
        <button id="sidebarCollapse" type="button" class="btn btn-light bg-white rounded-pill shadow-sm px-4 mb-4"><i class="fa fa-bars mr-2"></i><small class="text-uppercase font-weight-bold">Menu</small></button>

        <!-- Demo content -->
        <h2 class="display-4 text-center"><b>Password Manager</b></h2>

        <div class="separator"></div>

        <div class="container">
            <div class="col-12 mt-5 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 style="color: #2e3192;" class="display-6 align-center">Add your Passwords</h4>
                        <hr>
                        <form name="submit-new" id="submit-new" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="mb-3">
                                <label for="site" class="form-label">Website URL</label>
                                <input type="url" class="form-control" id="site" name="site" placeholder="https://www.example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <div class="form-text"><span style="color: grey; font-size:small;"><i>We'll never share your password with anyone else.</i></span></div>
                            </div>
                            <a href="managepassword.php"><button type="button" class="btn btn-danger"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back</button></a>
                            <button style="color: #fff; background-color: #2e3192;" type="submit" class="btn">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding-top:10rem; bottom: 0; width: 100%; color: white; text-align: center;">
            <p>Copyright &copy; SprintBoard</p>
        </div>

    </div>




    <!-- End demo content -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>