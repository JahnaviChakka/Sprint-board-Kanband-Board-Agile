<?php
include_once "./connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['favid'];
    $sql = mysqli_query($db_connection, "UPDATE passwords SET favorite = NOT favorite WHERE id = $id;");
}

header("Location:http://localhost/WEBDEV/SprintBoard/managepassword.php");
