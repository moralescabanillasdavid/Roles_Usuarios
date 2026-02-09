<?php
require "conexion.php";
require "comprobarAdminYEditor.php";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim(htmlspecialchars($_POST["titulo"]));
    $cuerpo = trim(htmlspecialchars($_POST["cuerpo"]));
    
    
    $stmt = $pdo->prepare("INSERT INTO tarticulos (titulo, cuerpo, creador) VALUES (?, ?, ?)");
    $stmt->execute([$titulo, $cuerpo, $_SESSION['usuario']]);
    
    echo "Artículo creado correctamente";
}
?>

<html>
    <head>
        <link rel="stylesheet" href="estiloWeb.css">
    </head>
    <div class="grupo">
    <form method="post">
        <label for="titulo">Título: </label>
        <br>
        <input type="text" id="titulo" name="titulo" required>
        <br><br>
        <label for="cuerpo">Cuerpo: </label>
        <br>
        <textarea id="cuerpo" name="cuerpo" required></textarea>
        <br><br>
        <button type="submit">Crear Artículo</button> 
    </form>
    <?php
    if($_SESSION['rol'] == 'admin')
{
    echo"<form action='indexAdmin.php' method='get'>
    <button type='submit'>Volver</button>
</form>";
}
elseif($_SESSION['rol'] == 'editor')
{
   echo"<form action='indexEditor.php' method='get'>
    <button type='submit'>Volver</button>
</form>"; 
}
else
{
   echo"<form action='perfilUsuario.php' method='get'>
    <button type='submit'>Volver</button>
</form>"; 
}
    ?>
        </div>
</html>

