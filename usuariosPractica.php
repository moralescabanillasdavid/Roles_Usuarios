<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST["action"]) && $_POST["action"] == "crearCuenta") {
        header("Location: crearCuenta.php");
        exit;
    }
    
    $usuario = trim(htmlspecialchars(filter_input(INPUT_POST, 'usuario')));
    $clave = trim(filter_input(INPUT_POST, 'clave'));
    
    $pdo = new PDO("mysql:host=localhost;dbname=bd_daw2;charset=utf8", "root", "");
    
    $stmt = $pdo->prepare("SELECT id, usuario, password, intentos, rol, estado FROM tusuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$fila) {
        echo "<p style ='color: red;'>Usuario o contraseña incorrectos.</p>";
        echo"<form action='usuariosPractica.php' method='get'>
                    <button type='submit'>Volver</button>
                    </form>";
    } elseif ($fila['estado'] == 'Inactivo') {
        echo "<p style ='color: red;'>Cuenta bloqueada. Contacta con el administrador.</p>";
        echo"<form action='usuariosPractica.php' method='get'>
                    <button type='submit'>Volver</button>
                    </form>";
    } else {
        if (password_verify($clave, $fila['password'])) {
            $stmt = $pdo->prepare("UPDATE tusuarios SET intentos = 0 WHERE usuario = ?");
            $stmt->execute([$usuario]);
            
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $fila['rol'];
            
            if($fila['rol'] == 'admin') {
                header('Location: indexAdmin.php');
                exit;
            } elseif($fila["rol"] == "editor") {
                header('Location: indexEditor.php');
            } else {
                header('Location: perfilUsuario.php');
                exit;
            }
        } else {
            $nuevos_intentos = $fila['intentos'] + 1;
            
            if ($nuevos_intentos >= 3) {
                $stmt = $pdo->prepare("UPDATE tusuarios SET intentos = ?, estado = 'Inactivo' WHERE usuario = ?");
                $stmt->execute([$nuevos_intentos, $usuario]);
                echo "<p style='color: red;'>Cuenta bloqueada por demasiados intentos fallidos. Contacta con el administrador.</p>";
                echo"<form action='usuariosPractica.php' method='get'>
                    <button type='submit'>Volver</button>
                    </form>";
            } else {
                $stmt = $pdo->prepare("UPDATE tusuarios SET intentos = ? WHERE usuario = ?");
                $stmt->execute([$nuevos_intentos, $usuario]);
                echo "<p style='color: red;'>Usuario o contraseña incorrectos. Intentos fallidos: $nuevos_intentos de 3</p>";
                echo"<form action='usuariosPractica.php' method='get'>
                    <button type='submit'>Volver</button>
                    </form>";
            }
        }
    }
} else {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="estiloWeb.css">
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <br>
    <form method="post">
        <div class="grupo">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" required>
        </div>
        <br>
        <div class="grupo">
            <label for="clave">Contraseña:</label>
            <input type="password" name="clave" id="clave" required>
        </div>
        <input type="submit" value="Entrar">
    </form>
    
    <form method="post">
        <p>¿No tienes cuenta? Haz click aquí</p>
        <input type="hidden" name="action" value="crearCuenta">
        <button type="submit">Crear cuenta</button>
    </form>
</body>
</html>
<?php
}