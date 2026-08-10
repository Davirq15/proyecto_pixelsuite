<?php

// Inicia la sesión actual para poder manipularla.
session_start();

// Destruye todos los datos de la sesión.
session_destroy();

// Redirige al usuario a la página principal.
header("Location: index.php");
exit();

?>
