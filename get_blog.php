<?php
// get_blog.php

require_once("./system/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $blogId = $_GET['id'];
    $sql = "SELECT * FROM blog WHERE id = $blogId";
    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $blogData = mysqli_fetch_assoc($result);

        // Devolver los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($blogData);
    } else {
        echo "Blog no encontrado.";
    }
} else {
    echo "Solicitud no válida.";
}
?>
