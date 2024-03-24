<?php
session_start();
$servername = "localhost"; // Nombre del servidor de la base de datos
$username = "root"; // Nombre de usuario de la base de datos
$password = ""; // Contraseña de la base de datos
$database = "my_system_db"; // Nombre de la base de datos

// Crear una conexión a la base de datos
$db = mysqli_connect($servername, $username, $password, $database);

// Verificar la conexión
if (!$db) {
    die("Conexión fallida: " . mysqli_connect_error());
}
