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

?>

<!DOCTYPE html>
<html>

<head>
    <title>SprintBoard</title>
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
            <div class="media d-flex align-items-center justify-content-center">
                <img width="100rem" height="auto" style="border-radius: 3rem;" src="<?php echo $user['profile_image']; ?>" alt="<?php echo $user['name']; ?>">
            </div>
            <div class="media-body text-center mt-3">
                <h4 style="color:white;" class="m-0"><?php echo $user['name']; ?></h4>
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

        <div style="padding-top:10rem; bottom: 0; width: 100%; color: white; text-align: center;">
            <p>Copyright &copy; SprintBoard</p>
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
        <?php
        $res = $db_connection->query("SELECT * FROM passwords WHERE user_id = {$userId} ORDER BY (favorite IS TRUE) DESC, username;");
        $array = array();

        while ($item = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            $array[] = $item;
        }

        $db_connection->close();
        ?>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 mt-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <a href="addnewpass.php" class="mb-4"><button style="color: #fff; background-color: #2e3192;" class="btn mb-2"><i class="fa fa-plus" aria-hidden="true"></i> &nbsp;Add Password</button></a>



                            <?php
                            $counter = 1;
                            foreach ($array as $item) {
                                echo '
                            <div class="mt-3">
                                <div style="background-color: #F1F1F6; margin: 2.5rem; border-radius: 1rem !important;" class="card-body">
                                    <div class="row px-3">
                                        <div class="col-11 mb-2"><h5><a href="' . $item["site"] . '" target="_blank"><i class="ml-2 fa fa-external-link" aria-hidden="true"></i> ' . $item["site"] . '</a></h5></div>
                                        <div class="col-1 d-flex" style="text-align: right;">
                                            <form method="post" class="me-4" action="favpass.php" id="favorite-' . $item['id'] . '">
                                                <input type="hidden" name="favid" value="' . $item['id'] . '">';
                                if ($item["favorite"]) {
                                    echo '<i class="fa fa-star text-warning" aria-hidden="true" style="cursor: pointer;" title="Remove from favorites" onclick="document.getElementById(\'favorite-' . $item['id'] . '\').submit();"></i>';
                                } else {
                                    echo '<i class="fa fa-star-o" style="cursor: pointer;" title="Add to favorites" onclick="document.getElementById(\'favorite-' . $item['id'] . '\').submit();"></i>';
                                }
                                echo '</form>
                                            <form method="post" action="deletepass.php" id="delete-' . $item['id'] . '">
                                                <input type="hidden" name="delid" value="' . $item['id'] . '">
                                                <i class="fa fa-trash text-danger ml-2" aria-hidden="true" style="cursor: pointer;" title="Delete" onclick="document.getElementById(\'delete-' . $item['id'] . '\').submit();"></i>
                                            </form>
                                        </div>
        
                                        <hr class="mt-2">
                                    </div>
        
                                    <div class="row px-3">
                                        <div class="col-6">
                                            <label for="username-' . $counter . '" class="form-label">Username</label>
                                            <input id="username-' . $counter . '" type="text" class="form-control" value="' . $item['username'] . '" disabled>
                                        </div>
                                        <div class="col-6">
                                            <label for="password-' . $counter . '" class="form-label">Password</label>
                                            <div class="input-group mb-3">
                                            
                                            <input id="password-' . $counter . '" type="password" class="form-control" value="' . decryptthis($item["password"], $key) . '" disabled>
                                                <span class="input-group-text"><i class="fa fa-eye" aria-hidden="true" style="cursor: pointer;" id="password-' . $counter . '-eye" onclick="showPw(\'password-' . $counter . '\')"> </i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                                $counter++;
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showPw(id) {
                const field = document.getElementById(id);
                const eye = document.getElementById(`${id}-eye`);
                if (field.type == "text") {
                    field.type = "password";
                    eye.classList.remove("fa-eye-slash");
                    eye.classList.add("fa-eye");
                } else {
                    field.type = "text";
                    eye.classList.add("fa-eye-slash");
                    eye.classList.remove("fa-eye");
                }
            }
        </script>
    </div>




    <!-- End demo content -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>