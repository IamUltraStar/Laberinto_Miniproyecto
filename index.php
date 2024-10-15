<?php
/* if (file_exists("src/static/sesionUser.json")) {
    $usuarioData = json_decode(file_get_contents("src/static/sesionUser.json"), true);
    if ($usuarioData) {
        header("Location: src/views/game.php");
        exit();
    }
} */
session_start();

if (isset($_SESSION['id_usuario'])) {
    header("Location: src/views/game.php");
    exit();
}else{
    header("Location: src/views/login.php");
    exit();
}
?>