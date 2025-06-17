<?php
// buscaminas.php

class Buscaminas {
    public function generarMapa(int $filas, int $columnas, int $minas): array {
        $mapa = array_fill(0, $filas, array_fill(0, $columnas, 0));
        $minasColocadas = 0;
        while ($minasColocadas < $minas) {
            $f = rand(0, $filas - 1);
            $c = rand(0, $columnas - 1);
            if ($mapa[$f][$c] !== 9) {
                $mapa[$f][$c] = 9;
                $minasColocadas++;
            }
        }
        return $mapa;
    }

    public function guardarMapa(string $nombreArchivo, array $mapa): bool {
        return file_put_contents($nombreArchivo, json_encode($mapa)) !== false;
    }

    public function cargarMapa(string $nombreArchivo): array {
        if (!file_exists($nombreArchivo)) return [];
        $contenido = file_get_contents($nombreArchivo);
        return json_decode($contenido, true) ?: [];
    }
}

