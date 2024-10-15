<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$base_datos = "Game_Laberinto";

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
