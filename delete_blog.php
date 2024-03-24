<?php
require_once("./system/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $blogId = $_POST['id'];
    $sql = "DELETE FROM blog WHERE id = $blogId";
    
    if (mysqli_query($db, $sql)) {
        echo "Blog borrado con éxito.";
    } else {
        echo "Error al borrar el blog: " . mysqli_error($db);
    }
} else {
    echo "Solicitud no válida.";
}
?>
