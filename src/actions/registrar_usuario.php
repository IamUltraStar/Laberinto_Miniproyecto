<?php
include("../db/ConexionSQL.php");
session_start();

function checkErrors($conexion, $nombres, $correo, $usuario, $password1, $password2) {
    $domains_allowed = ['@gmail.com', "@hotmail.com", "@outlook.com"];

    if ($nombres == "" || $usuario == "" || $correo == "" || $password1 == "" || $password2 == "") {
        return '4';
    }

    $domain = substr($correo, strpos($correo, '@'));
    if (!in_array($domain, $domains_allowed)) {
        return '6';
    }

    $query = "SELECT usuario FROM usuario WHERE usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return '3';
    }

    if ($password1 != $password2) {
        return '5';
    }

    return '0';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST['nombres'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $type_error = checkErrors($conexion, $nombres, $correo, $usuario, $password1, $password2);

    if ($type_error == '0') {
        $hashed_password = password_hash($password1, PASSWORD_BCRYPT);
        $query = "INSERT INTO usuario (nombres, correo, usuario, contrasenia) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ssss", $nombres, $correo, $usuario, $hashed_password);

        if ($stmt->execute()) {
            //$usuario_data = ['id_usuario' => $conexion->insert_id];
            //file_put_contents("../static/sesionUser.json", json_encode($usuario_data));
            $_SESSION['id_usuario'] = $conexion->insert_id;
            
            $stmt->close();
            $conexion->close();
            header("Location: ../views/game.php");
            exit();
        } else {
            $stmt->close();
            $conexion->close();
            header("Location: ../views/login.php?error=100");
            exit();
        }
    }else{
        $conexion->close();
        header("Location: ../views/login.php?error=$type_error");
        exit();
    }
}
?>