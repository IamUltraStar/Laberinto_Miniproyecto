<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //unlink("../static/sesionUser.json");
    session_destroy();
    header("Location: ../views/login.php");
    exit();
}
?>
