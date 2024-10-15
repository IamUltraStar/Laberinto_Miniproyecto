<?php
include("../db/ConexionSQL.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        //$jsonData = file_get_contents("../static/sesionUser.json");
        //$sessionData = json_decode($jsonData, true);
        $id_user = $_SESSION['id_usuario'] ?? null;

        $nivel = $_POST['nivel'];
        $tiempo_jugado = $_POST['tiempo_jugado'];
        $puntaje = $_POST['puntaje'];

        $query = "UPDATE usuario SET nivel = ?, tiempo_jugado = ?, puntaje = ? WHERE id_usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iiii", $nivel, $tiempo_jugado, $puntaje, $id_user);
        $stmt->execute();
        echo $stmt->affected_rows > 0 ? 'success' : 'error';
        $stmt->close();
        $conexion->close();
    } catch (Exception $e) {
        echo "Hubo un error inesperado.";
    }
}
?>
