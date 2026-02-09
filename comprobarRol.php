<?php

session_start();

if(!isset($_SESSION["usuario"]) || $_SESSION["rol"] != "admin")
{
    header("Location: usuariosPractica.php");
    exit();
}


?>