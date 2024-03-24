<?php
require_once("./system/db.php");

// if(isset($_SESSION['username'])){
//     header('Location: login.php');
//     exit;
// }

$msg="";
// Procesa el formulario de registro cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera los datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']); 
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $password_hash= hash("sha256", $password);




    $count_username=mysqli_num_rows(mysqli_query($db, "SELECT id FROM `users` WHERE username='$username' LIMIT 1"));

    $count_email=mysqli_num_rows(mysqli_query($db, "SELECT id FROM `users` WHERE email='$email' LIMIT 1"));

    if($count_username==1){
        $msg= "Ese usuario ya ha sido registrado!";
    }else if($count_email==1){
        $msg= "Ese email ya ha sido registrado!";
    }else{
        $sql="INSERT INTO `users` (`name`, `username`, `email`, `password`) VALUES ('$nombre', '$username', '$email', '$password_hash')";
        if(mysqli_query($db, $sql)){



            $assoc_user=mysqli_fetch_assoc(mysqli_query($db, "SELECT username FROM `users` WHERE username='$username' LIMIT 1"));
            $_SESSION['username'] = $assoc_user['username'];


            
            header('Location: home.php');
        }else{
            $msg= "Ha ocurrido un problema";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body id="login">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card" id ="card">
                    <div class="card-header" id="formedit">Registro</div>
                    <div class="card-body" id ="headerform">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" id="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" name="username" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                        </form>
                        <div class="text-center">
                            <a href="login.php" id="enlace">Ya tienes una cuenta creada, acceda ahora!</a>
                        </div>
                        <?=$msg?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
