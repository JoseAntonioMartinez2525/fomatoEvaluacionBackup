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
    <style>

    body.chrome @media print {
    #convocatoria {
        font-size: 1.2rem;
        color: blue;
        /* Ejemplo de estilo específico para Chrome */
    }
}
    @media print{
    .datosPrimarios{

        font-size: .9rem;
    }

    .descripcionCAAC{
        font-size: .75rem;
    }

footer {
    position: absolute; /* Usar absolute en lugar de fixed */
    font-size: .8rem;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
    background-color: white;
    z-index: 10;
    padding: 5px 0;
    border-top: 1px solid #ccc;
}

    footer::after {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            
            background: white;
            padding: 5px;
            z-index: 10;
        }


    .prevent-overlap {
        page-break-before: always;
    }

    #convocatoria {
        margin: 0;
        font-size: .8rem;
    }

    #piedepagina {
        margin: 0;
         page-break-inside: avoid; /* Evitar saltos dentro del pie de página */
    }

    div {
        page-break-after: avoid;
        page-break-before: avoid;
    }
    

    @page {
        size: landscape;
        margin: 20mm; /* Ajusta según sea necesario */
        
    }


}

body.dark-mode #cantInternacional, body.dark-mode #cantNacional, body.dark-mode #cantidadRegional, body.dark-mode #cantPreparacion,
body.dark-mode #comInternacional, body.dark-mode #comNacional, body.dark-mode #comRegional, body.dark-mode #comPreparacion{
    color: black;
}

body.dark-mode [id^="obs3_4_"]{
    color: black;
}

#btn3_4{
    margin-left: 1100px;
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
        <!-- Form for Part 3_4 -->
        <form id="form3_4" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-form34', 'form3_4');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <div>
                <!-- 3.4 Distinciones académicas recibidas por el docente  -->
                <h4 class="datosPrimarios">Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">60</label>
                </h4>
            </div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">Actividad</th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                        <th class="table-ajust" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th id="seccion3_4" colspan="2" class="punto3_4" scope="col" style="padding:5px;">3.4 Distinciones
                            académicas recibidas por el docente</th>
                        <td class="punto3_4">Puntaje</td>
                        <td class="punto3_4">Cantidad</td>
                        <td id="score3_4">0</td>
                        <td id="comision3_4">0</td>
                    </tr>
                    <tr>
                        <td class="punto3_4">a)</td>
                        <td>Internacional</td>
                        <td id="p60"><b>60</b></td>
                        <td>
                            <span id="cantInternacional" name="cantInternacional"></span>
                        </td>
                        <td id="cantInternacional2"></td>
                        <td>@if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comInternacional" oninput="onActv3Comision3_4()" value="{{ oldValueOrDefault('comInternacional') }}">
                        @else
                            <span id="comInternacional" name="comInternacional"></span>
                            @endif
                        </td>
                        <td>@if($userType == 'dictaminador')
                            <input id="obs3_4_1" name="obs3_4_1" class="table-header" type="text">
                        @else
                            <span id="obs3_4_1" name="obs3_4_1" class="table-header"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="punto3_4">b)</td>
                        <td>Nacional</td>
                        <td id="p30Nac"><b>30</b></td>
                        <td><span type="number" step="0.01" id="cantNacional"></span></td>
                        <td id="cantNacional2"></td>
                        <td>@if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comNacional"name="comNacional" oninput="onActv3Comision3_4()" value="{{ oldValueOrDefault('comNacional') }}">
                        @else
                            <span id="comNacional" name="comNacional"></span>
                            @endif
                        </td>
                        <td>@if($userType == 'dictaminador')
                            <input id="obs3_4_2" name="obs3_4_2" class="table-header" type="text">
                            @else
                                <span id="obs3_4_2" name="obs3_4_2" class="table-header"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="punto3_4">c)</td>
                        <td>Regional o estatal</td>
                        <td id="p20"><b>20</b></td>
                        <td>
                            <span id="cantidadRegional"></span>
                        </td>
                        <td id="cantidadRegional2"></td>
                        <td>@if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comRegional" name="comRegional" oninput="onActv3Comision3_4()" value="{{ oldValueOrDefault('comRegional') }}">
                        @else
                            <span id="comRegional" name="comRegional"></span>
                            @endif
                        </td>
                        <td>@if($userType == 'dictaminador')
                            <input id="obs3_4_3" name="obs3_4_3" class="table-header" type="text">
                            @else
                            <span id="obs3_4_3" name="obs3_4_3" class="table-header"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="punto3_4">d)</td>
                        <td>Preparación de grupos de alumnado para olimpiadas competencias académicas o exámenes generales.</td>
                        <td id="p30Prep"><b>30</b></td>
                        <td>
                            <span id="cantPreparacion"></span>
                        </td>
                        <td id="cantPreparacion2"></td>
                        <td>@if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comPreparacion"  oninput="onActv3Comision3_4()" value="{{ oldValueOrDefault('comPreparacion') }}">
                        @else
                            <span id="comPreparacion" name="comPreparacion"></span>
                            @endif
                        </td>
                        <td>
                            @if($userType == 'dictaminador')
                            <input id="obs3_4_4" name="obs3_4_4" class="table-header" type="text">
                            @else
                                <span id="obs3_4_4" name="obs3_4_4" class="table-header"></span>
                            @endif
                        </td>
                    </tr>
                    <tr><!--Tabla informativa Acreditacion Actividad 3.4-->
                        <td class="acreditacion" scope="col">Acreditacion: </td>
                        <td class="descripcionCAAC"><b>CAAC, Instancia que la otorga</b></td>
                    </tr>
                </tbody>
            </table>
            @if($userType != '')
                <button id="btn3_4" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
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
            <x-form-renderer :forms="[['view' => 'form3_4', 'startPage' => 8, 'endPage' => 8]]" />
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
                                    document.getElementById('score3_4').textContent = data.form3_4.score3_4 || '0';
                                    document.getElementById('cantInternacional').textContent = data.form3_4.cantInternacional || '0';
                                    document.getElementById('cantNacional').textContent = data.form3_4.cantNacional || '0';
                                    document.getElementById('cantidadRegional').textContent = data.form3_4.cantidadRegional || '0';
                                    document.getElementById('cantPreparacion').textContent = data.form3_4.cantPreparacion || '0';
                                    document.getElementById('cantInternacional2').textContent = data.form3_4.cantInternacional2 || '0';
                                    document.getElementById('cantNacional2').textContent = data.form3_4.cantNacional2 || '0';
                                    document.getElementById('cantidadRegional2').textContent = data.form3_4.cantidadRegional2 || '0';
                                    document.getElementById('cantPreparacion2').textContent = data.form3_4.cantPreparacion2 || '0';

                                    // Populate hidden inputs
                                    document.querySelector('input[name="user_id"]').value = data.form3_4.user_id || '';
                                    document.querySelector('input[name="email"]').value = data.form3_4.email || '';
                                    document.querySelector('input[name="user_type"]').value = data.form3_4.user_type || '';

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
                                const selectedResponseForm3_4 = dictaminatorResponses.form3_4.find(res => res.email === email);
                                if (selectedResponseForm3_4)  {

                                    document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_4.dictaminador_id || '0';
                                    document.querySelector('input[name="user_id"]').value = selectedResponseForm3_4.user_id || '';
                                    document.querySelector('input[name="email"]').value = selectedResponseForm3_4.email || '';
                                    document.querySelector('input[name="user_type"]').value = selectedResponseForm3_4.user_type || '';

                                    document.getElementById('score3_4').textContent = selectedResponseForm3_4.score3_4 || '0';
                                    document.getElementById('cantInternacional').textContent = selectedResponseForm3_4.cantInternacional || '0';
                                    document.getElementById('cantNacional').textContent = selectedResponseForm3_4.cantNacional || '0';
                                    document.getElementById('cantidadRegional').textContent = selectedResponseForm3_4.cantidadRegional || '0';
                                    document.getElementById('cantPreparacion').textContent = selectedResponseForm3_4.cantPreparacion || '0';
                                    document.getElementById('cantInternacional2').textContent = selectedResponseForm3_4.cantInternacional2 || '0';
                                    document.getElementById('cantNacional2').textContent = selectedResponseForm3_4.cantNacional2 || '0';
                                    document.getElementById('cantidadRegional2').textContent = selectedResponseForm3_4.cantidadRegional2 || '0';
                                    document.getElementById('cantPreparacion2').textContent = selectedResponseForm3_4.cantPreparacion2 || '0';

                                    document.getElementById('comision3_4').textContent = selectedResponseForm3_4.comision3_4 || '0';
                                    document.querySelector('span[name="comInternacional"]').textContent = selectedResponseForm3_4.comInternacional || '0';
                                    document.querySelector('span[name="comNacional"]').textContent = selectedResponseForm3_4.comNacional || '0';
                                    document.querySelector('span[name="comRegional"]').textContent = selectedResponseForm3_4.comRegional || '0';
                                    document.querySelector('span[name="comPreparacion"]').textContent = selectedResponseForm3_4.comPreparacion || '0';
                                    document.querySelector('span[name="obs3_4_1"]').textContent = selectedResponseForm3_4.obs3_4_1 || '';
                                    document.querySelector('span[name="obs3_4_2"]').textContent = selectedResponseForm3_4.obs3_4_2 || '';
                                    document.querySelector('span[name="obs3_4_3"]').textContent = selectedResponseForm3_4.obs3_4_3 || '';
                                    document.querySelector('span[name="obs3_4_4"]').textContent = selectedResponseForm3_4.obs3_4_4 || '';

                                } else {

                                    console.error('No form3_4 data found for the selected dictaminador.');
                                    // Reset input values if no data found
                                    document.querySelector('input[name="dictaminador_id"]').value = '0';
                                    document.querySelector('input[name="user_id"]').value = '0';
                                    document.querySelector('input[name="email"]').value = '';
                                    document.querySelector('input[name="user_type"]').value = '';

                                    document.getElementById('score3_4').textContent = '0';
                                    document.getElementById('cantInternacional').textContent = '0';
                                    document.getElementById('cantNacional').textContent = '0';
                                    document.getElementById('cantidadRegional').textContent = '0';
                                    document.getElementById('cantPreparacion').textContent = '0';
                                    document.getElementById('cantInternacional2').textContent = '0';
                                    document.getElementById('cantNacional2').textContent = '0';
                                    document.getElementById('cantidadRegional2').textContent = '0';
                                    document.getElementById('cantPreparacion2').textContent = '0';
                                    document.getElementById('comision3_4').textContent = '0';
                                    document.querySelector('span[name="comInternacional"]').textContent = '0';
                                    document.querySelector('span[name="comNacional"]').textContent = '0';
                                    document.querySelector('span[name="comRegional"]').textContent = '0';
                                    document.querySelector('span[name="comPreparacion"]').textContent = '0';
                                    document.querySelector('span[name="obs3_4_1"]').textContent = '';
                                    document.querySelector('span[name="obs3_4_2"]').textContent = '';
                                    document.querySelector('span[name="obs3_4_3"]').textContent = '';
                                    document.querySelector('span[name="obs3_4_4"]').textContent = '';
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
            formData['cantInternacional'] = document.getElementById('cantInternacional').textContent;
            formData['cantNacional'] = document.getElementById('cantNacional').textContent;
            formData['cantidadRegional'] = document.getElementById('cantidadRegional').textContent;
            formData['cantPreparacion'] = document.getElementById('cantPreparacion').textContent;
            formData['cantInternacional2'] = document.getElementById('cantInternacional2').textContent;
            formData['cantNacional2'] = document.getElementById('cantNacional2').textContent;
            formData['cantidadRegional2'] = document.getElementById('cantidadRegional2').textContent;
            formData['cantPreparacion2'] = document.getElementById('cantPreparacion2').textContent;
            formData['comInternacional'] = form.querySelector('input[id="comInternacional"]').value;
            formData['comNacional'] = form.querySelector('input[id="comNacional"]').value;
            formData['comRegional'] = form.querySelector('input[id="comRegional"]').value;
            formData['comPreparacion'] = form.querySelector('input[id="comPreparacion"]').value;
            formData['score3_4'] = document.getElementById('score3_4').textContent;
            formData['comision3_4'] = document.getElementById('comision3_4').textContent;

            // Observations
            formData['obs3_4_1'] = form.querySelector('input[name="obs3_4_1"]').value;
            formData['obs3_4_2'] = form.querySelector('input[name="obs3_4_2"]').value;
            formData['obs3_4_3'] = form.querySelector('input[name="obs3_4_3"]').value;
            formData['obs3_4_4'] = form.querySelector('input[name="obs3_4_4"]').value;

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

                // Mensaje al usuario
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