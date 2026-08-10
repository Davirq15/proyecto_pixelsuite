<?php
// Incluye el archivo de funciones de validación.
include("validaciones.php");

$errores = [];
$mensajeExito = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"] ?? "";
    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if(validarVacio($usuario)){
        $errores[] = "El usuario es obligatorio.";
    }

    if(!validarEmail($email)){
        $errores[] = "El correo no es válido.";
    }

    if(!validarPassword($password)){
        $errores[] = "La contraseña debe tener mínimo 8 caracteres.";
    }

    $archivo = "users.json";

    if(file_exists($archivo)){
        $usuarios = json_decode(file_get_contents($archivo), true);
    } else {
        $usuarios = [];
    }

    foreach($usuarios as $u){
        if($u["usuario"] === $usuario){
            $errores[] = "El usuario ya existe.";
            break;
        }
    }

    if(count($errores) === 0){
        $password = password_hash($password, PASSWORD_DEFAULT);

        $usuarios[] = [
            "usuario" => $usuario,
            "email" => $email,
            "password" => $password
        ];

        file_put_contents($archivo, json_encode($usuarios, JSON_PRETTY_PRINT));

        $mensajeExito = "Registro exitoso.";
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro | PC VERSUS</title>
<link rel="stylesheet" href="css/register.css">

</head>
<body class="auth-page">

<header class="header">
<a href="index.php">
<button class="boton-regresar">&larr;</button>
</a>
</header>

<div class="card-login">

<h1>REGISTRARSE</h1>

<?php if(count($errores) > 0): ?>
    <div class="mensaje error">
        <?php foreach($errores as $e) echo $e . "<br>"; ?>
    </div>
<?php endif; ?>

<?php if($mensajeExito): ?>
    <div class="mensaje success">
        <?php echo $mensajeExito; ?>
    </div>
<?php endif; ?>

<form method="post">

<label>Usuario</label>
<input type="text" name="usuario">

<label>Correo</label>
<input type="email" name="email">

<label>Contraseña</label>
<input type="password" name="password">

<button type="submit">Registrarse</button>

</form>

</div>

</body>
</html>
