<?php
session_start();

if (isset($_SESSION['id_usuario'])) {
    header("Location: game.php");
    exit();
}

$error = '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <meta name="description" content="Inicio de Sesion by Star" />
    <link rel="stylesheet" href="../../assets/css/login_styles.css">
    <link rel="icon" href="../../assets/img/estrella.png">
</head>
<body>
    <?php
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 1:
                    $error = "Usuario o contraseña incorrectos.";
                    break;
                case 2:
                    $error = "Debe completar ambos campos.";
                    break;
                case 3:
                    $error = "Este usuario ya está registrado.";
                    break;
                case 4:
                    $error = "Debes completar todos los campos.";
                    break;
                case 5:
                    $error = "Las contraseñas no coinciden.";
                    break;
                case 6:
                    $error = "Dominio de correo no permitido.";
                    break;
                default:
                    $error = "Error desconocido. Inténtalo de nuevo.";
            }

            echo "<dialog open>
                        <div class='body-dialog'>
                            <div class='container-dialog'>
                                <p>$error</p>
                                <form method='dialog'>
                                    <button onclick='removeErrorParam()'>Continuar</button>
                                </form>
                            </div>
                        </div>
                </dialog>";
        }
        $loginDisplay = ((isset($_GET['error']) && $_GET['error'] <= 2) || !isset($_GET['error'])) ? '' : 'hidden';
        $registerDisplay = (isset($_GET['error']) && ($_GET['error'] > 2)) ? '' : 'hidden';
        $mainContainerWidth = ($registerDisplay == '') ? 'mainContainerWidth-1000' : 'mainContainerWidth-700';
    ?>
    <div class="main-container <?php echo $mainContainerWidth; ?>">
        <img src="../../assets/img/welcome.jpg" alt="Imagen de Bienvenida">
        <div class="main-body <?php echo $loginDisplay; ?>" id="login-body">
            <h2>BIENVENIDO</h2>
            <form action="../actions/verificar_usuario.php" method="POST">
                <div>
                    <label for="">Usuario</label>
                    <input type="text" name="usuario" placeholder="Ingrese su usuario...">
                </div>
                <div>
                    <label for="">Contraseña</label>
                    <input type="password" name="password" placeholder="Ingrese su contraseña...">
                </div>
                <button type="submit">Ingresar</button>
            </form>
            <p>No estas registrado? <button type="button" class="change-rol" onclick="changeMainBody()">Registrate ahora</button></p>
        </div>
        <div class="main-body <?php echo $registerDisplay; ?>" id="register-body">
            <h2>REGISTRATE</h2>
            <form action="../actions/registrar_usuario.php" method="POST">
                <div>
                    <label for="">Nombre</label>
                    <input type="text" name="nombres" placeholder="Ingrese su nombre y apellido...">
                </div>
                <div>
                    <label for="">Correo</label>
                    <input type="text" name="correo" placeholder="Ingrese su correo electronico...">
                </div>
                <div>
                    <label for="">Usuario</label>
                    <input type="text" name="usuario" placeholder="Ingrese un usuario...">
                </div>
                <div>
                    <label for="">Contraseña</label>
                    <input type="password" name="password1" placeholder="Ingrese una contraseña...">
                </div>
                <div>
                    <label for="">Confirmar contraseña</label>
                    <input type="password" name="password2" placeholder="Confirme su contraseña...">
                </div>
                <button type="submit">Registrarme</button>
            </form>
            <p>Ya estas registrado? <button type="button" class="change-rol" onclick="changeMainBody()">Inicia sesión ahora</button></p>
        </div>
    </div>
    <script src="../scripts/login_script.js"></script>
</body>
</html>