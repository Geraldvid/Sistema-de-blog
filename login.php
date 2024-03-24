<?php
require_once("./system/db.php");
$msg="";

// if(isset($_SESSION['username'])){
//     header('Location: home.php');
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera los datos de inicio de sesión
    $username = $_POST['username'];
    // $password = hash("sha256", $_POST['password']);

    function sanit($variable){
        global $db;
        return mysqli_real_escape_string($db, htmlspecialchars($variable));
    }
    // $password = mysql_real_escape_string($db, htmlspecialchars($_POST['password']));

    $password = hash("sha256",sanit($_POST['password']));



    $query="SELECT * FROM `users` WHERE (`username`='$username' OR `email`='$username') AND `password`='$password'  LIMIT 1";

    $result = $db->query($query);

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();

        if ($row['banned'] == 1) {
            $msg="El usuario esta baneado no puede ingresar.  Motivo: ". $row['motivo_baneo'];

        }else{
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
            header('Location: home.php');
        }

   
    }
    
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body id="login">
    <div class="container mt-5" >
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card" id ="card">
                    <div class="card-header" id="formedit">Iniciar Sesión</div>
                    <div class="card-body" id ="headerform">
                        <form method="post" action="" >
                            <div class="mb-3">
                                <label for="username" class="form-label">Correo Electrónico / Username</label>
                                <input type="text" name="username" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </form>
                        <div class="text-center">
                            <a href="registro.php" id="enlace">No tienes una cuenta creada, Creala Ahora!</a><br>
                            <a href="Recuperacion_contra.php" id="enlace"> Olvidaste la contraseña?</a>
                        </div>
                        <?php
                         echo $msg;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
