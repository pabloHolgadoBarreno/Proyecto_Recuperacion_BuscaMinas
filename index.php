<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Generador de Buscaminas</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1>Generador de Mapas - Buscaminas</h1>

    <form id="formDificultad">
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

    <h2>Mapa generado (JSON):</h2>
    <pre id="resultado"></pre>

    <button id="guardarBtn" style="display:none;">Guardar Mapa</button>
    <button id="cargarBtn">Cargar Mapa</button>

    <div id="mapaCargado" style="white-space: pre-wrap; margin-top: 20px;"></div>

    <script>
        function toggleCustom(value) {
            document.getElementById('customOptions').style.display = (value === 'custom') ? 'block' : 'none';
        }

        const form = document.getElementById('formDificultad');
        const resultado = document.getElementById('resultado');
        const guardarBtn = document.getElementById('guardarBtn');
        const cargarBtn = document.getElementById('cargarBtn');
        const mapaCargado = document.getElementById('mapaCargado');

        let mapaActual = null; // Para guardar el mapa generado

        form.addEventListener('submit', function(e){
            e.preventDefault();

            const formData = new FormData(form);
            fetch('generar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.error) {
                    resultado.textContent = 'Error: ' + data.error;
                    guardarBtn.style.display = 'none';
                    return;
                }
                mapaActual = data;
                resultado.textContent = JSON.stringify(data, null, 2);
                guardarBtn.style.display = 'inline-block';
                mapaCargado.textContent = '';
            })
            .catch(err => {
                resultado.textContent = 'Error en la conexión';
                guardarBtn.style.display = 'none';
            });
        });

        guardarBtn.addEventListener('click', function(){
            if (!mapaActual) return alert('No hay mapa generado para guardar.');

            fetch('guardar.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(mapaActual)
            })
            .then(response => response.json())
            .then(data => {
                if(data.error) {
                    alert('Error al guardar: ' + data.error);
                } else {
                    alert('Mapa guardado: ' + data.archivo);
                }
            })
            .catch(() => alert('Error al guardar el mapa.'));
        });

        cargarBtn.addEventListener('click', function(){
            const nombreArchivo = prompt('Introduce el nombre del archivo de mapa para cargar (ejemplo: mapa_20250617_123456.json)');
            if (!nombreArchivo) return;

            fetch('mapas/' + nombreArchivo)
            .then(response => {
                if(!response.ok) throw new Error('Archivo no encontrado');
                return response.json();
            })
            .then(data => {
                mapaCargado.textContent = 'Mapa cargado:\n' + JSON.stringify(data, null, 2);
                resultado.textContent = '';
                guardarBtn.style.display = 'none';
                mapaActual = null;
            })
            .catch(err => {
                alert('Error al cargar: ' + err.message);
            });
        });
    </script>
</body>
</html>

