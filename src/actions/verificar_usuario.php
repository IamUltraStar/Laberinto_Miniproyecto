<?php
include("../db/ConexionSQL.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $valorLogin = FALSE;

    if (empty($usuario) || empty($password)) {
        header("Location: ../views/login.php?error=2");
        exit();
    }

    $query = "SELECT id_usuario, usuario, contrasenia FROM usuario WHERE usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['contrasenia'])) {
            //$usuario_data = ['id_usuario' => $row['id_usuario'];
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $valorLogin = TRUE;
        }
    }

    $stmt->close();
    $conexion->close();

    if ($valorLogin) {
        //file_put_contents("../static/sesionUser.json", json_encode($usuario_data));
        header("Location: ../views/game.php");
        exit();
    }else{
        header("Location: ../views/login.php?error=1");
        exit();
    }
}
?>
