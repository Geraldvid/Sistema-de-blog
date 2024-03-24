<?php

require_once("./system/db.php"); 

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pregunta = $_POST['pregunta'];
    $respuesta = $_POST['respuesta'];

    $query = "SELECT * FROM users WHERE username = '$username' AND email = '$email' AND Pregunta_Seguridad = '$pregunta' AND Respuesta_Seguridad = '$respuesta'";

    $result = $db->query($query);

    if ($result->num_rows == 1) {
        // Usuario y respuesta de seguridad coinciden
        $row = $result->fetch_assoc();
        $username = $row['username'];

        // Generar nueva contraseña
        $nuevaContraseña = generateRandomPassword();
        $hashNuevaContraseña = password_hash($nuevaContraseña, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $updateQuery = "UPDATE users SET `password` = '$hashNuevaContraseña' WHERE email = '$email'";
        $db->query($updateQuery);

        // Enviar la nueva contraseña al usuario (puedes personalizar este correo)
        // $mensaje = "Hola $username,\nTu nueva contraseña es: $nuevaContraseña";
        // mail($email, "Recuperación de Contraseña", $mensaje);
        echo "<script>alert('Hola $username, tu contraseña es: $nuevaContraseña');</script>";

        $msg = "Contraseña mostrada con éxito.";
    } else {
        $msg = "La información proporcionada no coincide. Inténtalo de nuevo.";
    }
}

function generateRandomPassword($length = 8) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body id="login">
<div class="container mt-5" >
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card" id ="card">
                    <div class="card-header" id="formedit">Recuperación de Contraseña</div>
                    <div class="card-body" id ="headerform">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de Usuario</label>
                                <input type="text" name="username" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                            <select class="form-select" aria-label="Default select example" name="pregunta" id="pregunta">
                                <option selected>Seleciona una pregunta secreta</option>
                                <option value="1">Comida Favorita?</option>
                                <option value="2">Color Favorito?</option>
                                <option value="3">Fecha de Nacimiento?</option>
                            </select>
                            </div>
                            <div class="mb-3">
                                <label for="respuesta" class="form-label">Respuesta Secreta</label>
                                <input type="text" name="respuesta" class="form-control" id="respuesta" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                        </form>
                        <div class="text-center">
                            <a href="login.php" id="enlace">Ya tienes una cuenta creada, acceda ahora!</a>
                        </div>
                        <center>
                            <div class="mb-3">
                                <?=$msg?>
                            </div>
                        
                        </center>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>