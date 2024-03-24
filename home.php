<?php
require_once("./system/db.php");

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$msg = '';


if (isset($_GET['delete'])) {
    $num_user_detect = mysqli_num_rows(mysqli_query($db, "SELECT id FROM users WHERE id='$_GET[delete]'"));
    if ($num_user_detect > 0) {
        //Codigo para eleminacion de usuario
        mysqli_query($db, "DELETE FROM `users` WHERE id='" . $_GET['delete'] . "'");
        $msg = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Exito!</strong> Usuario Borrado correctamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    } else {
        $msg = ' 
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> No se ha podido eliminar ese usuario, porque no existe!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }
}
if (isset($_GET['delete'])) {
    // Código para eliminar el blog
    $blogId = $_GET['delete'];
    mysqli_query($db, "DELETE FROM `blog` WHERE id='$blogId'");
    $msg = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Éxito!</strong> Blog eliminado correctamente!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}

if (isset($_GET['banned'])) {
    
    $num_user_detect = mysqli_num_rows(mysqli_query($db, "SELECT id FROM users WHERE id='$_GET[banned]'"));
    if ($num_user_detect > 0) {
        $asunto = "Termínos y Condiciones Incumplidas";
        //Codigo para banear de usuario
        mysqli_query($db, "UPDATE `users` SET `banned` = '1', `motivo_baneo` = '$asunto' WHERE id='" . $_GET['banned'] . "'");
        $msg = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Exito!</strong> Usuario Baneado correctamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    } else {
        $msg = ' 
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> No se ha podido Banear ese usuario!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['user_id'])) {
        // Recupera los datos del formulario
        $nombre = $_POST['nombre'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $pregunta= $_POST['pregunta'];
        $respuesta = $_POST['respuesta'];
        

        $id = $_POST['user_id'];

        if ($password != '') {
            $password_hash = hash("sha256", $password);
            //Codigo para actualizar usuario con cambio de contraseña
            $sql_query = "UPDATE `users` SET `name`='$nombre',`username`='$username',`email`='$email',`password`='$password_hash',
             `Pregunta_Seguridad` = '$pregunta', `Respuesta_Seguridad` = '$respuesta' WHERE id='$id'";
            $msg = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Exito!</strong> Usuario Editado correctamente (Contraseña modificada)!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        } else {
            //Codigo para actualizar datos del usurio menos la contraseña 
            $sql_query = "UPDATE `users` SET `name`='$nombre',`username`='$username',`email`='$email',`Pregunta_Seguridad` = '$pregunta', `Respuesta_Seguridad` = '$respuesta'  WHERE id='$id'";
            $msg = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Exito!</strong> Usuario Editado correctamente!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        }

        mysqli_query($db, $sql_query);


    } else {
    //     // Recupera los datos del formulario
    //     $nombre = $_POST['nombre'];
    //     $username = $_POST['username'];
    //     $email = $_POST['email'];
    //     $password = $_POST['password'];
    //     $pregunta= $_POST['pregunta'];
    //     $respuesta = $_POST['respuesta'];
       

    //     $password_hash = hash("sha256", $password);

    //     $count_username = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `users` WHERE username='$username' LIMIT 1"));

    //     $count_email = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `users` WHERE email='$email' LIMIT 1"));
    //         //Validacion de usuario si esta registrado 
    //     if ($count_username == 1) {
    //         $msg = '
    //     <div class="alert alert-danger alert-dismissible fade show" role="alert">
    //         <strong>Error!</strong> Ese usuario ya ha sido registrado!
    //         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //     </div>
    //     ';
    //         //Validacion de email si esta registrado 
    //     } else if ($count_email == 1) {
    //         $msg = '
    //     <div class="alert alert-danger alert-dismissible fade show" role="alert">
    //         <strong>Error!</strong> Ese email ya ha sido registrado!
    //         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //     </div>
    //     ';
    //         //Registrar los datos del nuevo usuario 
    //     } else {
    //         $sql = "INSERT INTO `users` (`name`, `username`, `email`, `password`, `Pregunta_Seguridad`, `Respuesta_Seguridad`) VALUES ('$nombre', '$username', '$email', '$password_hash','$pregunta','$respuesta')";
    //         if (mysqli_query($db, $sql)) {
    //             $msg = '
    //         <div class="alert alert-success alert-dismissible fade show" role="alert">
    //             <strong>Exito!</strong> Usuario Registrado correctamente!
    //             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //         </div>
    //         ';
    //         } else {
    //             $msg = '
    //         <div class="alert alert-danger alert-dismissible fade show" role="alert">
    //             <strong>Error!</strong> Ha ocurrido un problema!
    //             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //         </div>
    //         ';
    //         }
    //     }
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_blog'])) {
    // Código para actualizar el blog
    $blogId = $_POST['blog_id'];
    $titulo = $_POST['titulo'];
    $descripcion_breve = $_POST['descripcion_breve'];
    $descripcion = $_POST['descripcion'];

    // Puedes agregar más campos según tus necesidades

    mysqli_query($db, "UPDATE `blog` SET `titulo`='$titulo', `descripcion_breve`='$descripcion_breve', `descripcion`='$descripcion' WHERE id='$blogId'");
    $msg = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Éxito!</strong> Blog actualizado correctamente!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}
$_SESSION['token']=md5(uniqid("clave"));
$token= $_SESSION['token'];



?>  
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script>
        // Función para confirmar la eliminación del blog
        function confirmDelete(blogId) {
            var result = confirm("¿Estás seguro de que quieres eliminar este blog?");
            if (result) {
                window.location.href = "home.php?delete=" + blogId;
            }
        }
    </script>

</head>

<body id="homepage">

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
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Usuarios
                        </a>
                        <ul class="dropdown-menu">
                            <li><button type="button" class="btn btn" data-bs-toggle="modal" data-bs-target="#add_user_">Agregar Usuario</button></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="Tabla_de_Usuarios.php">mostrar usuarios</a></li>
                            <li><a class="dropdown-item" href="usuarios_baneados.php">mostrar usuarios baneados</a></li>
                        </ul>
                    </li>
                    
                    <a class="nav-link" href="login.php">Salir</a>
                </div>
                </div>
            </div>
            </nav>
            <h2>Blogs diarios</h2>
            <div class="text-center">
            <?= $msg ?>
        </div>
    <div class="container mt-5" id="contenedor" >
        
        

        <?php
        $sql_blog = "SELECT `id`, `titulo`, `descripcion_breve`, `descripcion`, `imagen`, `usuario`, `fecha` FROM `blog` LIMIT 10";

        $blog_arr = mysqli_query($db, $sql_blog);
        foreach ($blog_arr as $blog) {
        ?>
            <div class="card" style="width: 18rem; display: flexbox; margin-bottom: 20px">
                <img src="data:image/jpg;base64,<?php echo base64_encode($blog['imagen']) ?>" class="card-img-top" alt="imagen">
                <div class="card-body">
                    <h5 class="card-title"><?= $blog['titulo'] ?></h5>
                    <p class="card-text"><?= $_SESSION['username'] ?></p>
                    <p class="card-text"><?= $blog['descripcion_breve'] ?></p>
                    <p class="card-text"><?= $blog['descripcion'] ?></p>
                    <p class="card-text"><?= $blog['fecha'] ?></p>

                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $blog['id'] ?>">Editar</a>
                    <a href="#" onclick="confirmDelete(<?= $blog['id'] ?>)" class="btn btn">Eliminar</a>
                </div>
            </div>
            <div>  </div>
            <!-- Modal para editar blog -->
            <div class="modal fade" id="editModal<?= $blog['id'] ?>" tabindex="-1" aria-labelledby="editModal<?= $blog['id'] ?>Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModal<?= $blog['id'] ?>Label">Editar Blog</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulario de edición -->
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título</label>
                                    <input type="text" name="titulo" class="form-control" id="titulo" value="<?= $blog['titulo'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion_breve" class="form-label">Descripción Breve</label>
                                    <textarea name="descripcion_breve" class="form-control" id="descripcion_breve" required><?= $blog['descripcion_breve'] ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea name="descripcion" class="form-control" id="descripcion" required><?= $blog['descripcion'] ?></textarea>
                                </div>
                                <input type="hidden" name="blog_id" value="<?= $blog['id'] ?>">
                                <button type="submit" class="btn btn-primary" name="edit_blog">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    
<!-- Botón para agregar nuevo usuario -->
        <div class="modal fade" id="add_user_" tabindex="-1" aria-labelledby="add_user_Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" id ="headerform">
                        <h1 class="modal-title fs-5" id="add_user_Label">Añadir usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id = "formularioregistro" >
                        <form method="POST" action="" >
                            <div class="form-group mt-2">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" id="nombre" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" name="username" class="form-control" id="username" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            <div class="form-group mt-2">
                                <select class="form-center" aria-label="Default select example" name="pregunta" id="pregunta">
                                    <option selected>Seleciona una pregunta secreta</option>
                                    <option value="1">Comida Favorita?</option>
                                    <option value="2">Color Favorito?</option>
                                    <option value="3">Fecha de Nacimiento?</option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="respuesta" class="form-label">Respuesta de Seguridad</label>
                                <input type="text" name="respuesta" class="form-control" id="respuesta" required>
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-primary" name="añadir">Añadir usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        

        

                    <!-- Modal -->
                    <div class="modal fade" id="edit_<?= $user['id'] ?>" tabindex="-1" aria-labelledby="edit_<?= $user['id'] ?>Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" id ="headeredit">
                                    <h1 class="modal-title fs-5" id="edit_<?= $user['id'] ?>Label">Editar: <?= $user['username'] ?></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id ="formedit">
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" name="nombre" class="form-control" id="nombre" value="<?= $user['name'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Nombre de Usuario</label>
                                            <input type="text" name="username" class="form-control" id="username" value="<?= $user['username'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo Electrónico</label>
                                            <input type="email" name="email" class="form-control" id="email" value="<?= $user['email'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Contraseña</label>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Vacio, Si no se realiza ningun cambio">
                                        </div>
                                        <div class="form-group mt-2">
                                            <select class="form-center" aria-label="Default select example" name="pregunta" id="pregunta">
                                                <option selected>Seleciona una pregunta secreta</option>
                                                <option value="1">Comida Favorita?</option>
                                                <option value="2">Color Favorito?</option>
                                                <option value="3">Fecha de Nacimiento?</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="respuesta" class="form-label">Respuesta de Seguridad</label>
                                            <input type="text" name="respuesta" class="form-control" id="respuesta" required>
                                        </div>
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-primary">Edit Now</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                <!-- < ?php
                }
                ?> -->
            </tbody>
        </table>
    </div>
    <!-- <div class="container mt-5" id="contenedor"> 
    < ?php
    $sql_blog = "SELECT `titulo`, `descripcion_breve`, `descripcion`,`imagen`,`usuario`  FROM `blog` ";

    $blog_arr = mysqli_query($db, $sql_blog);
    foreach ($blog_arr as $blog){
        ?>
        <div class="card" style="width: 18rem; display: flex;">
            <img  src="data:image/jpg;base64,< ?php echo base64_encode($blog['imagen']) ?>" class="card-img-top" alt="imagen">
                <div class="card-body">
                <h5 class="card-title">< ?= $blog['titulo'] ?></h5>
                <p class="card-text">< ?= $_SESSION['username']?></p>
                <p class="card-text">< ?= $blog['descripcion_breve'] ?></p>
                <p class="card-text">< ?= $blog['descripcion'] ?></p>
                <a href="" class="btn btn-primary">Actualizar</a>
                <a href="" class="btn btn">Eliminar</a>
                

                </div>
        </div>
        



< ?php
    }
    ?> -->
    </div>
    <footer>
            
        
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>