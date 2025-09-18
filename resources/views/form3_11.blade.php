@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
@endphp
<!DOCTYPE html>
<html lang="">

<head>
    <link rel="icon" href="{{ $logo }}" type="image/png">
    <title>Evaluación docente</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-head-resources />
    <link href="{{ asset('css/onePage.css') }}" rel="stylesheet">
</head>

<body class="bg-gray-50 text-black/50">

    <div class="relative min-h-screen flex flex-col items-center justify-center">
        @if (Route::has('login'))
            @if (Auth::check())
                <x-nav-menu :user="Auth::user()" />
            @endif
        @endif
    </div>
    <x-general-header />
@php
$user = Auth::user();
$userType = $user->user_type;
$user_identity = $user->id; 
@endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if($userType !== 'docente')
            <!-- Select para dictaminador seleccionando docentes -->
            <label for="docenteSelect">Seleccionar Docente:</label>
            <select id="docenteSelect" class="form-select"> <!--name="docentes[]" multiple-->
                <option value="">Seleccionar un docente</option>
                <!-- Aquí se llenarán los docentes con JavaScript -->
            </select>
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_1 -->
        <form id="form3_11" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-form311', 'form3_11');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <!--3.11 Trabajos dirigidos para la titulación de estudiantes-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">95</label>
            </h4>
            <table class="table table-sm tutorias">
                <thead>
                    <tr>
                        <th scope="col" colspan=3>Actividad</th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                        </th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th id="seccion3_11" class="acreditacion" colspan=5>3.11 Asesoría a estudiantes</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th id="score3_11">0</th>
                        <th id="comision3_11">0</th>
                        <th class="acreditacion" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="acreditacion">Incisos</th>
                        <th class="acreditacion">Documento</th>
                        <th class="acreditacion">Actividad</th>
                        <th class="acreditacion">Puntaje</th>
                        <th class="acreditacion">Cantidad</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="acreditacion">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <!--3_11 Asesoria a estudiantes incisos-->
                    <tr>
                        <td>a)</td>
                        <td>Asesorías académicas</td>
                        <td>Por alumno(a), por semestre</td>
                        <td id="academica">5</td>
                        <td id="cantAsesoria"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalAsesoria"></td>
                        <td id="comisionAsesoria">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionAsesoria" name="comisionAsesoria" oninput="onActv3Comision3_11()" value="{{ oldValueOrDefault('comisionAsesoria') }}">   
                            @else
                                <span  id="comisionAsesoria" name="comisionAsesoria" ></span>                      
                            @endif
                        </td>
                        <td id="obsAsesoria">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsAsesoria" name="obsAsesoria">
                            @else
                            <span id="obsAsesoria" name="obsAsesoria"></span>
                            @endif
                            
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>Servicio social*</td>
                        <td>Por alumno(a), por semestre</td>
                        <td id="servicio">20</td>
                        <td id="cantServicio"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalServicio"></td>
                        <td id="comisionServicio">
                        @if ($userType == 'dictaminador')   
                            <input type="number" step="0.01" id="comisionServicio" name="comisionServicio" placeholder="0" oninput="onActv3Comision3_11()" value="{{ oldValueOrDefault('comisionServicio') }}">
                        @else
                            <span id="comisionServicio" name="comisionServicio"></span>
                        @endif

                        </td>
                        <td id="obsServicio">
                        @if ($userType == 'dictaminador')   
                            <input class="table-header" type="text" id="obsServicio" name="obsServicio"></td>
                        @else
                            <span id="comisionServicio" name="obsServicio"></span>
                        @endif

                    </tr>
                    <tr>
                        <td>c)</td>
                        <td>Prácticas profesionales</td>
                        <td>Por alumno(a), por semestre</td>
                        <td id="practicas">20</td>
                        <td id="cantPracticas"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalPracticas"></td>
                        <td  id="comisionPracticas">
                        @if ($userType == 'dictaminador')  
                            <input type="number" step="0.01" id="comisionPracticas"  name="comisionPracticas" oninput="onActv3Comision3_11()" value="{{ oldValueOrDefault('comisionPracticas') }}">
                        @else
                        <span id="comisionPracticas" name="comisionPracticas"></span
                        @endif  
                            
                        </td>
                        <td id="obsPracticas">
                        @if ($userType == 'dictaminador')  
                            <input class="table-header" type="text" id="obsPracticas" name="obsPracticas">
                        @else
                            <span id="obsPracticas" name="obsPracticas"></span>
                        @endif    
                            
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <!--Tabla informativa Acreditacion Actividad 3.11-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>
            
                        <th class="descripcion"><b>JD, *DSEs</b> </th>
                        <th>
                            @if ($userType != '') 
                            <button id="btn3_11" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                            @endif
                        </th>
                    </tr>
                </thead>
            </table>
            </form>
    </main>
<center>
    <footer id="footerForm3_4">
        <center>
            <div id="convocatoria">
                <!-- Mostrar convocatoria -->
                @if(isset($convocatoria))

                    <div style="margin-right: -700px;">
                        <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                    </div>
                @endif
            </div>
        </center>
    
        <div id="piedepagina" style="margin-left: 500px;margin-top:10px;">
            <x-form-renderer :forms="[['view' => 'form3_11', 'startPage' => 17, 'endPage' => 17]]" />
        </div>
    </footer>
</center>
    <script>
        window.onload = function () {
            const footerHeight = document.querySelector('footer').offsetHeight;
            const elements = document.querySelectorAll('.prevent-overlap');

            elements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                // Verifica si el elemento está demasiado cerca del footer y aplica page-break-before si es necesario
                if (rect.bottom + footerHeight > viewportHeight) {
                    element.style.pageBreakBefore = "always"; // Forzar salto antes
                }
            });

        };          
            document.addEventListener('DOMContentLoaded', async () => {
                const userType = @json($userType);  // Inject user type from backend to JS
                const user_identity = @json($user_identity);
                const docenteSelect = document.getElementById('docenteSelect');

                if (docenteSelect) {
                    // Cuando el usuario es dictaminador
                    if (userType === 'dictaminador') {
                        try {
                           const response = await fetch('/formato-evaluacion/get-docentes');
                            const docentes = await response.json();

                            docentes.forEach(docente => {
                                const option = document.createElement('option');
                                option.value = docente.email;
                                option.textContent = docente.email;
                                docenteSelect.appendChild(option);
                            });

                            docenteSelect.addEventListener('change', async (event) => {
                                const email = event.target.value;

                                if (email) {
                                    axios.get('/formato-evaluacion/get-docente-data', { params: { email } })
                                        .then(response => {
                                            const data = response.data;
                                            // Populate fields with fetched data
                                            document.getElementById('score3_11').textContent = data.form3_11.score3_11 || '0';
                                            document.getElementById('cantAsesoria').textContent = data.form3_11.cantAsesoria || '0';
                                            document.getElementById('cantServicio').textContent = data.form3_11.cantServicio || '0';
                                            document.getElementById('cantPracticas').textContent = data.form3_11.cantPracticas || '0';
                                            document.getElementById('subtotalAsesoria').textContent = data.form3_11.subtotalAsesoria || '0';
                                            document.getElementById('subtotalServicio').textContent = data.form3_11.subtotalServicio || '0';
                                            document.getElementById('subtotalPracticas').textContent = data.form3_11.subtotalPracticas || '0';


                                            // Populate hidden inputs
                                            document.querySelector('input[name="user_id"]').value = data.form3_11.user_id || '';
                                            document.querySelector('input[name="email"]').value = data.form3_11.email || '';
                                            document.querySelector('input[name="user_type"]').value = data.form3_11.user_type || '';

                                            // Actualizar convocatoria
                                            const convocatoriaElement = document.getElementById('convocatoria');
                                            if (convocatoriaElement) {
                                                if (data.form1) {
                                                    convocatoriaElement.textContent = data.form1.convocatoria || '';
                                                } else {
                                                    console.error('form1 no está definido en la respuesta.');
                                                }
                                            } else {
                                                console.error('Elemento con ID "convocatoria" no encontrado.');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error fetching docente data:', error);
                                        });
                                    //await asignarDocentes(user_identity, email);
                                }
                            });
                        } catch (error) {
                            console.error('Error fetching docentes:', error);
                            alert('No se pudo cargar la lista de docentes.');
                        }
                    }
                    // Cuando el userType está vacío
                    else if (userType === '') {

                        try {
                           const response = await fetch('/formato-evaluacion/get-docentes');

                            const docentes = await response.json();

                            docentes.forEach(docente => {
                                const option = document.createElement('option');
                                option.value = docente.email;
                                option.textContent = docente.email;
                                docenteSelect.appendChild(option);
                            });

                            docenteSelect.addEventListener('change', async (event) => {
                                const email = event.target.value;

                                if (email) {
                                    axios.get('/formato-evaluacion/get-docente-data', { params: { email } })
                                        .then(response => {
                                            const data = response.data;

                                            // Actualizar convocatoria

                                            // Verifica si la respuesta contiene los datos esperados
                                            if (data.docente) {
                                                const convocatoriaElement = document.getElementById('convocatoria');

                                                // Mostrar la convocatoria si existe
                                                if (convocatoriaElement) {
                                                    if (data.docente.convocatoria) {
                                                        convocatoriaElement.textContent = data.docente.convocatoria;
                                                    } else {
                                                        convocatoriaElement.textContent = 'Convocatoria no disponible';
                                                    }
                                                }
                                            }
                                        });
                                    // Lógica para obtener datos de DictaminatorsResponseForm2
                                    try {
                                        const response = await fetch('/formato-evaluacion/get-dictaminators-responses');
                                        const dictaminatorResponses = await response.json();
                                        // Filtrar la entrada correspondiente al email seleccionado
                                        const selectedResponseForm3_11 = dictaminatorResponses.form3_11.find(res => res.email === email);
                                        if (selectedResponseForm3_11) {

                                            document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_11.dictaminador_id || '0';
                                            document.querySelector('input[name="user_id"]').value = selectedResponseForm3_11.user_id || '';
                                            document.querySelector('input[name="email"]').value = selectedResponseForm3_11.email || '';
                                            document.querySelector('input[name="user_type"]').value = selectedResponseForm3_11.user_type || '';

                                            document.getElementById('score3_11').textContent = selectedResponseForm3_11.score3_11 || '0';
                                            document.getElementById('cantAsesoria').textContent = selectedResponseForm3_11.cantAsesoria || '0';
                                            document.getElementById('cantServicio').textContent = selectedResponseForm3_11.cantServicio || '0';
                                            document.getElementById('cantPracticas').textContent = selectedResponseForm3_11.cantPracticas || '0';
                                            document.getElementById('subtotalAsesoria').textContent = selectedResponseForm3_11.subtotalAsesoria || '0';
                                            document.getElementById('subtotalServicio').textContent = selectedResponseForm3_11.subtotalServicio || '0';
                                            document.getElementById('subtotalPracticas').textContent = selectedResponseForm3_11.subtotalPracticas || '0';
                                            document.getElementById('comision3_11').textContent = selectedResponseForm3_11.comision3_11 || '0';
                                            document.querySelector('span[name="comisionAsesoria"]').textContent = selectedResponseForm3_11.comisionAsesoria || '0';
                                            document.querySelector('span[name="comisionServicio"]').textContent = selectedResponseForm3_11.comisionServicio || '0';
                                            document.querySelector('span[name="comisionPracticas"]').textContent = selectedResponseForm3_11.comisionPracticas || '0';
                                            document.querySelector('span[name="obsAsesoria"]').textContent = selectedResponseForm3_11.obsAsesoria || '';
                                            document.querySelector('span[name="obsServicio"]').textContent = selectedResponseForm3_11.obsServicio || '';
                                            document.querySelector('span[name="obsPracticas"]').textContent = selectedResponseForm3_11.obsPracticas || '';


                                        } else {

                                            console.error('No form3_11 data found for the selected dictaminador.');
                                            // Reset input values if no data found
                                            document.querySelector('input[name="dictaminador_id"]').value = '0';
                                            document.querySelector('input[name="user_id"]').value = '0';
                                            document.querySelector('input[name="email"]').value = '';
                                            document.querySelector('input[name="user_type"]').value = '';
                                            document.getElementById('cantAsesoria').textContent = '0';
                                            document.getElementById('cantServicio').textContent = '0';
                                            document.getElementById('cantPracticas').textContent = '0';
                                            document.getElementById('subtotalAsesoria').textContent = '0';
                                            document.getElementById('subtotalServicio').textContent = '0';
                                            document.getElementById('subtotalPracticas').textContent = '0';
                                            document.getElementById('comision3_11').textContent = '0';
                                            document.querySelector('span[name="comisionAsesoria"]').textContent = '0';
                                            document.querySelector('span[name="comisionServicio"]').textContent = '0';
                                            document.querySelector('span[name="comisionPracticas"]').textContent = '0';
                                            document.querySelector('span[name="obsAsesoria"]').textContent = '';
                                            document.querySelector('span[name="obsServicio"]').textContent = '';
                                            document.querySelector('span[name="obsPracticas"]').textContent = '';

                                        }
                                    } catch (error) {
                                        console.error('Error fetching dictaminators responses:', error);
                                    }
                                }
                            });
                        } catch (error) {
                            console.error('Error fetching docentes:', error);
                            alert('No se pudo cargar la lista de docentes.');
                        }


                    }



                }


                /*
                async function asignarDocentes(dictaminadorId, docenteEmail) {
                    try {
                        const response = await fetch(`/asignar-docentes/${dictaminadorId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ docentes: docenteEmail })
                        });
                        const data = await response.json();
                        console.log('Docentes asignados correctamente:', data);
                    } catch (error) {
                        console.error('Error asignando docentes:', error);
                    }
                }
                
                async function agregarDocente(dictaminadorId, docenteEmail) {
                    try {
                        const response = await fetch(`/agregar-docente/${dictaminadorId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ docente_email: docenteEmail })
                        });
                        const data = await response.json();
                        console.log('Docente agregado correctamente:', data);
                    } catch (error) {
                        console.error('Error agregando docente:', error);
                    }
                }
                        */
            });

        // Function to handle form submission
        async function submitForm(url, formId) {
            const formData = {};
            const form = document.getElementById(formId);

            if (!form) {
                console.error(`Form with id "${formId}" not found.`);
                return;
            }

            // Gather all related information from the form
            formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
            formData['user_id'] = form.querySelector('input[name="user_id"]').value;
            formData['email'] = form.querySelector('input[name="email"]').value;
            formData['user_type'] = form.querySelector('input[name="user_type"]').value;
            formData['cantAsesoria'] = document.getElementById('cantAsesoria').textContent;
            formData['comisionAsesoria'] = document.getElementById('comisionAsesoria').value; // Ensure input value is fetched
            formData['cantServicio'] = document.getElementById('cantServicio').textContent;
            formData['cantPracticas'] = document.getElementById('cantPracticas').textContent;
            formData['comisionServicio'] = document.getElementById('comisionServicio').value; // Ensure input value is fetched
            formData['subtotalAsesoria'] = document.getElementById('subtotalAsesoria').textContent;
            formData['subtotalServicio'] = document.getElementById('subtotalServicio').textContent;
            formData['comisionPracticas'] = document.getElementById('comisionPracticas').value; // Ensure input value is fetched
            formData['subtotalPracticas'] = document.getElementById('subtotalPracticas').textContent;
            formData['comisionAsesoria'] = form.querySelector('input[id="comisionAsesoria"]').value;
            formData['comisionServicio'] = form.querySelector('input[id="comisionServicio"]').value;
            formData['comisionPracticas'] = form.querySelector('input[id="comisionPracticas"]').value;
            formData['score3_11'] = document.getElementById('score3_11').textContent;
            formData['comision3_11'] = document.getElementById('comision3_11').textContent;

            // Observations
            formData['obsAsesoria'] = form.querySelector('input[name="obsAsesoria"]').value;
            formData['obsServicio'] = form.querySelector('input[name="obsServicio"]').value;
            formData['obsPracticas'] = form.querySelector('input[name="obsPracticas"]').value;


            console.log('Form data:', formData);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const responseData = await response.json();
                console.log('Response received from server:', responseData);

                //Mensaje al usuario
                if (responseData.success) {
                    showMessage('Formulario enviado', 'green');
                } else {
                    showMessage('Formulario no enviado', 'red');
                }
            } catch (error) {
                  showMessage('Error de red o servidor', 'red');
                    console.error('There was a problem with the fetch operation:', error);
        }
        }
        function minWithSum(value1, value2) {
            const sum = value1 + value2;
            return Math.min(sum, 200);


        }

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