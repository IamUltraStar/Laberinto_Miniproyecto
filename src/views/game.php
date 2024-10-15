<?php
include("../db/ConexionSQL.php");
session_start();

function transformarTiempoJugado ($tiempo) {
    $horas = floor($tiempo / 3600);
    $minutos = floor(($tiempo % 3600) / 60);
    $segundos = $tiempo % 60;
    return sprintf("%02dh %02dm %02ds", $horas, $minutos, $segundos);
}

try {
    //$jsonData = file_get_contents("../static/sesionUser.json");
    //$sessionData = json_decode($jsonData, true);
    $id_user = $_SESSION['id_usuario'] ?? null;

    $query = "SELECT nombres, correo, usuario, nivel, tiempo_jugado, puntaje FROM usuario WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $nombres = $row['nombres'];
        $correo = $row['correo'];
        $usuario = $row['usuario'];
        $nivel = $row['nivel'];
        $tiempo_jugado = $row['tiempo_jugado'];
        $puntaje = $row['puntaje'];
    }else{
        //unlink("../static/sesionUser.json");
        header("Location: login.php");
        exit();
    }

    $users_leaderboard = [];
    $query = "SELECT usuario, puntaje, tiempo_jugado FROM usuario ORDER BY puntaje DESC, tiempo_jugado ASC LIMIT 10";
    $result = $conexion->query($query);

    while ($row = $result->fetch_assoc()) {
        $users_leaderboard[] = $row;
    }

    $horas = floor($tiempo_jugado / 3600);
    $minutos = floor(($tiempo_jugado % 3600) / 60);
    $segundos = $tiempo_jugado % 60;
    $cronometro = sprintf("Cronómetro: %02d:%02d:%02d", $horas, $minutos, $segundos);
} catch (Exception $e) {
    //unlink("../static/sesionUser.json");
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Maze</title>
    <meta name="description" content="Juego de Laberinto by Star" />
    <link rel="stylesheet" href="../../assets/css/game_styles.css">
    <link rel="icon" href="../../assets/img/estrella.png">
</head>
<body>
    <dialog id="dialogEditDataUser">
        <div class='body-dialog'>
            <div class='container-dialog'>
                <header>
                    <h2>Editar mis datos</h2>
                    <form method='dialog'>
                        <button class="button-cerrar" onclick="closeDialogEditDataUser()">X</button>
                    </form>
                </header>
                <form action="../actions/actualizar_informacion_usuario.php" method="POST">
                    <div>
                        <label for="">Nombre</label>
                        <input type="text" value="<?php echo htmlspecialchars($nombres) ?>" name="nombres">
                    </div>
                    <div>
                        <label for="">Correo</label>
                        <input type="text" value="<?php echo htmlspecialchars($correo) ?>" name="correo">
                    </div>
                    <div>
                        <label for="">Usuario</label>
                        <input type="text" value="<?php echo htmlspecialchars($usuario) ?>" name="usuario">
                    </div>
                    <div>
                        <label for="">Contraseña nueva</label>
                        <input type="password" name="password1" placeholder="Ingrese una contraseña...">
                    </div>
                    <div>
                        <label for="">Confirmar contraseña</label>
                        <input type="password" name="password2" placeholder="Ingrese una contraseña...">
                    </div>
                    <button>Actualizar mis datos</button>
                </form>
            </div>
        </div>
    </dialog>
    <section class="user-section">
        <div class="card card-user">
            <img src="../../assets/img/usuario.png" alt="Imagen de Usuario">
            <div class="card-body">
                <p><b style="color: lightsalmon;">Nombres:</b> <?php echo htmlspecialchars($nombres) ?></p>
                <p><b style="color: lightsalmon;">Correo:</b> <?php echo htmlspecialchars($correo) ?></p>
                <p><b style="color: lightsalmon;">Usuario:</b> <?php echo htmlspecialchars($usuario) ?></p>
                <p><b style="color: lightsalmon;">Puntaje:</b> <span id="perfil-puntaje"><?php echo htmlspecialchars($puntaje) ?></span> pts</p>
                <button onclick="openDialogEditDataUser()">Editar mis datos</button>
                <form action="../actions/cerrar_sesion.php" method="POST">
                    <button type="submit" class="button-cerrar">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </section>
    <section class="game-section">
        <div class="card card-game">
            <h2 id="title-game">Juego del Laberinto: Nivel <?php echo htmlspecialchars($nivel+1) ?> - Laberinto N°1</h2>
            <p id="cronometro-output"><?php echo htmlspecialchars($cronometro) ?></p>
            <div class="game-container">
                <div id="laberinto"></div>
                <button id="work-laberinto" onclick="iniciarCronometro()">Iniciar</button>
                <p id="mensaje"></p>
            </div>
        </div>
    </section>
    <section class="leaderboard-section">
        <div class="card card-leaderboard">
            <h2>TABLA DE CLASIFICACIÓN</h2>
            <div class="card-body">
                <ol>
                    <?php foreach ($users_leaderboard as $user): ?>
                        <?php $tiempo_formateado = transformarTiempoJugado($user['tiempo_jugado']); ?>
                        <li><?php echo htmlspecialchars($user['usuario']) . " - " . htmlspecialchars($user['puntaje']) . " pts - " . htmlspecialchars($tiempo_formateado) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </section>
    <script>
        let tiempo = <?php echo ($tiempo_jugado); ?>;
        let nivelActual = <?php echo ($nivel); ?>;
        let puntajeActual = <?php echo ($puntaje); ?>;
    </script>
    <script src="../scripts/game_script.js"></script>
</body>
</html>