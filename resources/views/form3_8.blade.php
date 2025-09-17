@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
@endphp
<!DOCTYPE html>
<html lang="">

<head>
    <link rel="icon" href="{{ $logo }}" type="image/png">
    <title>Evaluación docente</title>    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-head-resources />
    <link href="{{ asset('css/onePage.css') }}" rel="stylesheet">
    <style>
        .descripcion{
            width: fit-content;
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
        <form id="form3_8" method="POST" onsubmit="event.preventDefault(); submitForm('/store-form38', 'form3_8');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <div>
                <!--3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de educación, continua o de formación y capacitación docente-->
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
                            <td id="seccion3_8" colspan=1 class="punto3_8" scope=col style="padding:-60px;">3.8  Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de educación,
continua o de formación y capacitación docente </td>
                            <td class="punto3_8">Factor</td>
                            <td class="punto3_8">Horas</td>
                            <td id="score3_8" for="">0</td>
                            <td id="comision3_8">0</td>
            
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td>1 por cada hora</td>
                            <td id="p3_8">1</td>
                            <td id="puntaje3_8"></td>
                            <td id="puntajeHoras3_8" class="rightSelect"></td>
                            <td class="rightSelect">
                                @if ($userType == 'dictaminador')
                                    <input type="number" step="0.01" id="comisionDict3_8" name="comisionDict3_8" oninput="onActv3Comision3_8()" value="{{ oldValueOrDefault('comisionDict3_8') }}">
                                @else
                                    <span id="comisionDict3_8" name="comisionDict3_8"></span>
                                @endif
                                
                            </td>
                            <td>
                                @if ($userType == 'dictaminador')
                                    <input class="table-header" id="obs3_8_1" name="obs3_8_1" type="text">
                                @else
                                    <span id="obs3_8_1" name="obs3_8_1"></span>
                                @endif
                                
                            </td>
                        </tr>
                    </thead>
                    <!--Tabla informativa Acreditacion Actividad 3.8-->
                    <table>
                        <thead>
                            <tr>
                                <th class="acreditacion" scope="col">Acreditacion: </th>
            
                                <th class="descripcion" id="form3_8Acreditacion"><b>
                                    *JD,CAAC, DDCE, DDIE, SA,DIIP, según
                                        corresponda. Cuando sea en
                                        instituciones externas, presentar constancia de la
                                        institución y el convenio acuerdo con
                                        la
                                        UABCS.</b> </th>
                            </tr>
                        </thead>
                    </table>
                </tbody>
            </table>
            @if ($userType != '')
                <button id="btn3_8" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
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
        <x-form-renderer :forms="[['view' => 'form3_8', 'startPage' => 12, 'endPage' => 12]]" />
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
            const response = await fetch('/get-docentes');
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
                    axios.get('/get-docente-data', { params: { email } })
                        .then(response => {
                            const data = response.data;
                            // Populate fields with fetched data
                            document.getElementById('score3_8').textContent = data.form3_8.score3_8 || '0';
                            document.getElementById('puntaje3_8').textContent = data.form3_8.puntaje3_8 || '0';
                            document.getElementById('puntajeHoras3_8').textContent = data.form3_8.puntajeHoras3_8 || '0';


                            // Populate hidden inputs
                            document.querySelector('input[name="user_id"]').value = data.form3_8.user_id || '';
                            document.querySelector('input[name="email"]').value = data.form3_8.email || '';
                            document.querySelector('input[name="user_type"]').value = data.form3_8.user_type || '';

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
            const response = await fetch('/get-docentes');

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
                    axios.get('/get-docente-data', { params: { email } })
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
                        const response = await fetch('/get-dictaminators-responses');
                        const dictaminatorResponses = await response.json();
                        // Filtrar la entrada correspondiente al email seleccionado
                        const selectedResponseForm3_8 = dictaminatorResponses.form3_8.find(res => res.email === email);
                        if (selectedResponseForm3_8) {

                            document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_8.dictaminador_id || '0';
                            document.querySelector('input[name="user_id"]').value = selectedResponseForm3_8.user_id || '';
                            document.querySelector('input[name="email"]').value = selectedResponseForm3_8.email || '';
                            document.querySelector('input[name="user_type"]').value = selectedResponseForm3_8.user_type || '';

                            document.getElementById('score3_8').textContent = selectedResponseForm3_8.score3_8 || '0';
                            document.getElementById('puntaje3_8').textContent = selectedResponseForm3_8.puntaje3_8 || '0';
                            document.getElementById('puntajeHoras3_8').textContent = selectedResponseForm3_8.puntajeHoras3_8 || '0';

                            document.getElementById('comision3_8').textContent = selectedResponseForm3_8.comision3_8 || '0';
                            document.querySelector('span[name="comisionDict3_8"]').textContent = selectedResponseForm3_8.comisionDict3_8 || '0';
                            document.querySelector('span[name="obs3_8_1"]').textContent = selectedResponseForm3_8.obs3_8_1 || '';


                        } else {
                            console.error('No form3_8 data found for the selected dictaminador.');
                            // Reset input values if no data found
                            document.querySelector('input[name="dictaminador_id"]').value = '0';
                            document.querySelector('input[name="user_id"]').value = '0';
                            document.querySelector('input[name="email"]').value = '';
                            document.querySelector('input[name="user_type"]').value = '';

                            document.getElementById('score3_8').textContent = '0';
                            document.getElementById('puntaje3_8').textContent = '0';
                            document.getElementById('puntajeHoras3_8').textContent = '0';
                            document.getElementById('comision3_8').textContent = '0';
                            document.querySelector('span[name="comisionDict3_8"]').textContent = '0';
                            document.querySelector('span[name="obs3_8_1"]').textContent = '';


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
            formData['puntaje3_8'] = document.getElementById('puntaje3_8').textContent;
            formData['puntajeHoras3_8'] = document.getElementById('puntajeHoras3_8').textContent;
            formData['comisionDict3_8'] = form.querySelector('input[id="comisionDict3_8"]').value;
            formData['score3_8'] = document.getElementById('score3_8').textContent;
            formData['comision3_8'] = document.getElementById('comision3_8').textContent;

            // Observations
            formData['obs3_8_1'] = form.querySelector('input[name="obs3_8_1"]').value;

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