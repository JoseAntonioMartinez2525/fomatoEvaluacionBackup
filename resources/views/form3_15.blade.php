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
        <form id="form3_15" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-form315', 'form3_15');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
        <!--3.15 Registro de patentes y productos de investigación tecnológica y educativa -->
        <h4>Puntaje máximo
            <label class="bg-black text-white px-4 mt-3" for="">60</label>
        </h4>
        <table class="table table-sm tutorias">
            <thead>
                <tr>
                    <th scope="col" colspan=3>Actividad</th>
                    <th class="table-ajust" scope="col"></th>
                    <th class="table-ajust" scope="col"></th>
                    <th class="table-ajust" scope="col"></th>
                    <th class="table-ajust" scope="col"></th>
                    <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                    <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th id="seccion3_15" class="acreditacion" colspan=2>3.15 Registro de patentes y
                        productos de
                        investigación
                        tecnológica
                        y educativa</th>
                    <th class="acreditacion">Puntaje</th>
                    <th class="acreditacion">Cantidad</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th id="score3_15">0</th>
                    <th id="comision3_15">0</th>
                    <th class="acreditacion" scope="col">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>a)</td>
                    <td>Registro de patentes</td>
                    <td id="puntajePatentes"><b>60</b></td>
                    <td id="cantPatentes"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="subtotalPatentes">0</td>
                    <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionPatententes" value="{{ oldValueOrDefault('comisionPatententes') }}" oninput="onActv3Comision3_15()">
                        @else
                            <span id="comisionPatententes"></span>
                        @endif               
                    </td>
                    <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsPatentes">
                        @else
                            <span id="obsPatentes"></span>
                        @endif                      
                    </td>
                </tr>
                <tr>
                    <td>b)</td>
                    <td>Desarrollo de prototipos</td>
                    <td id="puntajePrototipos"><b>30</b></td>
                    <td id="cantPrototipos"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="subtotalPrototipos">0</td>
                    <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionPrototipos" value="{{ oldValueOrDefault('comisionPrototipos') }}" oninput="onActv3Comision3_15()">
                        @else
                            <span id="comisionPrototipos"></span>
                        @endif
                    </td>
                    <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsPrototipos">
                        @else
                            <span id="obsPrototipos"></span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <!--Tabla informativa Acreditacion Actividad 3.15-->
        <table>
            <thead>
                <tr>
                    <th class="acreditacion" scope="col">Acreditacion: </th>
        
                    <th class="descripcion"><b>IMPI</b></th>
                </tr>
            </thead>
        </table>
        @if($userType != '')
            <th><button id="btn3_15" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
        @endif
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
            <x-form-renderer :forms="[['view' => 'form3_15', 'startPage' => 22, 'endPage' => 22]]" />
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
                                    document.getElementById('score3_15').textContent = data.form3_15.score3_15 || '0';

                                    // Cantidades
                                    document.getElementById('cantPatentes').textContent = data.form3_15.cantPatentes || '0';
                                    document.getElementById('cantPrototipos').textContent = data.form3_15.cantPrototipos || '0';

                                    // Subtotales
                                    document.getElementById('subtotalPatentes').textContent = data.form3_15.subtotalPatentes || '0';
                                    document.getElementById('subtotalPrototipos').textContent = data.form3_15.subtotalPrototipos || '0';

                                    //  hidden inputs
                                    document.querySelector('input[name="user_id"]').value = data.form3_15.user_id || '';
                                    document.querySelector('input[name="email"]').value = data.form3_15.email || '';
                                    document.querySelector('input[name="user_type"]').value = data.form3_15.user_type || '';

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
                                const selectedResponseForm3_15 = dictaminatorResponses.form3_15.find(res => res.email === email);
                                if (selectedResponseForm3_15) {

                                    document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_15.dictaminador_id || '0';
                                    document.querySelector('input[name="user_id"]').value = selectedResponseForm3_15.user_id || '';
                                    document.querySelector('input[name="email"]').value = selectedResponseForm3_15.email || '';
                                    document.querySelector('input[name="user_type"]').value = selectedResponseForm3_15.user_type || '';
                                    document.getElementById('score3_15').textContent = selectedResponseForm3_15.score3_15 || '0';
                                    document.getElementById('comision3_15').textContent = selectedResponseForm3_15.comision3_15 || '0';

                                    // Cantidades
                                    document.getElementById('cantPatentes').textContent = selectedResponseForm3_15.cantPatentes || '0';
                                    document.getElementById('cantPrototipos').textContent = selectedResponseForm3_15.cantPrototipos || '0';


                                    // Subtotales
                                    document.getElementById('subtotalPatentes').textContent = selectedResponseForm3_15.subtotalPatentes || '0';
                                    document.getElementById('subtotalPrototipos').textContent = selectedResponseForm3_15.subtotalPrototipos || '0';

                                    // Comisiones
                                    document.querySelector('#comisionPatententes').textContent = selectedResponseForm3_15.comisionPatententes || '0';
                                    document.querySelector('#comisionPrototipos').textContent = selectedResponseForm3_15.comisionPrototipos || '0';

                                    // Observaciones
                                    document.querySelector('#obsPatentes').textContent = selectedResponseForm3_15.obsPatentes || '';
                                    document.querySelector('#obsPrototipos').textContent = selectedResponseForm3_15.obsPrototipos || '';


                                } else {
                                    console.error('No form3_15 data found for the selected dictaminador.');

                                    // Reset input values if no data found
                                    document.querySelector('input[name="dictaminador_id"]').value = '0';
                                    document.querySelector('input[name="user_id"]').value = '0';
                                    document.querySelector('input[name="email"]').value = '';
                                    document.querySelector('input[name="user_type"]').value = '';

                                    document.getElementById('score3_15').textContent = '0';

                                    // Cantidades
                                    document.getElementById('cantPatentes').textContent = '0';
                                    document.getElementById('cantPrototipos').textContent = '0';

                                    // Subtotales
                                    document.getElementById('subtotalPatentes').textContent = '0';
                                    document.getElementById('subtotalPrototipos').textContent = '0';

                                    // Comisiones
                                    document.querySelector('#comisionPatententes').textContent = '0';
                                    document.querySelector('#comisionPrototipos').textContent = '0';

                                    // Observaciones
                                    document.querySelector('#obsPatentes').textContent = '';
                                    document.querySelector('#obsPrototipos').textContent = '';

                                    document.getElementById('comision3_15').textContent = '0';
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

    });

        // Function to handle form submission
        async function submitForm(url, formId) {
            const formData = {};
            const form = document.getElementById(formId);

            if (!form) {
                console.error(`Form with id "${formId}" not found.`);
                return;
            }

            formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
            formData['user_id'] = form.querySelector('input[name="user_id"]').value;
            formData['email'] = form.querySelector('input[name="email"]').value;
            formData['user_type'] = form.querySelector('input[name="user_type"]').value;

            // Cantidades
            formData['cantPatentes'] = form.querySelector('td[id="cantPatentes"]').textContent;
            formData['cantPrototipos'] = form.querySelector('td[id="cantPrototipos"]').textContent;


            // Subtotales
            formData['subtotalPatentes'] = document.getElementById('subtotalPatentes').textContent;
            formData['subtotalPrototipos'] = document.getElementById('subtotalPrototipos').textContent;


            // Comisiones
            formData['comisionPatententes'] = form.querySelector('input[id="comisionPatententes"]').value;
            formData['comisionPrototipos'] = form.querySelector('input[id="comisionPrototipos"]').value;


            // Observaciones
            formData['obsPatentes'] = form.querySelector('input[id="obsPatentes"]').value;
            formData['obsPrototipos'] = form.querySelector('input[id="obsPrototipos"]').value;


            formData['score3_15'] = document.getElementById('score3_15').textContent;
            formData['comision3_15'] = document.getElementById('comision3_15').textContent;

            // Observations

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