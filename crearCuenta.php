<?php
// Inicializar variables
$usuario = '';
$password = '';

// Incluir archivo de conexión
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['enviar'])) {
        function h($valor) {
            return htmlspecialchars(trim($valor), ENT_QUOTES, 'UTF-8');
        }

        $usuario = h($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validaciones básicas
        $errores = [];
        
        if (empty($usuario)) {
            $errores[] = "El nombre de usuario es obligatorio";
        }
        
        if (empty($password)) {
            $errores[] = "La contraseña es obligatoria";
        }

        try {
            if (empty($errores)) {
                // Verificar si el usuario ya existe
                $sql_check = "SELECT id FROM tusuarios WHERE usuario = :usuario";
                $stmt_check = $pdo->prepare($sql_check);
                $stmt_check->execute([':usuario' => $usuario]);
                
                if ($stmt_check->fetch()) {
                    $errores[] = "El nombre de usuario ya existe";
                } else {
                    // Crear nuevo usuario
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO tusuarios (usuario, password, rol, estado, intentos) 
                            VALUES (:usuario, :password, 'usuario', 'Activo', 0)";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':usuario' => $usuario,
                        ':password' => $password_hash
                    ]);

                    header("Location: usuariosPractica.php");
                    exit;
                }
            }
            
            // Mostrar errores si los hay
            if (!empty($errores)) {
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
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="estiloWeb.css">
    <script>
        function confirmarEnvio() {
            return confirm("¿Está seguro que quiere crear este usuario?");
        }
    </script>
</head>
<body>
<h1>Crear Nuevo Usuario</h1>

<div class="grupo">
<form method="post" onsubmit="return confirmarEnvio();">
    <label for="usuario">Nombre de usuario:</label>
    <input type="text" id="usuario" name="usuario" value="<?php echo $usuario; ?>" required><br><br>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit" name="enviar">Crear usuario</button>
    <a href="./usuariosPractica.php">
        <button type="button">Cancelar</button>
    </a>
</form>
</div>
</body>
</html>