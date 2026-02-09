<?php

session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'editor')
{
    header: ("Location: usuariosPractica.php");
    exit();
}