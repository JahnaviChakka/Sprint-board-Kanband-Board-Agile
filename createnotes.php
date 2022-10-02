<?php

require 'connect.php';

if (!isset($_SESSION['login_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_SESSION['login_id'];

$get_user = mysqli_query($db_connection, "SELECT * FROM `users` WHERE `google_id`='$id'");

if (mysqli_num_rows($get_user) > 0) {
    $user = mysqli_fetch_assoc($get_user);
} else {
    header('Location: logout.php');
    exit;
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
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="createnotes.css">
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
        <h2 class="display-4 text-center"><b>SprintBoard - My Notebook</b></h2>

        <div class="separator"></div>
        <div class="row">
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit your Notes</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="./createnotes.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="snoEdit" id="snoEdit">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" required title="This field is required" class="form-control" id="titleEdit" name="titleEdit" maxlength="40" aria-describedby="emailHelp">
                                </div>

                                <div class="form-group">
                                    <label for="desc">Description</label>
                                    <textarea required class="form-control" id="descriptionEdit" name="descriptionEdit" rows="6" cols="150" type="text"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer d-block mr-auto">
                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-outline-secondary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php
            if (isset($_POST['snoEdit'])) {
                // Update the record
                $sno = $_POST["snoEdit"];
                $title = $_POST["titleEdit"];
                $description = $_POST["descriptionEdit"];
                $id = $_SESSION['login_id'];

                // Sql query to be executed
                $sql = "UPDATE notes SET title = '$title', description = '$description' WHERE sno = '$sno'";
                $result = mysqli_query($db_connection, $sql);
                if ($result) {
                    echo "<script>alert('Updated Successfully')</script>";
                } else {
                    echo "<script>alert('Failed to update')</script>";
                }
            }
            ?>
            <?php
            $res = $db_connection->query("SELECT * FROM notes WHERE id = {$id}");
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
                                <a href="addnotes.php" class="mb-4"><button style="color: #fff; background-color: #2e3192;" class="btn mb-2"><i class="fa fa-plus" aria-hidden="true"></i> &nbsp;Add Notes</button></a>

                                <?php
                                $counter = 1;
                                foreach ($array as $item) {
                                    echo '
                            <div class="mt-3">

                                <div style="background-color:#fff; margin: 2.5rem; border:0.15rem solid #2e3192; border-radius: 1rem !important;" class="card-body">

                                    <div class="row px-3 edit">

                                    <div class="col-11 mb-2"><h5 class="title' . $item['sno'] . '" name="title' . $item['sno'] . '" id="title' . $item['sno'] . '">' . $item["title"] . '</h5></div>
                                    
                                    <div class="col-1 d-flex" style="text-align: right;">
                                    
                                            <form method="post" id="edit-' . $item['sno'] . '">
                                                <input type="hidden" name="' . $item['sno'] . '" id="' . $item['sno'] . '" value="' . $item['sno'] . '">
                                                <i id="' . $item['sno'] . '" onclick="myFunc(this.id)" class="fa fa-pencil-square-o text-dark ml-2" aria-hidden="true" style="cursor: pointer;" title="edit"></i>
                                            </form>

                                            <form method="post" action="deletenotes.php" id="delete-' . $item['sno'] . '">
                                                <input type="hidden" name="delid" value="' . $item['sno'] . '">
                                                <i class="fa fa-trash text-danger ml-2" aria-hidden="true" style="cursor: pointer;" title="Delete" onclick="document.getElementById(\'delete-' . $item['sno'] . '\').submit();"></i>
                                            </form>
                                        </div>
        
                                        <hr class="mt-2">
                                    </div>
        
                                    <div class="row px-3">
                                    <textarea style="background-color:#fff;" name="description' . $item['sno'] . '" id="description' . $item['sno'] . '" rows="6" cols="150" type="text" class="form-control" disabled>' . $item['description'] . '</textarea>
                                    </div>
                                </div>
                            </div>';
                                    $counter++;
                                }
                                ?>

                            </div>

                            <script>
                                var dynamicVar;

                                function myFunc(clicked) {
                                    dynamicVar = clicked;
                                }
                                edits = document.getElementsByClassName('edit');
                                Array.from(edits).forEach((element) => {
                                    element.addEventListener("click", (e) => {
                                        title = document.querySelector(".title" + dynamicVar).textContent;
                                        console.log(title);

                                        description = document.getElementById("description" + dynamicVar).value;
                                        console.log(description);

                                        sno = document.getElementById(dynamicVar).value;
                                        console.log(sno);

                                        // console.log(title, description);

                                        titleEdit.value = title;
                                        descriptionEdit.value = description;
                                        snoEdit.value = sno;
                                        $('#editModal').modal('toggle');
                                    })
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- End demo content -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>