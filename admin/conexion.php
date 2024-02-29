<?php
// Datos del servidor MySQL
$server = "localhost";      // Nombre del servidor
$username = "root";         // Nombre de usuario de la base de datos
$password = "";             // Contraseña de la base de datos
$bd = "bd_quiz";            // Nombre de la base de datos

// Creamos una conexión a la base de datos
$conn = mysqli_connect($server, $username, $password, $bd);

// Chequeamos si la conexión fue exitosa
if (!$conn) {
    // Si la conexión falla, se muestra un mensaje de error y se termina el script
    die("Conexión fallida: " . mysqli_connect_error());
}

// En este punto, la conexión ha sido exitosa y se puede utilizar para realizar consultas a la base de datos
?>


