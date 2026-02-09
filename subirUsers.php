<?php
//Script qpara subir los usuarios a la base de datos. Cambiar el nombre de la BBDD si hace falta
// Mostrar el formulario si no se ha enviado ningún archivo
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
?>
<form method="post" enctype="multipart/form-data">
 Selecciona el fichero de usuarios: <input type="file" name="usuariosfile"
accept=".txt"><br>
 <input type="submit" value="Importar usuarios">
</form>
<?php
} else {
 // 1. Verificar que se ha subido el fichero
 if (isset($_FILES['usuariosfile']) && $_FILES['usuariosfile']['error'] ==
UPLOAD_ERR_OK) {
 // 2. Guardar temporalmente el fichero subido
 $tmpName = $_FILES['usuariosfile']['tmp_name'];
 // 3. Conexión PDO MySQL (AJUSTA tus datos)
 $pdo = new PDO("mysql:host=localhost;dbname=bd_daw2;charset=utf8", "root", "");
 // 4. Leer el fichero de usuarios en claro
 $lineas = file($tmpName, FILE_IGNORE_NEW_LINES);
//Se podría haber hecho usando fgets() con fopen() bucle while y fclose, pero como
//es un fichero tan pequeño, lo puedo leer todo en memoria, sin problema.
 $contador = 0;
 foreach ($lineas as $linea) {
 if (trim($linea) === '') continue; // omitir líneas vacías
 list($usuario, $clave) = explode(':', $linea);
 $hash = password_hash($clave, PASSWORD_DEFAULT);
 // Insertar en la base de datos
 $stmt = $pdo->prepare("INSERT INTO tusuarios (usuario, password) VALUES
(?, ?)");
 $stmt->execute([$usuario, $hash]);
 $contador++;
 }
 echo "Usuarios importados correctamente con clave hasheada. Total: $contador";
 } else {
 echo "Error al subir el fichero.";
 }
}
?>