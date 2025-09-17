@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
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
    <link href="{{ asset('css/edit_delete_form.css') }}" rel="stylesheet">
    <style>
        #formSelect{
            margin-bottom: 1rem;
        }
        body.dark-mode .btn-success{
            background-color:rgb(56, 163, 79);
         }

         body.dark-mode .btn-danger{
            background-color:rgb(218, 65, 81);
         }
         
        /* Estilos para las pestañas */
        .tab-container {
            display: flex;
            width: 100%;
            height: 45px;
            margin-top: 0; /* Eliminar margen superior */
        }
        
        .tab-button {
            flex: 1;
            text-align: center;
            padding: 12px 200px 50px 200px;
            font-weight: bold;
            text-decoration: none;
            color: #555;
            background-color:rgb(192, 206, 221);
            text-transform: uppercase;
            font-size: 14px;
            border-radius: 0.5rem;
            margin: 0 3px;
            transition: all 0.2s ease;
            
        }
        
        .tab-button.active {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            
        }
        
        /* Contenido principal */
        .main-content {
            margin-left: 200px; /* Espacio para el menú lateral */
            padding: 20px;
        }
        
        /* Botón de modo oscuro */
        .dark-mode-button {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        #acreditacion{
            font-weight: bold;
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
        <div class="d-flex">
            @if (Route::has('login'))
                @if (Auth::check() && Auth::user()->user_type === '')
                    <!-- Componente de menú de navegación -->
                    <x-nav-menu :user="Auth::user()">
                        <div>
                            <ul style="list-style: none;">
                                <li class="nav-item">
                                    <a class="nav-link active enlaceSN" style="width: 300px;" 
                                       href="{{route('dynamic_forms')}}"
                                       title="Ingresar nuevo formulario">
                                       <i class="fa-solid fa-folder-plus"></i>&nbspIngresar nuevo
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </x-nav-menu>
                @endif

                <!-- Sección principal del contenido -->
                <div class="main-content">
                    <!-- Pestañas de navegación -->
                    <div class="tab-container">
                        <a href="{{ route('edit_delete_form') }}" class="tab-button">EDITAR/ELIMINAR FORMULARIO</a>
                        <a href="{{ route('dynamic_forms') }}" class="tab-button  active">AÑADIR NUEVO FORMULARIO</a>
                    </div>

                    <!-- Contenido del formulario -->
                    <div class="mt-4">
                        <h3>Editar/Eliminar Formulario</h3>

                        <form id="editDeleteForm" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod">
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                            <input type="hidden" name="form_id" id="form_id">

                            <div class="form-group">
                                <label for="formSelect">Seleccionar Formulario:</label>
                                <select id="formSelect" name="form_name">
                                    <option value="">Selecciona un formulario</option>
                                    @foreach($forms as $form)
                                        <option value="{{ $form->form_name }}" data-id="{{ $form->id }}">{{ $form->form_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="dynamicTableContainer">
                                <!-- Table will be loaded here -->
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success" id="updateBtn">Guardar Cambios</button>
                                <button type="button" class="btn btn-danger" id="deleteBtn" onclick="deleteForm()">Eliminar Formulario</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

<script>
    const formSelect = document.getElementById('formSelect');
    console.log('Dropdown Options:', Array.from(formSelect.options).map(option => option.value)); // Log the dropdown options

    formSelect.addEventListener('change', function () {

        const selectedOption = this.options[this.selectedIndex];
        const selectedForm = selectedOption.getAttribute('value');
        const selectedFormId = selectedOption.getAttribute('data-id');

        document.getElementById('form_id').value = selectedFormId;
        document.getElementById('editDeleteForm').action = `/forms/${selectedFormId}`;

        const dynamicTableContainer = document.getElementById('dynamicTableContainer');

        console.log('Selected Form Type:', selectedForm); // Log the selected form type
        console.log('Selected Form ID:', selectedFormId);
        console.log('Form Action Before:', document.getElementById('editDeleteForm').action);

        document.getElementById('form_id').value = selectedFormId;
        document.getElementById('editDeleteForm').action = `/forms/${selectedFormId}`;

        console.log('Form Action After:', document.getElementById('editDeleteForm').action);


        if (selectedForm) {
            fetch(`/get-form-data/${selectedForm}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Returned Data:', data);
                    console.log('Columns:', data.columns); // Log the columns
                    console.log('Values:', data.values); // Log the values received from the backend (should have row_index)
                    console.log('Puntaje máximo:', data.puntaje_maximo);
                    console.log('Acreditación:', data.acreditacion); // Log the acreditación

                    if (data.success) {
                        dynamicTableContainer.innerHTML = '';

                        // Mostrar el puntaje máximo en la parte superior con fondo negro
                        let tableHTML = `<div style="margin-bottom: 10px;"><strong>Puntaje máximo</strong> <input value="${data.puntaje_maximo}" style="background-color: #000; color:#ffff; font-weight:bold; text-align:center; padding: 2px 10px;"></input></div>`;

                        // Crear la tabla
                        tableHTML += '<table class="table table-bordered">';

                        // Encabezados principales
                        tableHTML += '<thead><tr>';
                        tableHTML += '<th>Actividad</th>';

                        // Agregar los nombres de las columnas dinámicas
                        const fixedHeaders = ['Actividad', 'Puntaje a evaluar', 'Puntaje de la Comisión Dictaminadora', 'Observaciones'];
                        const dynamicColumnNames = data.columns
                            .map(column => column.column_name)
                            .filter(name => !fixedHeaders.includes(name));

                        // Create a map from column ID to column name for easier lookup
                        const columnIdToNameMap = data.columns.reduce((acc, column) => {
                            acc[column.id] = column.column_name;
                            return acc;
                        }, {});

                        console.log('Column ID to Name Map:', columnIdToNameMap); // Log the column map


                        // Agregar solo las columnas dinámicas (subencabezados)
                        dynamicColumnNames.forEach(columnName => {
                            tableHTML += `<th><input value="${columnName}" readonly class="form-control"/></th>`;
                        });

                        tableHTML += '<th>Puntaje a evaluar</th>';
                        tableHTML += '<th>Puntaje de la Comisión Dictaminadora</th>';
                        tableHTML += '<th>Observaciones</th>';
                        tableHTML += '</tr></thead><tbody>';

                        // Group values by row_index
                        const valuesByRow = data.values.reduce((acc, valueItem) => {
                            // Ensure valueItem has a row_index and a valid column ID
                            if (valueItem.row_index !== undefined && valueItem.row_index !== null && valueItem.dynamic_form_column_id) {
                                const rowIndex = valueItem.row_index;
                                if (!acc[rowIndex]) {
                                    acc[rowIndex] = {};
                                    // Initialize all expected columns for this row to ensure structure
                                    fixedHeaders.forEach(header => {
                                        acc[rowIndex][header] = '';
                                    });
                                    dynamicColumnNames.forEach(dynamicColName => {
                                        acc[rowIndex][dynamicColName] = '';
                                    });
                                    acc[rowIndex]['row_index'] = rowIndex; // Store the row index in the row object
                                }
                                const columnName = columnIdToNameMap[valueItem.dynamic_form_column_id];

                                if (columnName) {
                                    acc[rowIndex][columnName] = valueItem.value;
                                    console.log(`Assigned value "${valueItem.value}" to row ${rowIndex}, column "${columnName}" (ID: ${valueItem.dynamic_form_column_id})`);
                                } else {
                                    console.warn(`No column name found for column ID ${valueItem.dynamic_form_column_id} in row ${rowIndex}. Value: "${valueItem.value}"`);
                                }
                            } else {
                                console.warn('Value item missing row_index or column ID, skipping:', valueItem);
                            }
                            return acc;
                        }, {});

                        console.log('Values Grouped by Row:', valuesByRow); // Log the grouped values

                        // Add the first row with the form name (special case as per your example)
                        tableHTML += '<tr>';
                        tableHTML += `<td><input value="${selectedForm}" readonly class="form-control"/></td>`; // formName in the first column

                        // Add empty cells for dynamic columns in the first row
                        dynamicColumnNames.forEach(columnName => {
                            tableHTML += '<td></td>';
                        });

                        // Add default values for fixed columns in the first row with styles
                        tableHTML += '<td style="background-color: #0b5967; color: #ffff;">0</td>';
                        tableHTML += '<td style="background-color: #ffcc6d;">0</td>';
                        tableHTML += '<td></td>'; // Empty cell for observations
                        tableHTML += '</tr>';


                        // Iterate through grouped rows (sorted by row_index) and create table rows
                        // Use Object.keys to get row indices and sort them numerically
                        Object.keys(valuesByRow).sort((a, b) => a - b).forEach(rowIndex => {
                            const row = valuesByRow[rowIndex];

                            // Basic check to ensure the row has an 'Actividad' value before rendering as a data row
                            // You might refine this condition based on your specific data
                            if (!row['Actividad'] || row['Actividad'] === selectedForm) { // Added check to skip if Actividad is empty or matches form name
                                console.warn(`Skipping row ${rowIndex} as it does not have a valid Actividad value:`, row);
                                return;
                            }


                            tableHTML += '<tr>';

                            // Add cell for 'Actividad'
                            tableHTML += `<td><input value="${row['Actividad'] || ''}" class="form-control"/></td>`;

                            // Add cells for dynamic columns
                            dynamicColumnNames.forEach(columnName => {
                                const cellValue = row[columnName] || '';
                                tableHTML += `<td><input value="${cellValue}" class="form-control"/></td>`;
                            });

                            // Add cells for fixed columns
                            // Use values from the reconstructed row, defaulting to '0' or '' if not present
                            tableHTML += `<td><input value="${row['Puntaje a evaluar'] || '0'}" class="form-control"/></td>`;
                            tableHTML += `<td><input value="${row['Puntaje de la Comisión Dictaminadora'] || '0'}" class="form-control"/></td>`;
                            tableHTML += `<td><input value="${row['Observaciones'] || ''}" class="form-control"/></td>`;

                            tableHTML += '</tr>';
                        });


                        // Acreditación row
                        tableHTML += '<tr>';
                        tableHTML += '<td>Acreditación:</td>';

                        // Calculate the number of columns (includes dynamic + fixed)
                        const totalColumnSpan = dynamicColumnNames.length + 3; // 3 = fixed columns (Puntaje a evaluar, Comisión, Observaciones)
                        tableHTML += `<td colspan="${totalColumnSpan}">`;
                        tableHTML += `<input id="acreditacion" type="text" value="${data.acreditacion || ''}" placeholder="Información sobre acreditación" class="form-control">`;
                        tableHTML += '</td>';
                        tableHTML += '</tr>';

                        tableHTML += '</tbody></table>';

                        // Agregar la tabla al contenedor
                        dynamicTableContainer.innerHTML = tableHTML;

                    } else {
                        alert('Error fetching form data.');
                    }
                })
                .catch(error => console.error('Error fetching form data:', error));
        } else {
            dynamicTableContainer.innerHTML = ''; // Clear the container if no form is selected
        }
    });
    
    function deleteForm() {
        var formId = document.getElementById('form_id').value;
        if (!formId) {
            alert('Error: No form selected');
            return;
        }

        if (confirm('¿Está seguro de que desea eliminar este formulario?')) {
            $.ajax({
                url: `/forms/${formId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                success: function (response) {
                    if (response.success) {
                        alert('Formulario eliminado correctamente');
                        window.location.href = "{{ route('secretaria') }}";
                    } else {
                        alert('Formulario no eliminado: ' + (response.message || 'Error desconocido'));
                    }
                },
                error: function (xhr) {
                    alert('Error al eliminar formulario: ' + (xhr.responseJSON?.message || 'Error desconocido'));
                }
            });
        }
    }
    document.getElementById("editDeleteForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission
        const formId = document.getElementById('form_id').value;
        if (!formId) {
            alert("Error: No se ha seleccionado un formulario válido.");
            return;
        }
        let formData = new FormData(this);
        console.log("Form Data:", Object.fromEntries(formData)); // Log data being sent

        // Convert FormData to JSON object
        let formDataObject = {
            _token: formData.get('_token'),
            _method: formData.get('_method'),
            user_id: formData.get('user_id'),
            email: formData.get('email'),
            user_type: formData.get('user_type'),
            form_name: formData.get('form_name'),
            puntajeMaximo: formData.get('puntajeMaximo'),
            column_name: [],
            value: []
        };
        //Process form Data
        formData.forEach((value, key) => {
            // Handle array inputs
            if (key.endsWith('[]')) {
                let arrayKey = key.slice(0, -2);
                if (!formDataObject[arrayKey]) {
                    formDataObject[arrayKey] = [];
                }
                formDataObject[arrayKey].push(value);
            } else {
                formDataObject[key] = value;
            }
        });

        const formName = formData.get('form_name');
        const isDelete = formData.get('_method').toLowerCase() === 'delete';

        // Determine the appropriate endpoint and method
        const endpoint = `/forms/${formId}`;
        const method = isDelete ? 'DELETE' : 'PUT';

        fetch(endpoint, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "X-Requested-With": "XMLHttpRequest",
                'Accept': 'application/json'
            },
            body: JSON.stringify(formDataObject)
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error('Server Error:', err);
                        throw new Error(err.message || 'Server error');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log("Server Response:", data);
                if (data.success) {
                    alert('Form updated successfully');
                    window.location.reload();
                } else {
                    alert('Error updating form: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert('Error updating form: ' + error.message);
            });
    });

    document.getElementById('updateBtn').addEventListener('click', function () {
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('editDeleteForm').submit();
    });

    document.getElementById('deleteBtn').addEventListener('click', function () {
        document.getElementById('formMethod').value = 'DELETE';
        document.getElementById('editDeleteForm').submit();
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