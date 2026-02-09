<?php

require"comprobarRol.php";
require"conexion.php";

?>

<html>
    <head>
        <link rel="stylesheet" href="estiloWeb.css">
    </head>
    <div class="grupo">
        
<?php
$stmt = $pdo->query("SELECT id, usuario, password, rol, estado, intentos FROM tusuarios ORDER BY id ASC");

$usuarios = $stmt->fetchAll();

if(count($usuarios) > 0)
{
    echo"<h1>Usuarios registrados</h1>";
    echo"<table class='tabla'>";
    echo"<tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Contraseña</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Intentos</th>
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
    
    echo"</th>";
        
            
    ;

    
    foreach($usuarios as $fila)
    {
        echo "<tr>";
        echo "<td>" . ($fila['id']) . "</td>";
        echo "<td>" . ($fila['usuario']) . "</td>";
        echo "<td>" . ($fila['password']) . "</td>";
        echo "<td>" . ($fila['rol']) . "</td>";
        echo "<td>" .($fila['estado']) . "</td>";
        echo "<td>" . ($fila['intentos']) . "</td>";
        echo"</tr>";
        
    }
    echo"</table>";
    
}
echo"<form action='indexAdmin.php' method='get'>
    <button type='submit'>Volver al Panel</button>
</form>";
?>

    </div>
</html>

