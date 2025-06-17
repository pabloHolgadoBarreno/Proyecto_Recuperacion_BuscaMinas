<?php
header('Content-Type: application/json');

// Obtener dificultad o parámetros personalizados
$dificultad = $_POST['dificultad'] ?? 'facil';

switch ($dificultad) {
    case 'facil':
        $filas = 9;
        $columnas = 9;
        $minas = 10;
        break;
    case 'medio':
        $filas = 16;
        $columnas = 16;
        $minas = 40;
        break;
    case 'experto':
        $filas = 16;
        $columnas = 30;
        $minas = 99;
        break;
    case 'custom':
        $filas = intval($_POST['filas'] ?? 9);
        $columnas = intval($_POST['columnas'] ?? 9);
        $minas = intval($_POST['minas'] ?? 10);
        break;
    default:
        echo json_encode(["error" => "Dificultad no válida"]);
        exit;
}

// Validar que el número de minas sea correcto
if ($minas >= $filas * $columnas) {
    echo json_encode(["error" => "Demasiadas minas para el tamaño del mapa"]);
    exit;
}

// Inicializar el mapa vacío
$mapa = array_fill(0, $filas, array_fill(0, $columnas, 0));

// Colocar minas aleatorias
$minas_colocadas = 0;
while ($minas_colocadas < $minas) {
    $x = rand(0, $filas - 1);
    $y = rand(0, $columnas - 1);

    if ($mapa[$x][$y] !== 'M') {
        $mapa[$x][$y] = 'M';
        $minas_colocadas++;
    }
}

// Calcular números alrededor de las minas
for ($i = 0; $i < $filas; $i++) {
    for ($j = 0; $j < $columnas; $j++) {
        if ($mapa[$i][$j] === 'M') continue;

        $contador = 0;
        for ($dx = -1; $dx <= 1; $dx++) {
            for ($dy = -1; $dy <= 1; $dy++) {
                $ni = $i + $dx;
                $nj = $j + $dy;

                if ($ni >= 0 && $ni < $filas && $nj >= 0 && $nj < $columnas && $mapa[$ni][$nj] === 'M') {
                    $contador++;
                }
            }
        }
        $mapa[$i][$j] = $contador;
    }
}

// Devolver el mapa como JSON
echo json_encode([
    "filas" => $filas,
    "columnas" => $columnas,
    "minas" => $minas,
    "mapa" => $mapa
]);
?>

