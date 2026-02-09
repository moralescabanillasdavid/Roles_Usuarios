<?php
$usuario = '';
$password = '';
$rol = '';
$estado = '';
$intentos = '';

require 'conexion.php';

require 'comprobarRol.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $sql_select = "SELECT usuario, rol, estado, intentos FROM tusuarios WHERE id = :id";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->execute([':id' => $id]);
        $usuario_actual = $stmt_select->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario_actual) {
            $usuario = $usuario_actual['usuario'];
            $rol = $usuario_actual['rol'];
            $estado = $usuario_actual['estado'];
            $intentos = $usuario_actual['intentos'];
        } else {
            echo "<p style='color:red;'>Usuario no encontrado</p>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['enviar']) && $id) {
        function h($valor) {
            return htmlspecialchars(trim($valor), ENT_QUOTES, 'UTF-8');
        }

        $usuario = h($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = h($_POST['rol'] ?? '');
        $estado = h($_POST['estado'] ?? '');
        $intentos = h($_POST['intentos'] ?? '');

        $errores = [];
        
        if (empty($usuario)) {
            $errores[] = "El nombre de usuario es obligatorio";
        }
        
        if (!is_numeric($intentos) || $intentos < 0) {
            $errores[] = "Los intentos deben ser un número válido";
        }

        $roles_permitidos = ['usuario', 'admin', 'editor'];
        if (!in_array($rol, $roles_permitidos)) {
            $errores[] = "Rol no válido";
        }

        $estados_permitidos = ['Activo', 'Inactivo'];
        if (!in_array($estado, $estados_permitidos)) {
            $errores[] = "Estado no válido";
        }

        try {
            if (empty($errores)) {
                if (!empty($password)) {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "UPDATE tusuarios SET usuario = :usuario, password = :password, rol = :rol, estado = :estado, intentos = :intentos WHERE id = :id";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':usuario' => $usuario,
                        ':password' => $password_hash,
                        ':rol' => $rol,
                        ':estado' => $estado,
                        ':intentos' => $intentos,
                        ':id' => $id
                    ]);
                } else {
                    $sql = "UPDATE tusuarios SET usuario = :usuario, rol = :rol, estado = :estado, intentos = :intentos WHERE id = :id";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':usuario' => $usuario,
                        ':rol' => $rol,
                        ':estado' => $estado,
                        ':intentos' => $intentos,
                        ':id' => $id
                    ]);
                }

                header("Location: editarUsuarios.php");
                exit;
            } else {
                foreach ($errores as $error) {
                    echo "<p style='color:red;'>$error</p>";
                }
            }

        } catch (PDOException $e) {
            echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<html>
<head>
    <link rel="stylesheet" href="estiloWeb.css">
    <title>Modificar Usuario</title>
    <script>
        function confirmarEnvio() {
            return confirm("¿Está seguro que quiere guardar estos cambios?");
        }
    </script>
</head>
<body>
<h1>Modificar Usuario</h1>

<?php if ($id): ?>
<div class="grupo">
<form method="post" onsubmit="return confirmarEnvio();">
    <label for="usuario">Nombre de usuario:</label>
    <input type="text" id="usuario" name="usuario" value="<?php echo $usuario; ?>" required><br><br>

    <label for="password">Nueva contraseña (dejar en blanco para mantener la actual):</label>
    <input type="password" id="password" name="password"><br><br>

    <label for="rol">Rol:</label>
    <select id="rol" name="rol" required>
        <option value="usuario" <?php echo ($rol == 'usuario') ? 'selected' : ''; ?>>Usuario</option>
        <option value="admin" <?php echo ($rol == 'admin') ? 'selected' : ''; ?>>Administrador</option>
        <option value="editor" <?php echo ($rol == 'editor') ? 'selected' : ''; ?>>Editor</option>
    </select><br><br>

    <label for="estado">Estado:</label>
    <select id="estado" name="estado" required>
        <option value="Activo" <?php echo ($estado == 'Activo') ? 'selected' : ''; ?>>Activo</option>
        <option value="Inactivo" <?php echo ($estado == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
    </select><br><br>

    <label for="intentos">Intentos de login:</label>
    <input type="number" id="intentos" name="intentos" value="<?php echo $intentos; ?>" min="0" required><br><br>

    <button type="submit" name="enviar">Guardar cambios</button>
    <a href="./editarUsuarios.php">
        <button type="button">Cancelar</button>
    </a>
</form>
<?php else: ?>
    <p style='color:red;'>No se ha especificado un ID de usuario válido</p>
    <a href="./editarUsuarios.php">
        <button type="button">Volver</button>
    </a>
<?php endif; ?>
    </div>
</body>
</html>