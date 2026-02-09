<?php
require "conexion.php";
require "existeUsuario.php";
?>

<html>
    <head>
        <link rel="stylesheet" href="estiloWeb.css">
        <style>
            .publicaciones-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 15px;
                padding: 20px;
            }
            
            .publicacion {
                width: 100%;
                max-width: 600px;
                background-color: white;
                border: 1px solid #e1e8ed;
                border-radius: 8px;
                padding: 15px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            .publicacion-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
            
            .creador {
                font-weight: bold;
                color: #2c3e50;
                font-size: 16px;
            }
            
            .titulo {
                font-size: 18px;
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 10px;
            }
            
            .cuerpo {
                font-size: 15px;
                line-height: 1.4;
                color: #333;
            }
            
            .btn-cerrar {
                background-color: #e74c3c;
                color: white;
                border: none;
                border-radius: 4px;
                padding: 5px 10px;
                cursor: pointer;
                font-size: 14px;
            }
            
            .btn-cerrar:hover {
                background-color: #c0392b;
            }
        </style>
    </head>
    <body>
        <div class="grupo">
            <div class="publicaciones-container">
<?php

$stmt = $pdo->query("SELECT titulo, cuerpo, creador FROM tarticulos ORDER BY id ASC");
$articulos = $stmt->fetchAll();

if(count($articulos) > 0)
{
    echo"<h1>Publicaciones</h1>";
    
    echo"<div style='width: 100%; max-width: 600px; text-align: right; margin-bottom: 20px;'>";
    echo"<form action='";
    if($_SESSION['rol'] == 'admin') {
        echo "indexAdmin.php";
    } elseif($_SESSION['rol'] == 'editor') {
        echo "indexEditor.php";
    } else {
        echo "perfilUsuario.php";
    }
    echo "' method='get' style='display: inline;'>";
    echo "<button class='btn-cerrar' type='submit'>X</button>";
    echo "</form>";
    echo"</div>";

    foreach($articulos as $fila)
    {
        echo "<div class='publicacion'>";
        echo "<div class='publicacion-header'>";
        echo "<span class='creador'>@" . htmlspecialchars($fila['creador']) . "</span>";
        echo "</div>";
        echo "<div class='titulo'>" . htmlspecialchars($fila['titulo']) . "</div>";
        echo "<div class='cuerpo'>" . htmlspecialchars($fila['cuerpo']) . "</div>";
        echo "</div>";
    }
}
else
{
    echo "<h1>Publicaciones</h1>";
    echo "<p>No hay publicaciones disponibles.</p>";
    
    echo"<form action='";
    if($_SESSION['rol'] == 'admin') {
        echo "indexAdmin.php";
    } elseif($_SESSION['rol'] == 'editor') {
        echo "indexEditor.php";
    } else {
        echo "perfilUsuario.php";
    }
    echo "' method='get'>";
    echo "<button type='submit'>Volver</button>";
    echo "</form>";
}
?>
            </div>
        </div>
    </body>
</html>