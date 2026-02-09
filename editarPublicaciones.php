<?php

require"conexion.php";
require "comprobarAdminYEditor.php";

$stmt = $pdo->query("SELECT id, titulo, cuerpo, creador FROM tarticulos ORDER BY id ASC");

$articulos = $stmt->fetchAll();
?>
<html>
    <head>
        <link rel="stylesheet" href="estiloWeb.css">
    </head>
<?php
if(count($articulos) > 0)
{
    echo"<div class='grupo'>";
    echo"<h1>Publicaciones</h1>";
    echo"<table class='tabla'>";
    echo"<tr>
        <th>ID</th>
        <th>Título</th>
        <th>Cuerpo</th>
        <th>Creador</th>
        <th>";
    
    echo"<form action='";
    if($_SESSION['rol'] == 'admin') {
        echo "indexAdmin.php";
    } elseif($_SESSION['rol'] == 'editor') {
        echo "indexEditor.php";
    } else {
        echo "perfilUsuario.php";
    }
    echo "' method='get' style='display: inline;'>";
    echo "<button style='background-color: red'type='submit'>X</button>";
    echo "</form>";
    
    echo"</th>
        </tr>";

    
    foreach($articulos as $fila)
    {
        echo "<tr>";
        echo "<td>" . ($fila['id']) . "</td>";
        echo "<td>" . ($fila['titulo']) . "</td>";
        echo "<td>" . ($fila['cuerpo']) . "</td>";
        echo "<td>" . ($fila['creador']) . "</td>";
        
        echo "<td>
                    <a href='./modificarPublicaciones.php?id=" . urlencode($fila['id']) . "'>
                        <button type='button'>Cambiar</button>
                    </a>
                  </td>";
        echo"</tr>";
        
    }
    echo"</table>";
    
}
if($_SESSION['rol'] == 'admin')
{
    echo"<form action='indexAdmin.php' method='get'>
    <button type='submit'>Volver</button>
</form>";
    echo"</div>";
}
elseif($_SESSION['rol'] == 'editor')
{
   echo"<form action='indexEditor.php' method='get'>
    <button type='submit'>Volver</button>
</form>";
       echo"</div>";

}
else
{
   echo"<form action='perfilUsuario.php' method='get'>
    <button type='submit'>Volver</button>
</form>"; 
       echo"</div>";

}

?>
</html>

