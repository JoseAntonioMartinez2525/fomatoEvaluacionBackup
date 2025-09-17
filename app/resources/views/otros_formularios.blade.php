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

        #formContainer {
            margin-left: 200px;
            margin-top: 100px;
        }

        #inputValues {
            text-align: right;
            width: max-content;
            border: none;
            outline: none;
        }

        #inputObservaciones {
            width: max-content;
            border: none;
            outline: none;
        }

        #puntajeComisionValues,
        #observacionesNForm,
        #inputValues,
        #observacionesNForm input {
            background-color: #d6fff7;

        }

        body.dark-mode #puntajeComisionValues,
        body.dark-mode #observacionesNForm,
        body.dark-mode #inputValues,
        body.dark-mode #observacionesNForm input {
            color: black;
        }

        body.dark-mode #inputValues,
        body.dark-mode #observacionesNForm input {
            border: none;
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
                @if(Auth::user()->user_type === 'docente')
                            <x-nav-docentes :user="Auth::user()" />
                            <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass mt-4" style="margin-left: 100px;"><i
                                    class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>
                            <x-general-header />
                            <div class="container mt-4">
                                <!-- Selector para elegir el formulario -->
                                <label for="formSelect">Seleccionar Formulario:</label>
                                <select id="formSelect" class="form-select">
                                    <option value=""></option>
                                    <!-- Dynamic options -->
                                    @foreach($forms as $form)
                                        @if(!in_array($form->form_name, $existingFormNames)) <!-- Check for duplicates -->
                                            <option value="{{ $form->form_name }}" data-id="{{ $form->id }}">{{ $form->form_name }}</option>
                                            @php $existingFormNames[] = $form->form_name; @endphp <!-- Add to existing names -->
                                        @endif
                                    @endforeach

                                </select>
                            </div>
                    <!-- Agregar un contenedor oculto con los datos del usuario -->
                    <div id="userData" data-user-id="{{ Auth::user()->id }}" data-email="{{ Auth::user()->email }}"
                        data-user-type="{{ Auth::user()->user_type }}" style="display: none;"></div>

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
        document.addEventListener('DOMContentLoaded', (event) => {
            const formSelect = document.getElementById('formSelect');
            console.log('Dropdown Options:', Array.from(formSelect.options).map(option => option.value)); // Log the dropdown options

            formSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const selectedForm = selectedOption.getAttribute('value');
                const selectedFormId = selectedOption.getAttribute('data-id');
                const formContainer = document.getElementById('formContainer');


                // Obtener los datos del usuario desde el contenedor oculto
                const userData = document.getElementById('userData');
                const userId = userData.getAttribute('data-user-id');
                const email = userData.getAttribute('data-email');
                const userType = userData.getAttribute('data-user-type');

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
                                    formContainer.innerHTML = '';
                                    let tableHTML = `<form id="${selectedFormId}" method="POST">`;

                                    //hidden inputs para el id del usuario y el correo
                                    tableHTML += `<input type="hidden" name="user_id" value="${userId}">`;
                                    tableHTML += `<input type="hidden" name="email" value="${email}">`;
                                    tableHTML += `<input type="hidden" name="user_type" value="${userType}">`;
                                    // Mostrar el puntaje máximo en la parte superior con fondo negro
                                    tableHTML = `<div style="margin-bottom: 10px;"><strong>Puntaje máximo</strong> <span style="background-color: #000; color: #fff; font-weight: bold; text-align: center; padding: 2px 10px;">${data.puntaje_maximo}</span></div>`;

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
                                        tableHTML += `<td class="puntajeValues"><input class="puntajeValues" id="inputPuntajeValues" type="number" placeholder="${puntajeEvalValues.length > 1 ? puntajeEvalValues[1].value : '0'}"></td>`;
                                        tableHTML += `<td id="puntajeComisionValues" class="puntajeValues">0</td>`;
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
                                        tableHTML += ` <button button type = "submit" class="btn custom-btn printButtonClass dynamicBtn" onclick = "submitDynamicFormData('${selectedFormId}')"> Enviar</button >`;
                                        tableHTML += '</form>';
                                        formContainer.innerHTML = tableHTML;
                                    }
                                } else {
                                    formContainer.innerHTML = '<p class="alert alert-danger">Error al cargar el formulario: ' + (data.message || 'Formulario no encontrado') + '</p>';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                formContainer.innerHTML = '<p class="alert alert-danger">Error: ' + error.message + '</p>';
                            });
                    }
                } else {
                    formContainer.innerHTML = '';
                }
            });
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
            const tableRows = formContainer.querySelectorAll('table tbody tr:not(:last-child)');

            // Recorrer cada fila de la tabla
            tableRows.forEach((row, rowIndex) => {
                const inputPuntajeValues = row.querySelector('input[id="inputPuntajeValues"]');
                puntajeEvalCell = row.querySelector('td.puntajeValues');
                const dynamicFormValueId = row.getAttribute('data-value-id');

                // Solo procesar filas que tengan campos de entrada
                if (inputPuntajeValues || puntajeEvalCell) {
                    rows.push({
                        row_identifier: `row_${rowIndex + 1}`,
                        dynamic_form_value_id: dynamicFormValueId || null,
                        puntaje_input_values: inputPuntajeValues ? inputPuntajeValues.value : null,
                        puntaje_evaluar: puntajeEvalCell ? puntajeEvalCell.textContent : null
                    });
                }
            });

            const userId = userData.getAttribute('data-user-id');
            const email = userData.getAttribute('data-email');
            const userType = userData.getAttribute('data-user-type');


            // Agregar el CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Enviar los datos al servidor
            fetch(`/update-commission-data/${formId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ 
                    rows,
                    user_id: userId,
                    email: email,
                    user_type: userType, 

                }),
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


    </script>

</body>

</html>