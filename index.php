<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Generador de Buscaminas</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1>Generador de Mapas - Buscaminas</h1>

    <form action="generar.php" method="POST">
        <label for="dificultad">Selecciona dificultad:</label><br>
        <select name="dificultad" id="dificultad" onchange="toggleCustom(this.value)">
            <option value="facil">Fácil (9x9 - 10 minas)</option>
            <option value="medio">Medio (16x16 - 40 minas)</option>
            <option value="experto">Experto (30x16 - 99 minas)</option>
            <option value="custom">Personalizado</option>
        </select><br><br>

        <div id="customOptions" style="display:none;">
            <label>Filas:</label><input type="number" name="filas" min="1"><br>
            <label>Columnas:</label><input type="number" name="columnas" min="1"><br>
            <label>Minas:</label><input type="number" name="minas" min="1"><br>
        </div>

        <br><button type="submit">Generar Mapa</button>
    </form>

    <script>
        function toggleCustom(value) {
            document.getElementById('customOptions').style.display = (value === 'custom') ? 'block' : 'none';
        }
    </script>

</body>
</html>

