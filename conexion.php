<?php 
// Este archivo sirve para conectarse a la base de datos. Para usarlo, se escribe require y el nombre del archivo
//después se puede usar para las consultas CRUD
$host= "localhost";
$dbname = "bd_daw2";
$user = "root";
$pass = "";

//conexión a la base de datos
try
{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e)
{
    die("Error de conexión: " . $e->getMessage());
}
?>

