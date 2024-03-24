<?php

require_once("./system/db.php");
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = htmlspecialchars($_POST['titulo']);
    $descripcion_breve = htmlspecialchars($_POST['descripcion_breve']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    
    $usuario = $_SESSION['username'];

    $fecha = date("Y-m-d H:i:s"); // Fecha actual

    

    $sql = "UPDATE `blog` SET `titulo`='$titulo',`descripcion_breve`='$descripcion_breve',`descripcion`='$descripcion',`imagen`='$imagen' WHERE id='$id'";
            

    if (mysqli_query($db, $sql)) {
        echo "Blog creado exitosamente.";
    } else {
        echo "Error al crear el blog: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" id="navbarhome">
            <div class="container-fluid">
                <a class="navbar-brand" href="home.php">Blogs master</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    <a class="nav-link" href="create_blog.php">Crear blogs</a>
                    
                   
                    
                    <a class="nav-link" href="login.php">Salir</a>
                </div>
                </div>
            </div>
            </nav>
            <div class="card-back" id="crear-blog">
				<div class="center-wrap">
					<div class="section text-center">
                        <h2>Actualizar el Blog</h2>
                        <div class="mb-3">
                        <form method="post" action="" enctype="multipart/form-data">
                            <label for="titulo">Título:</label>
                            <input type="text" name="titulo" class="form-style" required><br>

                            <label for="descripcion_breve">Descripción Breve:</label>
                            <input type="text" name="descripcion_breve" class="form-style" required><br>

                            <label for="descripcion">Descripción:</label>
                            <textarea name="descripcion" class="form-style" required></textarea><br>

                            <label for="imagen">URL de la Imagen:</label>
                            <input type="file" name="imagen" id="imagen" class="form-style"  required><br>

                            <button type="submit">Crear Blog</button>
                        </form>
                        </div>
                    </div>
				</div>
			</div>
           
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>
