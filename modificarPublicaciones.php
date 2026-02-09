<?php
require "conexion.php";
require "comprobarAdminYEditor.php";
$titulo = '';
$cuerpo = '';
$creador = '';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $sql_select = "SELECT titulo, cuerpo, creador FROM tarticulos WHERE id = :id";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->execute([':id' => $id]);
        $articulo_actual = $stmt_select->fetch(PDO::FETCH_ASSOC);
        
        if ($articulo_actual) {
            $titulo = $articulo_actual['titulo'];
            $cuerpo = $articulo_actual['cuerpo'];
            $creador = $articulo_actual['creador'];
        } else {
            echo "<p>Artículo no encontrado</p>";
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

        $titulo = h($_POST['titulo'] ?? '');
        $cuerpo = h($_POST['cuerpo'] ?? '');
        $creador = h($_POST['creador'] ?? '');

        $errores = [];
        
        if (empty($titulo)) {
            $errores[] = "El título es obligatorio";
        }
        
        if (empty($cuerpo)) {
            $errores[] = "El cuerpo es obligatorio";
        }

        if (empty($errores)) {
            try {
                $sql = "UPDATE tarticulos SET titulo = :titulo, cuerpo = :cuerpo, creador = :creador WHERE id = :id";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':titulo' => $titulo,
                    ':cuerpo' => $cuerpo,
                    ':creador' => $creador,
                    ':id' => $id
                ]);

                header("Location: editarPublicaciones.php");
                exit;

            } catch (PDOException $e) {
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        } else {
            foreach ($errores as $error) {
                echo "<p>$error</p>";
            }
        }
    }
    
    if (isset($_POST['eliminar']) && $id) {
        try {
            $sql_delete = "DELETE FROM tarticulos WHERE id = :id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute([':id' => $id]);
            
            header("Location: editarPublicaciones.php");
            exit;
            
        } catch (PDOException $e) {
            echo "<p>Error al eliminar: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<html>
<head>
    <title>Modificar Publicación</title>
    <link rel="stylesheet" href="estiloWeb.css">
    <script>
        function confirmarEnvio() {
            return confirm("¿Está seguro que quiere guardar estos cambios?");
        }
        
        function confirmarEliminacion() {
            return confirm("¿Está seguro que quiere ELIMINAR esta publicación? Esta acción no se puede deshacer.");
        }
    </script>
</head>
<body>
    <div class="grupo">
<h1>Modificar Publicación</h1>

<?php if ($id): ?>
<form method="post" onsubmit="return confirmarEnvio();">
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?php echo $titulo; ?>" required><br><br>

    <label for="cuerpo">Cuerpo:</label>
    <textarea id="cuerpo" name="cuerpo" required><?php echo $cuerpo; ?></textarea><br><br>

    <label for="creador">Creador:</label>
    <input type="text" id="creador" name="creador" value="<?php echo $creador; ?>" required><br><br>

    <button type="submit" name="enviar">Guardar cambios</button>
    <a href="./editarPublicaciones.php">
        <button type="button">Cancelar</button>
    </a>
    
    <button type="submit" name="eliminar" onclick="return confirmarEliminacion();" 
            style="background-color: #e74c3c; margin-left: 10px;">
        Eliminar Publicación
    </button>
</form>
<?php else: ?>
    <p>No se ha especificado un ID de artículo válido</p>
    <a href="./editarPublicaciones.php">
        <button type="button">Volver</button>
    </a>
<?php endif; ?>
    </div>
</body>
</html>