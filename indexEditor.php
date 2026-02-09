<?php
require "comprobarEditor.php";

?>

<html>
    <head>
        <link rel="stylesheet" href="estiloWeb.css">
    </head>
    <div class="grupo">
    <h1>Bienvenid@ <?php echo $_SESSION['usuario']; ?></h1>
    <form method="post">
        <button type="submit" name="action" value="crearPublicacion">Crear publicación</button>
        <button type="submit" name="action" value="verContenido">Ver Contenido</button>
        <button type="submit" name="action" value="editarPublicaciones">Editar publicaciones</button>
        <button type="submit" name="action" value="cerrarSesion">Cerrar Sesión</button>
    </form>
    </div>
</html>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST["action"]))
    {
        switch($_POST["action"])
        {
            case "crearPublicacion":
                header("Location: crearPublicacion.php");
                exit();
            
            case "verContenido":
                header("Location: verContenido.php");
                exit();
            case "cerrarSesion";
                session_abort();
                header("Location: usuariosPractica.php");
                exit();
            case "editarPublicaciones":
                header("Location: editarPublicaciones.php");
                exit();
        }
    }
}
?>