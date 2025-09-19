@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$formType = request()->query('formType');
$formName = request()->query('formName');
use App\Models\DynamicForm; // Ensure to include the model

$forms = DynamicForm::all(); // Fetch all forms from the database
$existingFormNames = [];
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
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
        #formContainer{
            margin-left:200px;
            margin-top:100px;
        }

        #inputValues{
            text-align: right;
            width: max-content;
            border: none;
            outline: none;
        }

        #inputObservaciones{
            width: max-content;
            border: none;
            outline: none;    
        }
        #puntajeComisionValues, #observacionesNForm, #inputValues, #observacionesNForm input {
            background-color: #d6fff7;
            
        }

        body.dark-mode #puntajeComisionValues, body.dark-mode #observacionesNForm, body.dark-mode #inputValues , body.dark-mode #observacionesNForm input {
            color: black;
        }

        body.dark-mode #inputValues , body.dark-mode #observacionesNForm input {
            border:none;
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

    @auth
        @if(Auth::user()->user_type === 'dictaminador')
            <x-nav-menu :user="Auth::user()"/>
            <x-general-header />
            <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass" style="margin-left: 100px;"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

            <div class="container mt-4">
                <!-- Selector para elegir el formulario -->
                <label for="formSelect">Buscar Evaluación:</label>

                   <div id="formSelect" class="container">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form2">1. Permanencia en las actividades de la docencia</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form2_2">2. Dedicación en el desempeño docente</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_1">3.1 Participación en actividades de diseño curricular</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_2">3.2 Calidad del desempeño docente evaluada por el alumnado</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_3">3.3 Publicaciones relacionadas con la docencia</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_4">3.4 Distinciones académicas recibidas por el docente</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_5">3.5 Asistencia, puntualidad y permanencia en el desempeño docente</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_6">3.6 Capacitación y actualización pedagógica recibida</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_7">3.7 Cursos de actualización disciplinaria</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_8">3.8 Impartición de cursos, diplomados, seminarios</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_8_1">3.8.1 RSU</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_9">3.9 Trabajos dirigidos para la titulación</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_10">3.10 Tutorías a estudiantes</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_11">3.11 Asesoría a estudiantes</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_12">3.12 Publicaciones de investigación</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_13">3.13 Proyectos académicos de investigación</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_14">3.14 Participación como ponente en congresos</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_15">3.15 Registro de patentes y productos de investigación</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_16">3.16 Actividades de arbitraje y edición</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_17">3.17 Proyectos académicos de extensión</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_18">3.18 Organización de congresos institucionales</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 mb-2">
                            <button class="btn btn-outline-primary w-100" value="form3_19">3.19 Participación en cuerpos colegiados</button>
                            </div>
                        </div>
                        </div>


                {{-- <select id="formSelect" class="form-select">
                    <option value=""></option>
                    <option value="form2">1. Permanencia en las actividades de la docencia</option>
                    <option value="form2_2">2. Dedicación en el desempeño docente</option>
                    <option value="form3_1">  3.1 Participación en actividades de diseño curricular</option>
                    <option value="form3_2">  3.2 Calidad del desempeño docente evaluada por el alumnado</option>
                    <option value="form3_3">  3.3 Publicaciones relacionadas con la docencia</option>
                    <option value="form3_4">  3.4 Distinciones académicas recibidas por el docente</option>
                    <option value="form3_5">  3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD y por CAAC</option>
                    <option value="form3_6">  3.6 Capacitación y actualización pedagógica recibida</option>
                    <option value="form3_7">  3.7 Cursos de actualización disciplinaria recibidos dentro de su área de conocimiento</option>
                    <option value="form3_8">  3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de educación, continua o de formación y
                    capacitación docente</option>
                    <option value="form3_8_1"> 3.8.1 RSU</option>
                    <option value="form3_9">  3.9 Trabajos dirigidos para la titulación de estudiantes</option>
                    <option value="form3_10"> 3.10 Tutorías a estudiantes</option>
                    <option value="form3_11"> 3.11 Asesoría a estudiantes</option>
                    <option value="form3_12"> 3.12 Publicaciones de investigación relacionadas con el contenido de los PE que imparte el docente</option>
                    <option value="form3_13"> 3.13 Proyectos académicos de investigación</option>
                    <option value="form3_14"> 3.14 Participación como ponente en congresos o eventos académicos del Área de Conocimiento o afines del docente</option>
                    <option value="form3_15"> 3.15 Registro de patentes y productos de investigación tecnológica y educativa</option>
                    <option value="form3_16"> 3.16 Actividades de arbitraje, revisión, correción y edición</option>
                    <option value="form3_17"> 3.17 Proyectos académicos de extensión y difusión</option>
                    <option value="form3_18"> 3.18 Organización de congresos o eventos institucionales del área de conocimiento del Docente</option>
                    <option value="form3_19"> 3.19 Participación en cuerpos colegiados</option> --}}

                    <!-- Dynamic options -->
                    {{-- @foreach($forms as $form)
                        @if(!in_array($form->form_name, $existingFormNames)) <!-- Check for duplicates -->
                            <option value="{{ $form->form_name }}" data-id="{{ $form->id }}">{{ $form->form_name }}</option>
                            @php $existingFormNames[] = $form->form_name; @endphp <!-- Add to existing names -->
                        @endif
                    @endforeach --}}

                {{-- </select> --}}
                <!--Funcionalidad en caso de nuevos formularios-->
                {{-- <div class="container mt-3" style="display: none;">
                    <label for="teacherSelect">Seleccionar Docente:</label>
                    <select id="teacherSelect" class="form-select">
                        <option value="">Seleccionar un docente</option>
                    </select>
                </div> --}}
                
            </div>

            <div id="formContainer">
                <!-- Aquí se cargará el contenido del formulario seleccionado -->
                </div>

        @else
            <p>No tienes permisos para ver esta página.</p>
        @endif
    @else
        <p>Por favor, inicia sesión.</p>
    @endauth
        </main>

        <div>

            <footer>
                <div>

                    <canvas id="convocatoriaCanvas" width="1500" height="500"></canvas>
                </div>
            </footer>

        </div>
        
        
    </div>
    </div>
    </div>

    <script>

        const convocatoria = document.querySelector('nav a').textContent.trim();

        //const actv2Comision = document.querySelector('#actv2ComisionText');

        function onload() {
            // Setup some event handlers. 
            var buttons = document.getElementsByClassName('button');
            for (var i = 0; i < buttons.length; i++) { buttons[i].addEventListener('click', handleClick); }

        }

        function handleClick(event) {
            var currentTarget = event.currentTarget;
            // Use the event data here. 
            console.log('Button clicked: ' + currentTarget.getAttribute('data-id'));
        } document.addEventListener('DOMContentLoaded', onload);
        


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



         // Manejo del formulario dinámico
    // Modificación para cargar datos de docentes y sus evaluaciones en comision_dictaminadora.blade.php

    // Aquí agregaremos la función para seleccionar docentes
document.addEventListener('DOMContentLoaded', () => {
    const formSelect = document.getElementById('formSelect');
    const formContainer = document.getElementById('formContainer');
    const docenteSelect = document.getElementById('teacherSelect');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Cargar la lista de docentes 
    fetch('/get-docentes')
        .then(response => response.json())
        .then(docentes => {
            // Limpiar opciones existentes
            docenteSelect.innerHTML = '<option value="">Seleccionar un docente</option>';
            
            // CORREGIDO: Solo mostrar el email del docente como texto de la opción
            docentes.forEach(docente => {
                const option = document.createElement('option');
                option.value = docente.email;
                option.textContent = docente.email; // Solo el email, sin mostrar "undefined"
                docenteSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar los docentes:', error);
        });

    // Evento al cambiar docente
    docenteSelect.addEventListener('change', function() {
        const selectedDocente = this.value;
        const selectedForm = formSelect.value;
        const selectedFormId = formSelect.options[formSelect.selectedIndex].getAttribute('data-id');
        
        if (selectedDocente && selectedForm) {
            if (selectedForm.startsWith('form')) {
                // Para formularios estáticos
                window.location.href = `/${selectedForm}?teacher=${selectedDocente}`;
            } else {
                // Para formularios dinámicos
                cargarFormularioConDatosDocente(selectedForm, selectedFormId, selectedDocente);
            }
        }
    });

    // Modificar el evento change del selector de formulario
    formSelect.addEventListener('change', function() {
        const selectedForm = this.value;
        const selectedFormId = this.options[this.selectedIndex].getAttribute('data-id');
        const selectedDocente = docenteSelect ? docenteSelect.value : null;
        
        if (selectedForm && selectedDocente) {
            if (selectedForm.startsWith('form')) {
                window.location.href = `/formato-evaluacion/${selectedForm}?teacher=${selectedDocente}`;
            } else {
                cargarFormularioConDatosDocente(selectedForm, selectedFormId, selectedDocente);
            }
        } else if (selectedForm) {
            if (selectedForm.startsWith('form')) {
                window.location.href = `/formato-evaluacion/${selectedForm}`;
            } else {
                cargarFormularioDinamico(selectedForm, selectedFormId);
            }
        } else {
            formContainer.innerHTML = '';
        }
    });

    // Función para cargar formulario dinámico (sin datos de docente)
    function cargarFormularioDinamico(formName, formId) {
        formContainer.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div><p>Cargando formulario...</p></div>';
        
        fetch(`/get-form-data/${formName}`)
            .then(response => response.json())
            .then(data => {
                console.log('Datos del formulario:', data);
                
                if (data.success) {
                    formContainer.innerHTML = '';
                    renderizarTablaOriginal(data, formName, formId);
                } else {
                    formContainer.innerHTML = `<div class="alert alert-danger">Error: ${data.message || 'No se pudieron cargar los datos'}</div>`;
                }
            })
            .catch(error => {
                console.error('Error al cargar el formulario:', error);
                formContainer.innerHTML = `<div class="alert alert-danger">Error al cargar el formulario: ${error.message}</div>`;
            });
    }
    
    // Función para cargar formulario con datos del docente
    function cargarFormularioConDatosDocente(formName, formId, docenteEmail) {
        formContainer.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div><p>Cargando datos del docente...</p></div>';
        
        fetch(`/get-teacher-form-data/${docenteEmail}/${formName}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('No se pudo obtener los datos del docente para este formulario');
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos del formulario para el docente:', data);
                
                if (data.success) {
                    formContainer.innerHTML = '';
                    renderizarTablaOriginal(data, formName, formId, docenteEmail, data.commission_data);
                } else {
                    formContainer.innerHTML = `<div class="alert alert-danger">Error: ${data.message || 'No se pudieron cargar los datos del docente'}</div>`;
                }
            })
            .catch(error => {
                console.error('Error al cargar datos del docente:', error);
                
                // Si falla, cargar el formulario sin datos del docente
                cargarFormularioDinamico(formName, formId);
            });
    }
    
    // Función para renderizar la tabla con el formato original
    function renderizarTablaOriginal(data, formName, formId, docenteEmail = null, commissionData = []) {
        // Crear el encabezado con puntaje máximo
        let tableHTML = `<form id="${formId}" method="POST">`;
        tableHTML = `<div style="margin-bottom: 10px;"><strong>Puntaje máximo</strong> <span style="background-color: #000; color: #fff; font-weight: bold; text-align: center; padding: 2px 10px;">${data.puntaje_maximo}</span></div>`;
        
        // Si hay datos del docente, mostrarlos
        if (docenteEmail && data.teacher) {
            tableHTML += `<div class="alert alert-info mb-3">
                <p><strong>Docente:</strong> ${data.teacher.name || ''}</p>
                <p><strong>Email:</strong> ${data.teacher.email || ''}</p>
            </div>`;
        }
        
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
            activityValues.push(formName);
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
        tableHTML += `<td>${activityValues[0] || formName}</td>`;
        
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
        
        // Buscar datos de comisión para esta fila
        const primeraFilaComision = commissionData ? commissionData.find(c => 
            c.row_identifier === 'row_1' || c.row_identifier === activityValues[0]
        ) : null;
        
        tableHTML += `<td style="background-color: #ffcc6d; text-align:center;font-weight:bold;">${primeraFilaComision ? primeraFilaComision.puntaje_comision : (puntajeComisionValues.length > 0 ? puntajeComisionValues[0].value : '0')}</td>`;
        tableHTML += `<td>${primeraFilaComision ? primeraFilaComision.observaciones : (observacionesValues.length > 0 ? observacionesValues[0].value : '')}</td>`;
        tableHTML += '</tr>';
        
        // Segunda fila - Si no tenemos una segunda actividad o valor no numérico, lo buscamos
        if (activityValues.length < 2) {
            // Agregar segunda fila con un placeholder
            const secondActivity = "Actividad adicional";
            addSecondRow(secondActivity);
            finalizarTabla();
        } else {
            // Ya tenemos una segunda actividad, podemos usarla directamente
            addSecondRow(activityValues[1]);
            finalizarTabla();
        }
        
        function addSecondRow(secondActivity) {
            // Determina el row_identifier para la segunda fila
            const rowId = `row_2`;
            
            // Buscar datos de comisión para esta fila
            const segundaFilaComision = commissionData ? commissionData.find(c => 
                c.row_identifier === rowId || c.row_identifier === secondActivity
            ) : null;
            
            // CORREGIDO: Obtener el valor de puntaje_input_values si existe
            const puntajeInputValue = segundaFilaComision ? segundaFilaComision.puntaje_input_values || '' : '';
            
            // Agregar segunda fila con el segundo valor de actividad
            tableHTML += '<tr data-row-id="' + rowId + '">';
            tableHTML += `<td id="inicioActividad">${secondActivity}</td>`;
            
            // Valores para columnas dinámicas en segunda fila
            dynamicColumnNames.forEach(colName => {
                const colValues = valuesByColumn[colName] || [];
                const secondValue = colValues.length > 1 ? colValues[1].value : '';
                tableHTML += `<td id="PrimerValorNumerico">${secondValue}</td>`;
            });
            
            // Segunda fila - puntajes y observaciones
            tableHTML += `<td class="puntajeValues">${puntajeEvalValues.length > 1 ? puntajeEvalValues[1].value : '0'}</td>`;
            
            // CORREGIDO: Agregar el campo hidden con el valor de puntaje_input_values
            tableHTML += `<td id="puntajeComisionValues" class="puntajeValues">
                <input id="inputValues" 
                       type="number" 
                       class="puntaje-comision" 
                       data-row-id="${rowId}" 
                       value="${segundaFilaComision ? segundaFilaComision.puntaje_comision : ''}" 
                       placeholder="0">
                <input type="hidden" id="puntaje_input_values" name="puntaje_input_values" value="${puntajeInputValue}">
            </td>`;
            
            tableHTML += `<td id="observacionesNForm">
                <input id="inputObservaciones" 
                       class="observaciones" 
                       data-row-id="${rowId}" 
                       value="${segundaFilaComision ? segundaFilaComision.observaciones : ''}" 
                       placeholder="escriba aqui los comentarios">
            </td>`;
            
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
            
            // CORREGIDO: Estilo del botón para que sea como los demás botones de la app
            if (docenteEmail) {
                tableHTML += `<button type="submit" class="btn custom-btn printButtonClass dynamicBtn" id="btnGuardarEvaluacion">Enviar</button>`;
            } else {
                tableHTML += `<div class="alert alert-warning mt-3">
                    <p>Para evaluar este formulario, seleccione un docente primero.</p>
                </div>`;
            }
            
            tableHTML += '</form>';
            formContainer.innerHTML = tableHTML;
            
            // Agregar evento al botón de guardar
            const btnGuardar = document.getElementById('btnGuardarEvaluacion');
            if (btnGuardar) {
                btnGuardar.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevenir el envío del formulario
                    guardarDatosComision(formId, docenteEmail);
                });
            }
        }
    }
    
    // Función para guardar los datos de comisión
    function guardarDatosComision(formId, docenteEmail) {
        // Obtener el token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Obtener los valores de los campos
        const puntajeInput = document.getElementById('inputValues');
        const observacionesInput = document.getElementById('inputObservaciones');
        const puntajeInputValues = document.getElementById('puntaje_input_values');
        
        if (!puntajeInput || !observacionesInput) {
            alert('Error: No se encontraron los campos para guardar');
            return;
        }
        
        // Preparar datos para enviar
        const formData = {
            rows: [
                {
                    row_identifier: 'row_2',
                    puntaje_comision: puntajeInput.value,
                    puntaje_input_values: puntajeInputValues ? puntajeInputValues.value : '',
                    observaciones: observacionesInput.value
                }
            ],
            user_id: {{ Auth::id() }},
            email: docenteEmail,
            user_type: 'dictaminador'
        };
        
        // Enviar datos al servidor
        fetch(`/update-commission-data/${formId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Datos guardados correctamente');
                // Recargar los datos del formulario
                const formName = document.getElementById('formSelect').value;
                cargarFormularioConDatosDocente(formName, formId, docenteEmail);
            } else {
                alert(`Error: ${data.message || 'No se pudieron guardar los datos'}`);
            }
        })
        .catch(error => {
            console.error('Error al guardar datos:', error);
            alert('Error al guardar los datos');
        });
    }
});


document.addEventListener('DOMContentLoaded', function () {
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            async function submitForm(url, formId) {
                // Get form data
                let formData = {};
                let gridOptions = {};
                let form = document.getElementById(formId);
                // Ensure the form element exists
                if (!form) {
                    console.error(`Form with id "${formId}" not found.`);
                    return;
                }

                //Recoge los datos dependiendo del formulario actual
                formData['user_id'] = form.querySelector('input[name="user_id"]').value;
                formData['email'] = form.querySelector('input[name="email"]').value;
                formData['user_type'] = form.querySelector('input[name="user_type"]').value;
                switch (formId) {


                    case 'form2':

                        formData['puntajeEvaluar'] = form.querySelector('input[name="puntajeEvaluar"]').value;
                        formData['horasActv2'] = form.querySelector('span[id=horasActv2]').textContent;
                        formData['puntajeEvaluarText'] = form.querySelector('span[id=puntajeEvaluarText]').textContent;
                        formData['comision1'] = form.querySelector('input[name="comision1"]').value;
                        formData['obs1'] = form.querySelector('input[name="obs1"]').value;
                        break;

                    case 'form2_2':

                        formData['hours'] = document.querySelector('label[id=hoursText]').textContent;
                       formData['horasPosgrado'] = form.querySelector('span[id="horasPosgrado]').textContent;
                       formData['horasSemestre'] = form.querySelector('span[id="horasSemestre]').textContent;
                       formData['dse'] = form.querySelector('span[id="dse]').textContent;
                       formData['dse2'] = form.querySelector('span[id="dse2]').textContent;
                        formData['comisionPosgrado'] = form.querySelector('input[name="comisionPosgrado"]').value;
                        formData['comisionLic'] = form.querySelector('input[name="comisionLic"]').value;

                        
                        let actv2ComisionLabel = form.querySelector('td[id="actv2Comision"]');

                        if (!actv2ComisionLabel) {
                            console.error('Label with id "actv2Comision" not found.');
                        } else {
                            formData['actv2Comision'] = actv2ComisionLabel.innerText;
                        }
                        formData['obs2'] = form.querySelector('input[name="obs2"]').value;
                        formData['obs2_2'] = form.querySelector('input[name="obs2_2"]').value; 
                        break;

                    case 'form3_1':

                        formData['elaboracion'] = document.getElementById('elaboracion').textContent;
                        formData['elaboracionSubTotal1'] = document.getElementById('elaboracionSubTotal1').textContent;
                        formData['comisionIncisoA'] = document.getElementById('comisionIncisoA').value;
                        formData['elaboracion2'] = document.getElementById('elaboracion2').textContent;
                        formData['elaboracionSubTotal2'] = document.getElementById('elaboracionSubTotal2').textContent;
                        formData['comisionIncisoB'] = document.getElementById('comisionIncisoB').value;
                        formData['elaboracion3'] = document.getElementById('elaboracion3').textContent;
                        formData['elaboracionSubTotal3'] = document.getElementById('elaboracionSubTotal3').textContent;
                        formData['comisionIncisoC'] = document.getElementById('comisionIncisoC').value;
                        formData['elaboracion4'] = document.getElementById('elaboracion4').textContent;
                        formData['elaboracionSubTotal4'] = document.getElementById('elaboracionSubTotal4').textContent;
                        formData['comisionIncisoD'] = document.getElementById('comisionIncisoD').value;
                        formData['elaboracion5'] = document.getElementById('elaboracion5').textContent;
                        formData['elaboracionSubTotal5'] = document.getElementById('elaboracionSubTotal5');
                        formData['comisionIncisoE'] = document.getElementById('comisionIncisoE').value;                             
                        formData['score3_1'] = document.getElementById('score3_1').textContent;
                        formData['actv3Comision'] = document.getElementById('actv3Comision').textContent;

                        formData['obs3_1_1'] = form.querySelector('input[name="obs3_1_1"]').value;
                        formData['obs3_1_2'] = form.querySelector('input[name="obs3_1_2"]').value;
                        formData['obs3_1_3'] = form.querySelector('input[name="obs3_1_3"]').value;
                        formData['obs3_1_4'] = form.querySelector('input[name="obs3_1_4"]').value;
                        formData['obs3_1_5'] = form.querySelector('input[name="obs3_1_5"]').value;
                        break;

                    case 'form3_2':
                        formData['score3_1'] = document.getElementById('score3_2').textContent;
                        formData['comision3_2'] = document.getElementById('comision3_2').textContent;
                        formData['prom90_100'] = document.getElementById('prom90_100').textContent;
                        formData['prom80_90'] = document.getElementById('prom80_90').textContent;
                        formData['prom70_80'] = document.getElementById('prom70_80').textContent;                     
                        formData['r1'] = document.getElementById('r1').value;
                        formData['r2'] = document.getElementById('r2').value;
                        formData['r3'] = document.getElementById('r3').value;
                        formData['cant1'] = document.getElementById('cant1').textContent;
                        formData['cant2'] = document.getElementById('cant2').textContent;
                        formData['cant3'] = document.getElementById('cant3').textContent;
                        formData['obs3_2_1'] = form.querySelector('input[name="obs3_2_1"]').value;
                        formData['obs3_2_2'] = form.querySelector('input[name="obs3_2_2"]').value;
                        formData['obs3_2_3'] = form.querySelector('input[name="obs3_2_3"]').value;
                }
                console.log('Form data:', formData);


                try {
                    let response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    let data = await response.json();
                    console.log('Response received from server:', data);
                    
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                }
            }

            window.submitForm = submitForm;
        });

        document.addEventListener('DOMContentLoaded', function () {
            const userEmail = "{{ Auth::user()->email }}"; // Obtén el email del usuario desde Blade

            const allowedEmails = [
                'joma_18@alu.uabcs.mx',
                'oa.campillo@uabcs.mx',
                'rluna@uabcs.mx',
                'v.andrade@uabcs.mx'
            ];

            // Verifica si el email está en la lista de correos permitidos
            if (allowedEmails.includes(userEmail)) {
                // Muestra el enlace
                document.getElementById('jsonDataLink').classList.remove('d-none');
            }
        });

        // Función para agregar un nuevo formulario al select, manteniendo el orden
            function addFormOption(newFormId, newFormLabel) {
                const selectElement = document.getElementById('formSelect');
                const newOption = document.createElement('option');
                newOption.value = newFormId;
                newOption.textContent = newFormLabel;

                // Encontrar la posición correcta para insertar el nuevo formulario
                const existingOptions = Array.from(selectElement.options);
                let insertBeforeOption = null;

                // Recorre las opciones para encontrar la correcta en la lista
                for (let i = 0; i < existingOptions.length; i++) {
                    if (existingOptions[i].value > newFormId) {
                        insertBeforeOption = existingOptions[i];
                        break;
                    }
                }

                // Insertar la nueva opción en el lugar correcto
                if (insertBeforeOption) {
                    selectElement.insertBefore(newOption, insertBeforeOption);
                } else {
                    selectElement.appendChild(newOption);
                }
            }

            // Ejemplo de uso para agregar un nuevo formulario
            //addFormOption('form3_3_1', '3.3.1 Nuevas Publicaciones relacionadas con la docencia');
        document.addEventListener('DOMContentLoaded', function () {

            const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
            if (toggleDarkModeButton) {
                const widthDarkButton = window.outerWidth - 230;
                toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
            }

            toggleDarkMode(); 
        });

        function submitFormData(formId) {
                const formData = {};
                const formContainer = document.getElementById('formContainer');
                const inputs = formContainer.querySelectorAll('input, select, textarea');

                // Recopilar los datos del formulario
                inputs.forEach(input => {
                    formData[input.id] = input.value;
                });

                // Agregar el CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Enviar los datos al servidor
                fetch(`/update-form/${formId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(formData)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Formulario actualizado correctamente.');
                        } else {
                            alert('Error al actualizar el formulario: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al enviar los datos.');
                    });
            }

    function submitDynamicFormData(formId) {
        const rows = [];
        const formContainer = document.getElementById('formContainer');
        const tableRows = formContainer.querySelectorAll('table tbody tr');

        // Recorrer cada fila de la tabla
        tableRows.forEach((row, rowIndex) => {
            const puntajeComisionInput = row.querySelector('input[id="inputValues"]');
            const observacionesInput = row.querySelector('input[id="inputObservaciones"]');
            const dynamicFormValueId = row.getAttribute('data-value-id'); // Asegúrate de incluir este atributo en las filas

            rows.push({
                row_identifier: `row_${rowIndex}`, // Identificador único para la fila
                dynamic_form_value_id: dynamicFormValueId || null, // ID del valor relacionado
                puntaje_comision: puntajeComisionInput ? puntajeComisionInput.value : null,
                observaciones: observacionesInput ? observacionesInput.value : null,
            });
        });

        // Agregar el CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Enviar los datos al servidor
        fetch(`/update-commission-data/${formId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ rows }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Datos enviados correctamente.');
                } else {
                    alert('Error al enviar los datos: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al enviar los datos.');
            });
    } 
/*
    // Manejar la selección de un docente en formularios dinámicos
        docenteDynamicSelect.addEventListener('change', (event) => {
            const selectedDocenteEmail = event.target.value;

            if (selectedDocenteEmail) {
                console.log('Docente seleccionado:', selectedDocenteEmail);

                // Aquí puedes realizar acciones adicionales, como cargar datos del docente
                fetch(`/get-docente-data?email=${selectedDocenteEmail}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Datos del docente:', data);

                        // Manejar los datos del docente
                        // ...
                    })
                    .catch(error => {
                        console.error('Error al obtener los datos del docente:', error);
                    });
            }
        });
        */
        
    
function guardarDatosComision(formId, docenteEmail) {
    const rows = [];
    document.querySelectorAll('tr[id^="commission_row_"]').forEach(row => {
        const comisionId = row.querySelector('.puntaje-comision').dataset.commissionId;
        rows.push({
            row_identifier: row.id,
            puntaje_comision: row.querySelector('.puntaje-comision').value,
            observaciones: row.querySelector('.observaciones').value,
            id: comisionId // Para actualizar registros existentes
        });
    });

    // Enviar datos al servidor
    fetch(`/update-commission-data/${formId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            rows,
            email: docenteEmail,
            user_type: 'dictaminador'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Evaluación guardada correctamente');
            cargarFormularioConDatosDocente(formName, formId, docenteEmail);
        }
    });
}
    
    </script>

</body>

</html>