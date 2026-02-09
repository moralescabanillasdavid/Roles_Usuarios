<?php

session_start();

if(!isset($_SESSION["usuario"]) || $_SESSION["rol"] == "usuario")
{
    header("Location: usuariosPractica.php");
    exit();
}

?>
