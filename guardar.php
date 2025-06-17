<?php
header('Content-Type: application/json');

// Ruta donde guardar los mapas
$directorio = __DIR__ . '/mapas/';

// Verificar que el directorio existe
if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

// Obtener contenido JSON del cuerpo de la petición
$datos_json = file_get_contents('php://input');

// Intentar decodificar JSON
$mapa = json_decode($datos_json, true);

if (!$mapa || !isset($mapa['mapa'])) {
    echo json_encode(['error' => 'Datos inválidos o mal formateados']);
    exit;
}

// Generar nombre de archivo único
$nombreArchivo = 'mapa_' . date('Ymd_His') . '.json';

// Guardar el archivo
file_put_contents($directorio . $nombreArchivo, json_encode($mapa, JSON_PRETTY_PRINT));

// Devolver confirmación
echo json_encode([
    'mensaje' => 'Mapa guardado correctamente',
    'archivo' => $nombreArchivo
]);
?>

