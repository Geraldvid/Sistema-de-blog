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
        // Recupera los datos del formulario
        $nombre = $_POST['nombre'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $pregunta= $_POST['pregunta'];
        $respuesta = $_POST['respuesta'];
       

        $password_hash = hash("sha256", $password);

        $count_username = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `users` WHERE username='$username' LIMIT 1"));

        $count_email = mysqli_num_rows(mysqli_query($db, "SELECT id FROM `users` WHERE email='$email' LIMIT 1"));
            //Validacion de usuario si esta registrado 
        if ($count_username == 1) {
            $msg = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Ese usuario ya ha sido registrado!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
            //Validacion de email si esta registrado 
        } else if ($count_email == 1) {
            $msg = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Ese email ya ha sido registrado!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
            //Registrar los datos del nuevo usuario 
        } else {
            $sql = "INSERT INTO `users` (`name`, `username`, `email`, `password`, `Pregunta_Seguridad`, `Respuesta_Seguridad`) VALUES ('$nombre', '$username', '$email', '$password_hash','$pregunta','$respuesta')";
            if (mysqli_query($db, $sql)) {
                $msg = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Exito!</strong> Usuario Registrado correctamente!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            } else {
                $msg = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Ha ocurrido un problema!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Usuarios</title>
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
    <!-- Tabla para mostrar la lista de usuarios -->
   

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $sql_users = "SELECT `id`, `name`, `username`, `email`, `date` FROM `users` WHERE banned = 0";
                
                $users_arr = mysqli_query($db, $sql_users);

                foreach ($users_arr as $user) {
                    ?>
                    <tr>
                        <th scope="row"><?= $user['id'] ?></th>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['date'] ?></td>
                        <td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_<?= $user['id'] ?>">Edit</button> 
                        <a href="?delet=<?= $user['id'] ?>" class="btn btn-danger">Delete</a> 
                        <a href="?banned=<?= $user['id'] ?>" class="btn btn-danger">Banear</a></td>
                    </tr>
                    <!-- Modal editar usuario -->
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
                    <?php
                }
                ?>

        
  <!-- Modal añadir usuario-->
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
        <div class="text-center">
            <?= $msg ?>
        </div>
    

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
