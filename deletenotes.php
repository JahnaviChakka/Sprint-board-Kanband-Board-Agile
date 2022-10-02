<?php
include_once "./connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sno = $_POST['delid'];
    $sql = mysqli_query($db_connection, "DELETE FROM notes WHERE sno = $sno;");
}

header("Location:http://localhost/WEBDEV/SprintBoard/createnotes.php");
