<?php
include("../db/ConexionSQL.php");
session_start();

function checkErrors($conexion, $id_user, $nombres, $correo, $usuario, $password1, $password2) {
    $domains_allowed = ['@gmail.com', "@hotmail.com", "@outlook.com"];

    if ($nombres == "" || $usuario == "" || $correo == "" || $password1 == "" || $password2 == "") {
        return '4';
    }

    $domain = substr($correo, strpos($correo, '@'));
    if (!in_array($domain, $domains_allowed)) {
        return '6';
    }

    $query = "SELECT usuario FROM usuario WHERE usuario = ? AND id_usuario != ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("si", $usuario, $id_user);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        //$jsonData = file_get_contents("../static/sesionUser.json");
        //$sessionData = json_decode($jsonData, true);
        $id_user = $_SESSION['id_usuario'] ?? null;

        $nombres = $_POST['nombres'];
        $correo = $_POST['correo'];
        $usuario = $_POST['usuario'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        $type_error = checkErrors($conexion, $id_user, $nombres, $correo, $usuario, $password1, $password2);

        if ($type_error == '0') {
            $hashed_password = password_hash($password1, PASSWORD_BCRYPT)
            $query = "UPDATE usuario SET nombres = ?, correo = ?, usuario = ?, contrasenia = ? WHERE id_usuario = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ssssi", $nombres, $correo, $usuario, $hashed_password, $id_user);
            $stmt->execute();

            $stmt->close();
            $conexion->close();
            header("Location: ../views/game.php");
            exit();
        }else{
            $conexion->close();
            echo "Hubo un error en los datos ingresados, intente de nuevo.";
        }

    } catch (Exception $e) {
        echo "Hubo un error inesperado en la solicitud.";
    }
}
?>