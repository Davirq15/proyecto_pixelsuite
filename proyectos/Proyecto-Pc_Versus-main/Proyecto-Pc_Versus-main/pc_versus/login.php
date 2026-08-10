<?php
// Inicia la sesión para poder guardar datos del usuario.
session_start();

// Incluye el archivo donde están las funciones de validación.
include("validaciones.php");

$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"] ?? "";
    $password = $_POST["password"] ?? "";

    if(validarVacio($usuario)){
        $errores[] = "El usuario es obligatorio.";
    }

    if(validarVacio($password)){
        $errores[] = "La contraseña es obligatoria.";
    }

    if(count($errores) === 0){
        $archivo = "users.json";

        if(file_exists($archivo)){
            $usuarios = json_decode(file_get_contents($archivo), true);

            foreach($usuarios as $u){
                if($u["usuario"] === $usuario && password_verify($password, $u["password"])){
                    $_SESSION["usuario"] = $u["usuario"];
                    header("Location: index.php");
                    exit();
                }
            }

            $errores[] = "Usuario o contraseña incorrectos.";
        } else {
            $errores[] = "No hay usuarios registrados.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión | PC VERSUS</title>
<link rel="stylesheet" href="css/register.css">

</head>
<body class="auth-page">

<header class="header">
<a href="index.php">
<button class="boton-regresar">&larr;</button>
</a>
</header>

<div class="card-login">

<h1>INICIO DE SESIÓN</h1>

<?php if(count($errores) > 0): ?>
    <div class="mensaje error">
        <?php foreach($errores as $e){ echo $e . "<br>"; } ?>
    </div>
<?php endif; ?>

<form method="post">

<label>Usuario</label>
<input type="text" name="usuario">

<label>Contraseña</label>
<input type="password" name="password">

<button type="submit">Iniciar sesión</button>

</form>

</div>

</body>
</html>
