<?php
require_once("./system/db.php");

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$msg = '';
if (isset($_GET['delet'])) {
    $num_user_detect = mysqli_num_rows(mysqli_query($db, "SELECT id FROM users WHERE id='$_GET[delet]'"));
    if ($num_user_detect > 0) {
        //Codigo para eleminacion de usuario
        mysqli_query($db, "DELETE FROM `users` WHERE id='" . $_GET['delet'] . "'");
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
if (isset($_GET['desbanned'])) {
    $num_user_detect = mysqli_num_rows(mysqli_query($db, "SELECT id FROM users WHERE id='$_GET[desbanned]'"));
    if ($num_user_detect > 0) {
        $asunto = "";
        //Codigo para desbanear de usuario
        mysqli_query($db, "UPDATE `users` SET `banned` = '0', motivo_baneo = '$asunto' WHERE id='" . $_GET['desbanned'] . "'");
        $msg = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Exito!</strong> Usuario Desbaneado correctamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    } else {
        $msg = ' 
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> No se ha podido Desbanear ese usuario!
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

        $id = $_POST['user_id'];

        if ($password != '') {
            $password_hash = hash("sha256", $password);
            //Codigo para actualizar usuario con cambio de contraseña
            $sql_query = "UPDATE `users` SET `name`='$nombre',`username`='$username',`email`='$email',`password`='$password_hash' WHERE id='$id'";
            $msg = '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Exito!</strong> Usuario Editado correctamente (Contraseña modificada)!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
        } else {
            //Codigo para actualizar datos del usurio menos la contraseña 
            $sql_query = "UPDATE `users` SET `name`='$nombre',`username`='$username',`email`='$email' WHERE id='$id'";
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
            $sql = "INSERT INTO `users` (`name`, `username`, `email`, `password`) VALUES ('$nombre', '$username', '$email', '$password_hash')";
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
    <title>Usuarios Baneados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body id="bannedpage" >

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
    <div class="container mt-5">
        <h2>Lista de Usuarios Baneados</h2>    

        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="" >
            <a href="home.php" id="listaban" >Regresar</a>
        </button>

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
                $sql_users = "SELECT `id`, `name`, `username`, `email`, `date` FROM `users` WHERE banned = 1";
                
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
                        <a href="?desbanned=<?= $user['id'] ?>" class="btn btn-danger">Desbanear</a></td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="edit_<?= $user['id'] ?>" tabindex="-1" aria-labelledby="edit_<?= $user['id'] ?>Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" id ="headeredit">
                                    <h1 class="modal-title fs-5" id="edit_<?= $user['id'] ?>Label">Editar: <?= $user['username'] ?></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id = "formedit">
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
                
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>