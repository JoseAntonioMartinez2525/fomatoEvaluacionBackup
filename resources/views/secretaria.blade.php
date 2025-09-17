@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$formType = request()->query('formType');
$formName = request()->query('formName');
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
use App\Models\DynamicForm; // Ensure to include the model

$forms = DynamicForm::all(); // Fetch all forms from the database
$existingFormNames = [];
@endphp
<!DOCTYPE html>
<html lang="{{ $newLocale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ $logo }}" type="image/png">
    <title>Evaluación docente</title>

    <x-head-resources />
    <style>
        @media print {
            .footer-number::after {
                content: "1";
            }
        }

        .table-responsive {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.375rem 0.75rem;
        }
        .dataAcreditacion{
            font-weight: bold;
        }
        .puntajeValues{
            text-align: right;
        }
        #PrimerValorNumerico{
            text-align: center;
        }

        #puntajeComisionValues, #observacionesNForm{
            background-color: #d6fff7;
            
        }

        body.dark-mode #puntajeComisionValues, body.dark-mode #observacionesNForm{
            color: black;
        }
        /* Botón de modo oscuro */
        .dark-mode-button {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <x-general-header />
        <!-- Botón de modo oscuro (fuera del flujo normal) -->
        <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass dark-mode-button">
            <i class="fa-solid fa-moon"></i>&nbspModo Obscuro
        </button>
    <div class="bg-gray-50 text-black/50">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            @if (Route::has('login'))
                @if (Auth::check() && Auth::user()->user_type === '')
                    <x-nav-menu :user="Auth::user()">
                        <div>
                            <!--Funcionalidad en caso de que se requiera un nuevo formulario
                            <ul style="list-style: none;">
                                <li class="nav-item">
                                    <a class="nav-link active enlaceSN" style="width: 300px;" href="{{route('dynamic_forms')}}"
                                        title="Ingresar nuevo formulario"><i class="fa-solid fa-folder-plus"></i>&nbspIngresar
                                        nuevo</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active enlaceSN" style="width: 300px;"
                                        href="{{route('edit_delete_form')}}" title="Editar ó eliminar formulario"><i
                                            class="fa-solid fa-user-pen"></i>&nbspEditar/Eliminar</a>
                                </li>
                            </ul>
                            -->
                        </div>
                    </x-nav-menu>
                @endif

                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2"></div>

                    <nav class="-mx-3 flex flex-1 justify-end"></nav>

                    <div class="container mt-4 printButtonClass">
                        <!-- Selector para elegir el formulario -->
                        <label for="formSelect">Seleccionar Formulario:</label>
                        <select id="formSelect" class="form-select">
                            <option value=""></option>
                            <option value="form2">1. Permanencia en las actividades de la docencia</option>
                            <option value="form2_2">2. Dedicación en el desempeño docente</option>
                            <option value="form3_1"> 3.1 Participación en actividades de diseño curricular</option>
                            <option value="form3_2"> 3.2 Calidad del desempeño docente evaluada por el alumnado</option>
                            <option value="form3_3"> 3.3 Publicaciones relacionadas con la docencia</option>
                            <option value="form3_4"> 3.4 Distinciones académicas recibidas por el docente</option>
                            <option value="form3_5"> 3.5 Asistencia, puntualidad y permanencia en el desempeño docente,
                                evaluada
                                por el JD y por CAAC</option>
                            <option value="form3_6"> 3.6 Capacitación y actualización pedagógica recibida</option>
                            <option value="form3_7"> 3.7 Cursos de actualización disciplinaria recibidos dentro de su área
                                de
                                conocimiento</option>
                            <option value="form3_8"> 3.8 Impartición de cursos, diplomados, seminarios, talleres
                                extracurriculares, de educación, continua o de formación y capacitación docente</option>
                            <option value="form3_8_1"> 3.8.1 RSU</option>
                            <option value="form3_9"> 3.9 Trabajos dirigidos para la titulación de estudianteso</option>
                            <option value="form3_10"> 3.10 Tutorías a estudiantes</option>
                            <option value="form3_11"> 3.11 Asesoría a estudiantes</option>
                            <option value="form3_12"> 3.12 Publicaciones de investigación relacionadas con el contenido de
                                los
                                PE que imparte el docente</option>
                            <option value="form3_13"> 3.13 Proyectos académicos de investigación</option>
                            <option value="form3_14"> 3.14 Participación como ponente en congresos o eventos académicos del
                                área
                                de conocimiento o afines del docente</option>
                            <option value="form3_15"> 3.15 Registro de patentes y productos de investigación tecnológica y
                                educativa</option>
                            <option value="form3_16"> 3.16 Actividades de arbitraje, revisión, correción y edición</option>
                            <option value="form3_17"> 3.17 Proyectos académicos de extensión y difusión</option>
                            <option value="form3_18"> 3.18 Organización de congresos o eventos institucionales del área de
                                conocimiento del Docente</option>
                            <option value="form3_19"> 3.19 Participación en cuerpos colegiados</option>
                            <!-- Dynamic options -->
                            @foreach($forms as $form)
                                @if(!in_array($form->form_name, $existingFormNames)) <!-- Check for duplicates -->
                                    <option value="{{ $form->form_name }}" data-id="{{ $form->id }}">{{ $form->form_name }}</option>
                                    @php $existingFormNames[] = $form->form_name; @endphp <!-- Add to existing names -->
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div id="dynamicTableContainer" class="mt-4">
                        <!-- Aquí se cargará el contenido del formulario seleccionado -->
                    </div>
                </header>
            @endif
        </div>
    </div>

    <div>
        <footer>
            <div>
                <canvas id="convocatoriaCanvas" width="1500" height="500"></canvas>
            </div>
        </footer>
    </div>

    <script>
        // Funciones de utilidad para cálculos
        function minWithSum(value1, value2) {
            const sum = value1 + value2;
            return Math.min(sum, 200);
        }

        function min40(...values) {
            const sum40 = values.reduce((acc, val) => acc + val, 0);
            return Math.min(sum40, 40);
        }

        function min30(...values) {
            const sum30 = values.reduce((acc, val) => acc + val, 0);
            return Math.min(sum30, 30);
        }

        function subtotal(value1, value2) {
            const st = value1 * value2;
            return st;
        }

        function min60(...values) {
            const sum60 = values.reduce((acc, val) => acc + val, 0);
            return Math.min(sum60, 60);
        }

        function minWithSumThree(value1, value2, value3, value4) {
            const ms = value1 + value2 + value3 + value4;
            return Math.min(ms, 100);
        }

        function min50(...values) {
            const ms = values.reduce((acc, val) => acc + val, 0);
            return Math.min(ms, 50);
        }

        function minWithSumThreeFive(value1, value2) {
            const ms = value1 + value2;
            return Math.min(ms, 75);
        }

        function minTutorias() {
            // convert the arguments object to an array
            const values = Array.from(arguments);
            // use reduce to sum the values
            const ms = values.reduce((acc, current) => {
                return acc + current;
            }, 0);
            // return the minimum of ms and 200
            return Math.min(ms, 200);
        }

        function min700(...values) {
            const ms = values.reduce((acc, val) => acc + val, 0);
            return Math.min(ms, 700);
        }

        // Manejo del formulario dinámico
        document.addEventListener('DOMContentLoaded', (event) => {
            const formSelect = document.getElementById('formSelect');
            console.log('Dropdown Options:', Array.from(formSelect.options).map(option => option.value)); // Log the dropdown options

            formSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const selectedForm = selectedOption.getAttribute('value');
                const selectedFormId = selectedOption.getAttribute('data-id');
                const dynamicTableContainer = document.getElementById('dynamicTableContainer');

                console.log('Selected Form:', selectedForm); // Log the selected form
                console.log('Selected Form ID:', selectedFormId); // Log the selected form ID

                if (selectedForm) {
                    if (selectedForm.startsWith('form')) {
                        // Manejar formularios estáticos
                        window.location.href = `/${selectedForm}`;
                    } else {
                        // Formularios dinámicos
                        fetch(`/get-form-data/${selectedForm}`)
                            .then(response => response.json())
                            .then(data => {
                                console.log('Returned Data:', data);
                                console.log('Columns:', data.columns); // Log the columns
                                console.log('Values:', data.values); // Log the values
                                console.log('Puntaje máximo:', data.puntaje_maximo);
                                console.log('Acreditación:', data.acreditacion); // Log the acreditación

                                if (data.success) {
                                    dynamicTableContainer.innerHTML = '';

                                    // Mostrar el puntaje máximo en la parte superior con fondo negro
                                    let tableHTML = `<div style="margin-bottom: 10px;"><strong>Puntaje máximo</strong> <span style="background-color: #000; color: #fff; font-weight: bold; text-align: center; padding: 2px 10px;">${data.puntaje_maximo}</span></div>`;

                                    // Crear la tabla
                                    tableHTML += '<table class="table table-bordered">';

                                    // Encabezados principales
                                    tableHTML += '<thead><tr>';
                                    tableHTML += '<th>Actividad</th>';

                                    // Agregar los nombres de las columnas dinámicas
                                    const columnNames = data.columns.map(column => column.column_name);

                                    // Filtrar solo subencabezados dinámicos (excluyendo los fijos)
                                    const fixedHeaders = ['Actividad', 'Puntaje a evaluar', 'Puntaje de la Comisión Dictaminadora', 'Observaciones'];
                                    const dynamicColumnNames = data.columns
                                        .map(column => column.column_name)
                                        .filter(name => !fixedHeaders.includes(name));

                                    // Agregar solo las columnas dinámicas (subencabezados)
                                    dynamicColumnNames.forEach(columnName => {
                                        tableHTML += `<th>${columnName}</th>`;
                                    });

                                    tableHTML += '<th>Puntaje a evaluar</th>';
                                    tableHTML += '<th>Puntaje de la Comisión Dictaminadora</th>';
                                    tableHTML += '<th>Observaciones</th>';
                                    tableHTML += '</tr></thead><tbody>';

                                    // Obtener los valores de actividad
                                    const activityColumnId = data.columns.find(col => col.column_name === 'Actividad')?.id;
                                    const activityValues = [];
                                    
                                    if (activityColumnId) {
                                        // Obtener todos los valores de actividad en orden
                                        const activityData = data.values
                                            .filter(val => val.dynamic_form_column_id === activityColumnId)
                                            .sort((a, b) => a.id - b.id); // Ordenar por ID
                                            
                                        activityData.forEach(activity => {
                                            activityValues.push(activity.value);
                                        });
                                    }
                                    
                                    // Si no hay actividades, usar el nombre del formulario como primera actividad
                                    if (activityValues.length === 0) {
                                        activityValues.push(selectedForm);
                                    }
                                    
                                    // Obtener valores para cada columna
                                    const valuesByColumn = {};
                                    data.columns.forEach(column => {
                                        valuesByColumn[column.column_name] = data.values
                                            .filter(val => val.dynamic_form_column_id === column.id)
                                            .sort((a, b) => a.id - b.id); // Ordenar por ID
                                    });
                                    
                                    // Primera fila
                                    tableHTML += '<tr>';
                                    // Primera actividad
                                    tableHTML += `<td>${activityValues[0] || selectedForm}</td>`;
                                    
                                    // Buscar qué valores corresponden a cada columna dinámica en la primera fila
                                    dynamicColumnNames.forEach(colName => {
                                        const colValues = valuesByColumn[colName] || [];
                                        const firstValue = colValues.length > 0 ? colValues[0].value : '';
                                        tableHTML += `<td id="celdaVacia"></td>`;
                                    });
                                    
                                    // Buscar valores para puntajes y observaciones
                                    const puntajeEvalValues = valuesByColumn['Puntaje a evaluar'] || [];
                                    const puntajeComisionValues = valuesByColumn['Puntaje de la Comisión Dictaminadora'] || [];
                                    const observacionesValues = valuesByColumn['Observaciones'] || [];
                                    
                                    // Primera fila - puntajes y observaciones
                                    tableHTML += `<td style="background-color: #0b5967; color: #ffff; text-align:center; font-weight:bold;">${puntajeEvalValues.length > 0 ? puntajeEvalValues[0].value : '0'}</td>`;
                                    tableHTML += `<td style="background-color: #ffcc6d; text-align:center;font-weight:bold;">${puntajeComisionValues.length > 0 ? puntajeComisionValues[0].value : '0'}</td>`;
                                    tableHTML += `<td>${observacionesValues.length > 0 ? observacionesValues[0].value : ''}</td>`;
                                    tableHTML += '</tr>';
                                    
                                    // Segunda fila - Si no tenemos una segunda actividad o valor no numérico, lo buscamos
                                    if (activityValues.length < 2) {
                                        fetch(`/get-first-non-numeric-value/${selectedFormId}`)
                                            .then(response => response.json())
                                            .then(secondData => {
                                                if (secondData.success && secondData.value) {
                                                    const secondActivity = secondData.value;
                                                    addSecondRow(secondActivity);
                                                }
                                                finalizarTabla();
                                            })
                                            .catch(error => {
                                                console.error('Error al obtener segunda actividad:', error);
                                                finalizarTabla();
                                            });
                                    } else {
                                        // Ya tenemos una segunda actividad, podemos usarla directamente
                                        addSecondRow(activityValues[1]);
                                        finalizarTabla();
                                    }
                                    
                                    function addSecondRow(secondActivity) {
                                        // Agregar segunda fila con el segundo valor de actividad
                                        tableHTML += '<tr>';
                                        tableHTML += `<td id="inicioActividad">${secondActivity}</td>`;
                                        
                                        // Valores para columnas dinámicas en segunda fila
                                        dynamicColumnNames.forEach(colName => {
                                            const colValues = valuesByColumn[colName] || [];
                                            const secondValue = colValues.length > 1 ? colValues[1].value : '';
                                            tableHTML += `<td id="PrimerValorNumerico">${secondValue}</td>`;
                                        });
                                        
                                        // Segunda fila - puntajes y observaciones
                                        tableHTML += `<td class="puntajeValues">${puntajeEvalValues.length > 1 ? puntajeEvalValues[1].value : '0'}</td>`;
                                        tableHTML += `<td id="puntajeComisionValues" class="puntajeValues">${puntajeComisionValues.length > 1 ? puntajeComisionValues[1].value : '0'}</td>`;
                                        tableHTML += `<td id="observacionesNForm"></td>`;
                                        tableHTML += '</tr>';
                                    }
                                    
                                    function finalizarTabla() {
                                        // Agregar fila de acreditación
                                        tableHTML += '<tr>';
                                        tableHTML += '<td>Acreditación:</td>';
                                        
                                        // Colspan para las columnas restantes
                                        const totalColumns = dynamicColumnNames.length + 3; // +3 por puntajes y observaciones
                                        tableHTML += `<td class="dataAcreditacion" colspan="${totalColumns}">${data.acreditacion || ''}</td>`;
                                        tableHTML += '</tr>';
                                        
                                        tableHTML += '</tbody></table>';
                                        dynamicTableContainer.innerHTML = tableHTML;
                                    }
                                } else {
                                    dynamicTableContainer.innerHTML = '<p class="alert alert-danger">Error al cargar el formulario: ' + (data.message || 'Formulario no encontrado') + '</p>';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                dynamicTableContainer.innerHTML = '<p class="alert alert-danger">Error: ' + error.message + '</p>';
                            });
                    }
                } else {
                    dynamicTableContainer.innerHTML = '';
                }
            });
        });

        // Mantener las funciones existentes que podrían ser utilizadas en otras partes
        function onChange() {
            // Obtener los valores de los inputs
            const puntajePosgrado = parseFloat(document.getElementById("horasPosgrado").value);
            const puntajeSemestre = parseFloat(document.getElementById("horasSemestre").value);
            const h = parseFloat(document.querySelector('#hoursText'));

            // Realizar los cálculos
            const dsePosgrado = puntajePosgrado * 8.5;
            const dseSemestre = puntajeSemestre * 8.5;
            const hora = (dsePosgrado + dseSemestre);

            // Actualizar el contenido de las etiquetas <label>
            document.getElementById("DSE").innerText = dsePosgrado;
            document.getElementById("DSE2").innerText = dseSemestre;

            // Mostrar los valores actualizados en la consola
            console.log(dsePosgrado);
            console.log(dseSemestre);

            const minimo = minWithSum(dsePosgrado, dseSemestre);

            document.getElementById("hoursText").innerText = minimo;
            console.log(minimo);
        }

        function hayObservacion(indiceActividad) {
            var selectEscala = document.getElementById('selectEscala' + indiceActividad);
            var selectActividad = document.getElementById('selectActividad' + indiceActividad);
            var inputObservacion = document.getElementById('observacion' + indiceActividad);
            var mensajeObservacion = document.getElementById('mensajeObservacion' + indiceActividad);

            if (selectActividad.value != 0 && selectEscala.value != 0) {
                mensajeObservacion.textContent = 'Observación: ' + inputObservacion.value;
                mensajeObservacion.style.display = 'block';
                return true;
            } else {
                mensajeObservacion.style.display = 'none';
                return false;
            }
        }

        const nav = document.querySelector('nav');
        let lastScrollLeft = 0; // Variable to store the last horizontal scroll position

        window.addEventListener('scroll', () => {
            let currentScrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

            // Check if scrolling to the right
            if (currentScrollLeft > lastScrollLeft) {
                // Scrolling to the right, hide the navigation
                nav.style.display = 'none';
            } else {
                // Scrolling to the left or not horizontally, show the navigation
                nav.style.display = 'block';
            }

            lastScrollLeft = currentScrollLeft <= 0 ? 0 : currentScrollLeft; // For Mobile or negative scrolling
        });

    document.addEventListener('DOMContentLoaded', function () {
            const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
            if (toggleDarkModeButton) {
                const widthDarkButton = window.outerWidth - 230;
                toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
            }

            toggleDarkMode();
        });
    </script>
</body>
</html>