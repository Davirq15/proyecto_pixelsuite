<?php
session_start();

header("Content-Type: application/json; charset=UTF-8");

if(!isset($_SESSION["usuario"])){
    http_response_code(401);
    echo json_encode(["ok" => false, "mensaje" => "Sesión no válida."]);
    exit();
}

$rutaJson = __DIR__ . DIRECTORY_SEPARATOR . "componentes.json";
$categoriasValidas = ["CPU", "GPU", "RAM"];

function leerComponentes(string $rutaJson): array{
    if(!file_exists($rutaJson)){
        return ["CPU" => [], "GPU" => [], "RAM" => []];
    }

    $contenido = file_get_contents($rutaJson);
    $datos = json_decode($contenido, true);

    if(!is_array($datos)){
        return ["CPU" => [], "GPU" => [], "RAM" => []];
    }

    foreach (["CPU", "GPU", "RAM"] as $categoria){
        if(!isset($datos[$categoria]) || !is_array($datos[$categoria])){
            $datos[$categoria] = [];
        }
    }

    return $datos;
}

if($_SERVER["REQUEST_METHOD"] === "GET"){
    echo json_encode(["ok" => true, "datos" => leerComponentes($rutaJson)], JSON_UNESCAPED_UNICODE);
    exit();
}

if($_SERVER["REQUEST_METHOD"] !== "POST"){
    http_response_code(405);
    echo json_encode(["ok" => false, "mensaje" => "Método no permitido."]);
    exit();
}

$entrada = json_decode(file_get_contents("php://input"), true);

if(!is_array($entrada)){
    http_response_code(400);
    echo json_encode(["ok" => false, "mensaje" => "Datos inválidos."]);
    exit();
}

$categoria = isset($entrada["categoria"]) ? trim((string)$entrada["categoria"]) : "";
$nombre = isset($entrada["nombre"]) ? trim((string)$entrada["nombre"]) : "";
$gama = isset($entrada["gama"]) ? trim((string)$entrada["gama"]) : "";
$specs = isset($entrada["specs"]) ? trim((string)$entrada["specs"]) : "";
$rendimiento = isset($entrada["rendimiento"]) ? (int)$entrada["rendimiento"] : 0;

if(!in_array($categoria, $categoriasValidas, true)){
    http_response_code(400);
    echo json_encode(["ok" => false, "mensaje" => "Categoría inválida."]);
    exit();
}

if($nombre === "" || $gama === "" || $specs === ""){
    http_response_code(400);
    echo json_encode(["ok" => false, "mensaje" => "Completa todos los campos."]);
    exit();
}

if($rendimiento < 1 || $rendimiento > 10){
    http_response_code(400);
    echo json_encode(["ok" => false, "mensaje" => "El rendimiento debe estar entre 1 y 10."]);
    exit();
}

$datos = leerComponentes($rutaJson);

foreach ($datos[$categoria] as $componente){
    if(isset($componente["nombre"]) && mb_strtolower($componente["nombre"], "UTF-8") === mb_strtolower($nombre, "UTF-8")){
        http_response_code(409);
        echo json_encode(["ok" => false, "mensaje" => "Ese componente ya existe en la categoría seleccionada."]);
        exit();
    }
}

$nuevoComponente = [
    "nombre" => $nombre,
    "rendimiento" => $rendimiento,
    "gama" => $gama,
    "specs" => $specs
];

$datos[$categoria][] = $nuevoComponente;

$resultadoGuardado = file_put_contents(
    $rutaJson,
    json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    LOCK_EX
);

if($resultadoGuardado === false){
    http_response_code(500);
    echo json_encode(["ok" => false, "mensaje" => "No se pudo guardar el componente."]);
    exit();
}

echo json_encode([
    "ok" => true,
    "mensaje" => "Componente guardado correctamente.",
    "componente" => $nuevoComponente,
    "datos" => $datos
], JSON_UNESCAPED_UNICODE);
