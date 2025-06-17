<?php
// tests/BuscaminasTest.php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Buscaminas.php';

class BuscaMinasTests extends TestCase {

    private $buscaminas;

    protected function setUp(): void {
        $this->buscaminas = new Buscaminas();
    }

    public function testGenerarMapa() {
        $filas = 5;
        $columnas = 5;
        $minas = 5;
        $mapa = $this->buscaminas->generarMapa($filas, $columnas, $minas);

        $this->assertCount($filas, $mapa);
        $this->assertCount($columnas, $mapa[0]);

        // Contar minas (9)
        $minasContadas = 0;
        foreach ($mapa as $fila) {
            foreach ($fila as $celda) {
                if ($celda === 9) $minasContadas++;
            }
        }

        $this->assertEquals($minas, $minasContadas);
    }

    public function testGuardarYCargarMapa() {
        $mapa = [
            [0, 9],
            [9, 0]
        ];
        $archivo = __DIR__ . '/testmapa.json';

        $exitoGuardar = $this->buscaminas->guardarMapa($archivo, $mapa);
        $this->assertTrue($exitoGuardar);

        $mapaCargado = $this->buscaminas->cargarMapa($archivo);
        $this->assertEquals($mapa, $mapaCargado);

        // Limpieza
        if (file_exists($archivo)) {
            unlink($archivo);
        }
    }

    public function testCargarMapaNoExistente() {
        $mapa = $this->buscaminas->cargarMapa('archivo_que_no_existe.json');
        $this->assertEquals([], $mapa);
    }
}

