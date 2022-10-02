<?php
require 'connect.php';

if (!isset($_SESSION['login_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['login_id'];

$get_user = mysqli_query($db_connection, "SELECT * FROM `users` WHERE `google_id`='$user_id'");

if (mysqli_num_rows($get_user) > 0) {
    $user = mysqli_fetch_assoc($get_user);
} else {
    header('Location: logout.php');
    exit;
}
?>

<?php

function get_connection()
{
    $dsn = "mysql:host=localhost;dbname=sprintboard";
    $user = "root";
    $passwd = "";
    $conn = new PDO($dsn, $user, $passwd);
    return $conn;
}

function save_task($type, $task, $id, $user_id)
{
    $conn = get_connection();
    if ($id) {
        $sql = "UPDATE organizetask SET `task`=? WHERE id=?"; // create sql
        $query = $conn->prepare($sql); // prepare
        $query->execute([$task, $id]); // execute
        return $id;
    } else {
        $sql = "INSERT INTO organizetask(`task`,`type`,`user_id`) VALUES (?,?,'$user_id')"; // create sql
        $query = $conn->prepare($sql); // prepare
        $query->execute([$task, $type]); // execute
        return $conn->lastInsertId();
    }
}

function move_task($id, $position)
{
    $conn = get_connection();
    $sql = "UPDATE organizetask SET `type`=? WHERE id=?"; // create sql
    $query = $conn->prepare($sql); // prepare
    $query->execute([$position, $id]); // execute
}

function get_tasks($type, $user_id)
{
    $results = [];
    try {
        $conn = get_connection();
        $query = $conn->prepare("SELECT * from organizetask WHERE type=? AND user_id='$user_id' order by id desc");
        $query->execute([$type]);
        $results = $query->fetchAll();
    } catch (Exception $e) {
    }
    return $results;
}

function get_task($id, $user_id)
{
    $results = [];
    try {
        $conn = get_connection();
        $query = $conn->prepare("SELECT * from organizetask WHERE id=? AND user_id='$user_id'");
        $query->execute([$id]);
        $results = $query->fetchAll();
        $results = $results[0];
    } catch (Exception $e) {
    }
    return $results;
}


function show_tile($taskObject, $type = "")
{
    $baseUrl = $_SERVER["PHP_SELF"] . "?shift&id=" . $taskObject["id"] . "&type=";
    $editUrl = $_SERVER["PHP_SELF"] . "?edit&id=" . $taskObject["id"] . "&type=" . $type;
    $deleteUrl = $_SERVER["PHP_SELF"] . "?delete&id=" . $taskObject["id"];
    $o = '<span class="board">' . $taskObject["task"] . '
      <hr>
      <span>
        <a href="' . $baseUrl . 'notstarted">' . '<i style="color:#222; padding:0.2rem;" class="fa fa-lightbulb-o" aria-hidden="true"></i>' . '</a>
        <a href="' . $baseUrl . 'pending">' . '<i style="color:#e60000; padding:0.2rem;" class="fa fa-times-circle-o" aria-hidden="true"></i>' . '</a>
        <a href="' . $baseUrl . 'progress">' . '<i style="color:#ff3300; padding:0.2rem;" class="fa fa-hourglass-half" aria-hidden="true"></i>' . '</a>
        <a href="' . $baseUrl . 'completed">' . '<i style="color:#2eb82e; padding:0.2rem;" class="fa fa-check-square-o" aria-hidden="true"></i>' . '</a>
      </span>
      <a href="' . $editUrl . '">' . '<i style="color:black; padding:0.2rem;" class="fa fa-pencil-square-o" aria-hidden="true"></i>' . '</a> <a href="' . $deleteUrl . '">' . '<i style="color:red;" class="fa fa-trash" aria-hidden="true"></i>' . '</a>
      </span>';
    return $o;
}

function get_active_value($type, $content)
{
    $currentType = isset($_GET['type']) ? $_GET['type'] :  null;
    if ($currentType == $type) {
        return $content;
    }
    return "";
}

$activeId = "";
$activeTask = "";


if (isset($_GET['shift'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $type = isset($_GET['type']) ? $_GET['type'] : null;
    if ($id) {
        move_task($id, $type);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // redirect take no action.
        header("Location: " . $_SERVER['PHP_SELF']);
    }
}

if (isset($_GET['edit'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $activeId = $id;
    $type = isset($_GET['type']) ? $_GET['type'] : null;
    if ($id) {
        $taskObject = get_task($id, $user_id);
        $activeTask = $taskObject["task"];
    }
}

if (isset($_GET['delete'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if ($id) {
        try {
            $conn = get_connection();
            $query = $conn->prepare("DELETE from organizetask WHERE id=?");
            $query->execute([$id]);
            header("Location: " . $_SERVER['PHP_SELF']);
        } catch (Exception $e) {
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $notstarted = "";
    $pending = "";
    $progress = "";
    $completed = "";
    $taskId = isset($_POST['task']) ? $_POST['task'] : null;

    if (isset($_POST['save-notstarted'])) {
        $notstarted = isset($_POST['notstarted']) ? $_POST['notstarted'] : null;
        save_task('notstarted', $notstarted, $activeId, $user_id);
    } else if (isset($_POST['save-pending'])) {
        $pending = isset($_POST['pending']) ? $_POST['pending'] : null;
        save_task('pending', $pending, $activeId, $user_id);
    } else if (isset($_POST['save-progress'])) {
        $progress = isset($_POST['progress']) ? $_POST['progress'] : null;
        save_task('progress', $progress, $activeId, $user_id);
    } else if (isset($_POST['save-completed'])) {
        $completed = isset($_POST['completed']) ? $_POST['completed'] : null;
        save_task('completed', $completed, $activeId, $user_id);
    }
    if ($activeId) {
        header("Location: " . $_SERVER['PHP_SELF']);
    }
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
    <link rel="stylesheet" href="./tasksboard.css">
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
        <h2 class="display-4 text-center"><b>SPRINT BOARD - KANBAN</b></h2>

        <div class="separator"></div>
        <div class="row text-center">
            <div class="bottom">
                <form method="post">
                    <input type="hidden" value="<?php echo $activeId; ?>" name="task" />
                    <div id="ideaboard" class="board-column">
                        <h3 class="tagheadings">Ideas <i style="color:#111; padding:0.2rem;" class="fa fa-lightbulb-o" aria-hidden="true"></i></h3>
                        <div class="board-form">
                            <input value="<?php echo get_active_value("notstarted", $activeTask); ?>" type="text" name="notstarted" style="height: 2.4rem; width: 70%" autocomplete="off" />
                            <button style="margin-top: -0.4rem;" class="btn btn-light" type=" submit" name="save-notstarted">Save</button>
                        </div>
                        <div class="board-items">
                            <?php foreach (get_tasks('notstarted', $user_id) as $task) : ?>
                                <?php echo show_tile($task, 'notstarted'); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="pendingboard" class="board-column">
                        <h3 class="tagheadings">Pending <i style="color:#800000; padding:0.2rem;" class="fa fa-times-circle-o" aria-hidden="true"></i></h3>
                        <div class="board-form">
                            <input value="<?php echo get_active_value("pending", $activeTask); ?>" type="text" name="pending" style="height: 2.4rem; width: 70%" autocomplete="off" />
                            <button style="margin-top: -0.4rem;" class="btn btn-light" type="submit" name="save-pending">Save</button>
                        </div>
                        <div class="board-items">
                            <?php foreach (get_tasks('pending', $user_id) as $task) : ?>
                                <?php echo show_tile($task, 'pending'); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="progressboard" class="board-column">
                        <h3 class="tagheadings">In Progress <i style="color:#ff3300; padding:0.2rem;" class="fa fa-hourglass-half" aria-hidden="true"></i></h3>
                        <div class="board-form">
                            <input value="<?php echo get_active_value("progress", $activeTask); ?>" type="text" name="progress" style="height: 2.4rem; width: 70%" autocomplete="off" />
                            <button style="margin-top: -0.4rem;" class="btn btn-light" type="submit" name="save-progress">Save</button>
                        </div>
                        <div class="board-items">
                            <?php foreach (get_tasks('progress', $user_id) as $task) : ?>
                                <?php echo show_tile($task, 'progress'); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="completedboard" class="board-column">
                        <h3 class="tagheadings">Completed <i style="color:#2eb82e; padding:0.2rem;" class="fa fa-check-square-o" aria-hidden="true"></i></h3>
                        <div class="board-form">
                            <input value="<?php echo get_active_value("completed", $activeTask); ?>" type="text" name="completed" style="height: 2.4rem; width: 70%" autocomplete="off" />
                            <button style="margin-top: -0.4rem;" class="btn btn-light" type="submit" name="save-completed">Save</button>
                        </div>
                        <div class="board-items">
                            <?php foreach (get_tasks('completed', $user_id) as $task) : ?>
                                <?php echo show_tile($task, 'completed'); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </form>
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