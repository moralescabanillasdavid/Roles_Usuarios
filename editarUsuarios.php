<?php
require "comprobarRol.php";
require "conexion.php";
?>
<html>
    <head> 
        <link rel="stylesheet" href="estiloWeb.css">
    </head>
    <body>
        <div class='grupo'>
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
        <th>Acciones</th>
        <th>";
    
    echo"<form action='indexAdmin.php' method='get' style='display: inline;'>";
    echo "<button style='background-color:red;type='submit'>X</button>";
    echo "</form>";
    
    echo"</th>
        </tr>";

    foreach($usuarios as $fila)
    {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila['id']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['usuario']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['password']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['rol']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['estado']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['intentos']) . "</td>";
        echo "<td>
                    <a href='modificarUser.php?id=" . urlencode($fila['id']) . "'>
                        <button type='button'>Cambiar</button>
                    </a>
                  </td>";
        ; 
        echo"</tr>";
    }
    echo"</table>";
}
else
{
    echo "<p>No hay usuarios registrados.</p>";
    
    echo"<form action='indexAdmin.php' method='get'>";
    echo "<button type='submit'>Regresar</button>";
    echo "</form>";
}
?>
        </div>
    </body>
</html>