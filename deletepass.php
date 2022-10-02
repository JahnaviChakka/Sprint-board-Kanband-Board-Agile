<?php
include_once "./connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['delid'];
    $sql = mysqli_query($db_connection, "DELETE FROM passwords WHERE id = $id;");
}

header("Location:http://localhost/WEBDEV/SprintBoard/managepassword.php");
