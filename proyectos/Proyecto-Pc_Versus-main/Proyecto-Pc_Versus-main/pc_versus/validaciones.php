<?php

// Verifica si el campo está vacío.
function validarVacio($campo){
    return empty(trim($campo));
}

// Verifica el formato del correo.
function validarEmail($correo){
    return filter_var($correo, FILTER_VALIDATE_EMAIL);
}

// Verifica la longitud mínima de la contraseña.
function validarPassword($password){
    return strlen($password) >= 8;
}
