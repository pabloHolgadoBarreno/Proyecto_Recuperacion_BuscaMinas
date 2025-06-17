<?php
// index.php

require_once 'buscaminas.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    header('Content-Type: application/json');
    $buscaminas = new Buscaminas();

    switch ($_POST['accion']) {
        case 'generar':
            $filas = intval($_POST['filas']);
            $columnas = intval($_POST['columnas']);
            $minas = intval($_POST['minas']);
            $mapa = $buscaminas->generarMapa($filas, $columnas, $minas);
            echo json_encode(['exito' => true, 'mapa' => $mapa]);
            break;

        case 'guardar':
            $nombre = $_POST['nombre'];
            $mapa = json_decode($_POST['mapa'], true);
            $exito = $buscaminas->guardarMapa($nombre, $mapa);
            echo json_encode(['exito' => $exito, 'error' => $exito ? null : 'No se pudo guardar']);
            break;

        case 'cargar':
            $nombre = $_POST['nombre'];
            $mapa = $buscaminas->cargarMapa($nombre);
            if (empty($mapa)) {
                echo json_encode(['exito' => false, 'error' => 'Archivo no encontrado o vacío']);
            } else {
                echo json_encode(['exito' => true, 'mapa' => $mapa]);
            }
            break;

        default:
            echo json_encode(['exito' => false, 'error' => 'Acción desconocida']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Buscaminas - Proyecto</title>
<style>
    textarea { width: 100%; height: 200px; }
    label, input, select, button { margin: 0.3em 0; display: block; }
</style>
</head>
<body>
<h1>Buscaminas - Proyecto</h1>

<form id="formulario">
    <label for="dificultad">Dificultad</label>
    <select id="dificultad" name="dificultad">
        <option value="facil">Fácil (9x9, 10 minas)</option>
        <option value="medio">Medio (16x16, 40 minas)</option>
        <option value="experto">Experto (30x16, 99 minas)</option>
        <option value="custom">Custom</option>
    </select>

    <div id="customParams" style="display:none;">
        <label for="filas">Filas:</label>
        <input type="number" id="filas" name="filas" min="1" max="100" value="9" />

        <label for="columnas">Columnas:</label>
        <input type="number" id="columnas" name="columnas" min="1" max="100" value="9" />

        <label for="minas">Minas:</label>
        <input type="number" id="minas" name="minas" min="1" value="10" />
    </div>

    <button type="button" id="btnGenerar">Generar Mapa</button>
</form>

<h2>Mapa generado (JSON):</h2>
<textarea id="output" readonly></textarea>

<div>
    <input type="text" id="nombreArchivo" placeholder="Nombre archivo (ej: mapa1.json)" />
    <button id="btnGuardar">Guardar Mapa</button>
    <button id="btnCargar">Cargar Mapa</button>
</div>

<script>
document.getElementById('dificultad').addEventListener('change', function() {
    document.getElementById('customParams').style.display = this.value === 'custom' ? 'block' : 'none';
});

document.getElementById('btnGenerar').addEventListener('click', function() {
    const dificultad = document.getElementById('dificultad').value;
    let filas, columnas, minas;

    switch(dificultad) {
        case 'facil':
            filas = 9; columnas = 9; minas = 10;
            break;
        case 'medio':
            filas = 16; columnas = 16; minas = 40;
            break;
        case 'experto':
            filas = 16; columnas = 30; minas = 99;
            break;
        case 'custom':
            filas = parseInt(document.getElementById('filas').value);
            columnas = parseInt(document.getElementById('columnas').value);
            minas = parseInt(document.getElementById('minas').value);
            break;
    }

    fetch('index.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            accion: 'generar',
            filas: filas,
            columnas: columnas,
            minas: minas
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.exito) {
            document.getElementById('output').value = JSON.stringify(data.mapa, null, 2);
        } else {
            alert('Error: ' + data.error);
        }
    });
});

document.getElementById('btnGuardar').addEventListener('click', function() {
    const mapaTexto = document.getElementById('output').value;
    const nombre = document.getElementById('nombreArchivo').value.trim();
    if (!mapaTexto || !nombre) {
        alert("Mapa vacío o nombre de archivo vacío");
        return;
    }
    let mapa;
    try {
        mapa = JSON.parse(mapaTexto);
    } catch {
        alert("JSON inválido");
        return;
    }

    fetch('index.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            accion: 'guardar',
            nombre: nombre,
            mapa: JSON.stringify(mapa)
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.exito) {
            alert("Mapa guardado exitosamente.");
        } else {
            alert("Error guardando: " + data.error);
        }
    });
});

document.getElementById('btnCargar').addEventListener('click', function() {
    const nombre = document.getElementById('nombreArchivo').value.trim();
    if (!nombre) {
        alert("Introduce un nombre de archivo para cargar");
        return;
    }

    fetch('index.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            accion: 'cargar',
            nombre: nombre
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.exito) {
            document.getElementById('output').value = JSON.stringify(data.mapa, null, 2);
        } else {
            alert("Error cargando: " + data.error);
        }
    });
});
</script>

</body>
</html>

