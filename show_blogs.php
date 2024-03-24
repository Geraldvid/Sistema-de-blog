<?php
require_once("./system/db.php");

if (isset($_GET['delete'])) {
    $num_user_detect = mysqli_num_rows(mysqli_query($db, "SELECT id FROM blog WHERE id='$_GET[delete]'"));
    if ($num_user_detect > 0) {
        //Codigo para eleminacion de usuario
        mysqli_query($db, "DELETE FROM `blog` WHERE id='" . $_GET['delete'] . "'");
        $msg = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Exito!</strong> Blog Borrado correctamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    } else {
        $msg = ' 
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> No se ha podido eliminar ese Blog, porque no existe!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }
}
if (isset($_POST['blog_id'])) {
    // Recupera los datos del formulario
    $titulo = htmlspecialchars($_POST['titulo']);
    $descripcion_breve = htmlspecialchars($_POST['descripcion_breve']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    
    

    $id = $_POST['blog_id'];

    if ($password != '') {
        $password_hash = hash("sha256", $password);
        //Codigo para actualizar usuario con cambio de contraseña
        $sql_query = "UPDATE `blog` SET `titulo`='$titulo',`descripcion_breve`='$descripcion_breve',`descripcion`='$descripcion',`imagen`='$imagen'WHERE id='$id'";
        $msg = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Exito!</strong> blog Editado correctamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Blogs</title>
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
                    <a class="nav-link" href="show_blogs.php">Ver blogs</a>
                   
                    
                    <a class="nav-link" href="login.php">Salir</a>
                </div>
                </div>
            </div>
            </nav>

            
        <div id="contenedor">

        

        <?php
    $sql_blog = "SELECT `titulo`, `descripcion_breve`, `descripcion`,`imagen`,`usuario`  FROM `blog` ";

    $blog_arr = mysqli_query($db, $sql_blog);
    foreach ($blog_arr as $blog){
        ?>
        <div class="card" style="width: 18rem; display: flex;">
            <img  src="data:image/jpg;base64,<?php echo base64_encode($blog['imagen']) ?>" class="card-img-top" alt="imagen">
                <div class="card-body">
                <h5 class="card-title"><?= $blog['titulo'] ?></h5>
                <p class="card-text"><?= $blog['descripcion_breve'] ?></p>
                <p class="card-text"><?= $blog['usuario'] ?></p>
                <p class="card-text"><?= $blog['descripcion'] ?></p>
                <a href="edit_<?= $blog['id'] ?>" class="btn btn-primary">editar</a>
                <a href="?delet=<?= $blog['id'] ?>" class="btn btn-danger">Delete</a> 

                </div>
        </div>

        <div class="modal fade" id="edit_<?= $blog['id'] ?>" tabindex="-1" aria-labelledby="edit_<?= $blog['id'] ?>Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" id ="headeredit">
                                    <h1 class="modal-title fs-5" id="edit_<?= $blog['id'] ?>Label">Editar: <?= $blog['titulo'] ?></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id ="formedit">
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="titulo" class="form-label">Titulo</label>
                                            <input type="text" name="titulo" class="form-control" id="titulo" value="<?= $blog['titulo'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcion_breve" class="form-label">Descripción breve</label>
                                            <input type="text" name="descripcion_breve" class="form-control" id="descripcion_breve" value="<?= $blog['descripcion_breve'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <input type="text" name="descripcion" class="form-control" id="descripcion" value="<?= $blog['descripcion'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="imagen" class="form-label">Imagen</label>
                                            <input type="file" name="imagen" class="form-control" id="imagen" >
                                        </div>
                                        
                                        <input type="hidden" name="blog_id" value="<?= $blog['id'] ?>">
                                        <button type="submit" class="btn btn-primary">Edit Now</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="container mt-5" id="contenedor"> 
   


<?php
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>