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
<style>
#btn3_6{
    margin-left: 280px;
}    
body.dark-mode [id^="btn3_"]{
        background-color: #456483;
        color: floralwhite;
}

body.dark-mode [id^="btn3_"]:hover {
    background-color: #6a5b9f;
    
}
</style>
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
        <form id="form3_6" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-form36', 'form3_6');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <div>
                <!-- 3.6 Capacitación y actualización pedagógica recibida  -->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">40</label>
                </h4>
            </div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">Actividad</th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
            
                        <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                        </th>
                        <th class="table-ajust" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <thead>
                        <tr>
                            <th id="seccion3_6" colspan=1 class="punto3_6" scope=col style="padding:30px;">3.6
                                Capacitación y
                                actualización
                                pedagógica recibida </th>
                            <td class="punto3_6">Factor</td>
                            <td class="punto3_6">Horas</td>
                            <td id="score3_6" for="">0</td>
                            <td id="comision3_6">0</td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td>0.5 por cada hora</td>
                            <td id="pMedio">0.5</td>
                            <td id="puntaje3_6" ></td>
                            <td id="puntajeHoras3_6"></td>
                            <td style="text-align: right;">
                                @if($userType == 'dictaminador')
                                    <input type="number"  step="0.01" id="comisionDict3_6" name="comisionDict3_6" oninput="onActv3Comision3_6()" value="{{ oldValueOrDefault('comisionDict3_6') }}">
                                @else
                                <span id="comisionDict3_6" name="comisionDict3_6"></span>
                                @endif
                            </td>
                            <td>
                                @if($userType == 'dictaminador')
                                <input id="obs3_6_1" name="obs3_6_1" class="table-header" type="text">
                                @else
                                <span id="obs3_6_1" name="obs3_6_1"></span>
                                @endif
                            </td>
                        </tr>
                    </thead>
                    <!--Tabla informativa Acreditacion Actividad 3.6-->
                    <table>
                        <thead>
                            <tr>
                                <th class="acreditacion" scope="col">Acreditacion: </th>
            
                                <th class="descripcion"><b>DDIE</b>
            
                                <th>@if($userType != '')
                                    <button id="btn3_6" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                                @endif
                                </th>
                            </tr>
                        </thead>
                    </table>
                </tbody>
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
        <x-form-renderer :forms="[['view' => 'form3_6', 'startPage' => 10, 'endPage' => 10]]" />
    </div>
</footer>

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
                                       document.getElementById('score3_6').textContent = data.form3_6.score3_6 || '0';
                                        document.getElementById('puntaje3_6').textContent = data.form3_6.puntaje3_6 || '0';
                                        document.getElementById('puntajeHoras3_6').textContent = data.form3_6.puntajeHoras3_6 || '0';


                                        // Populate hidden inputs
                                        document.querySelector('input[name="user_id"]').value = data.form3_6.user_id || '';
                                        document.querySelector('input[name="email"]').value = data.form3_6.email || '';
                                        document.querySelector('input[name="user_type"]').value = data.form3_6.user_type || '';

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
                                    const selectedResponseForm3_6 = dictaminatorResponses.form3_6.find(res => res.email === email);
                                   if (selectedResponseForm3_6) {

                                        document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_6.dictaminador_id || '0';
                                        document.querySelector('input[name="user_id"]').value = selectedResponseForm3_6.user_id || '';
                                        document.querySelector('input[name="email"]').value = selectedResponseForm3_6.email || '';
                                        document.querySelector('input[name="user_type"]').value = selectedResponseForm3_6.user_type || '';

                                        document.getElementById('score3_6').textContent = selectedResponseForm3_6.score3_6 || '0';
                                        document.getElementById('puntaje3_6').textContent = selectedResponseForm3_6.puntaje3_6 || '0';
                                        document.getElementById('puntajeHoras3_6').textContent = selectedResponseForm3_6.puntajeHoras3_6 || '0';

                                        document.getElementById('comision3_6').textContent = selectedResponseForm3_6.comision3_6 || '0';
                                        document.querySelector('span[name="comisionDict3_6"]').textContent = selectedResponseForm3_6.comisionDict3_6 || '0';
                                        document.querySelector('span[name="obs3_6_1"]').textContent = selectedResponseForm3_6.obs3_6_1 || '';

                                    } else {
                                        console.error('No form3_6 data found for the selected dictaminador.');
                                        // Reset input values if no data found
                                        document.querySelector('input[name="dictaminador_id"]').value = '0';
                                        document.querySelector('input[name="user_id"]').value = '0';
                                        document.querySelector('input[name="email"]').value = '';
                                        document.querySelector('input[name="user_type"]').value = '';

                                        document.getElementById('score3_6').textContent = '0';
                                        document.getElementById('puntaje3_6').textContent = '0';
                                        document.getElementById('puntajeHoras3_6').textContent = '0';
                                        document.getElementById('comision3_6').textContent = '0';
                                        document.querySelector('span[name="comisionDict3_6"]').textContent = '0';
                                        document.querySelector('span[name="obs3_6_1"]').textContent = '';
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

            // Gather all related information from the form
            formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
            formData['user_id'] = form.querySelector('input[name="user_id"]').value;
            formData['email'] = form.querySelector('input[name="email"]').value;
            formData['user_type'] = form.querySelector('input[name="user_type"]').value;
            formData['puntaje3_6'] = document.getElementById('puntaje3_6').textContent;
            formData['puntajeHoras3_6'] = document.getElementById('puntajeHoras3_6').textContent;
            formData['comisionDict3_6'] = form.querySelector('input[id="comisionDict3_6"]').value;
            formData['score3_6'] = document.getElementById('score3_6').textContent;
            formData['comision3_6'] = document.getElementById('comision3_6').textContent;

            // Observations
            formData['obs3_6_1'] = form.querySelector('input[name="obs3_6_1"]').value;

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