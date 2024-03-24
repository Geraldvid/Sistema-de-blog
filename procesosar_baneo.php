<?php
require_once("./system/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_POST['id'];
    $motivo_baneo = $_POST['motivo_baneo'];

    $sql = "UPDATE users SET banned = 1, motivo_baneo = '$motivo_baneo' WHERE id = $usuario_id";

    if ($db->query($sql) === TRUE) {
        header('Location: home.php');
    } else {
        echo "Error al banear al usuario: " . $db->error;
    }
}

$db->close();
?>