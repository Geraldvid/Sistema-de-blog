<?php
require_once("./system/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT blog.*, users.username AS usuario_nombre FROM blog 
            INNER JOIN users ON blog.usuario = users.id LIMIT 10";
    $result = mysqli_query($db, $sql);

    $blogs = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $blogs[] = $row;
        }
        echo json_encode($blogs);
    } else {
        echo json_encode(['error' => 'Error al obtener blogs: ' . mysqli_error($db)]);
    }
} else {
    echo json_encode(['error' => 'Error al obtener blogs: ' . mysqli_error($db)]);
}
?>
