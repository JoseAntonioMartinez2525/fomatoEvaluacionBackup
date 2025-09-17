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
        <!-- Form for Part 3_10 -->
        <form id="form3_10" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-form310', 'form3_10');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <!--3.10 Trabajos dirigidos para la titulación de estudiantes-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">115</label>
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
                        <th id="seccion3_10" class="acreditacion" colspan=2>3.10 Tutorías a estudiantes</th>
                        <th class="acreditacion">Puntaje</th>
                        <th class="acreditacion">cantidad</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th id="score3_10">0</th>
                        <th id="comision3_10">0</th>
                        <th class="acreditacion" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <thead>
                        <tr>
                            <!--Tutorias a estudantes 3_10 individuales, grupales -->
                            <td>a)</td>
                            <td>Por alumno(a) por semestre, grupales</td>
                            <td id="puntajeGrupales"><b>3</b> </td>
                            <td id="grupalesCant"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td id="evaluarGrupales"></td>
                            <td id="comisionGrupal">
                            @if ($userType == 'dictaminador')

                                <input type="number" step="0.01" id="comisionGrupal" name="comisionGrupal" oninput="onActv3Comision3_10()"
                                    value="{{ oldValueOrDefault('comisionGrupal') }}">                         
                            @else
                                <span id="comisionGrupal" name="comisionGrupal"></span>
                            @endif    
                            </td>
                            <td id="obsGrupal">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsGrupal" name="obsGrupal">
                            @else
                            <span id="obsGrupal" name="obsGrupal"></span>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td>b)</td>
                            <td>Por alumno(a) por semestre, individuales</td>
                            <td id="puntajeIndividual"><b>6</b></td>
                            <td id="individualCant"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td id="evaluarIndividual"></td>
                            <td id="comisionIndividual">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionIndividual" name="comisionIndividual" oninput="onActv3Comision3_10()"
                                        value="{{ oldValueOrDefault('comisionIndividual') }}"> 
                            @else
                                <span id="comisionIndividual"  name="comisionIndividual"></span>
                            @endif    
                            </td>
                            <td id="obsIndividual">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsIndividual" name="obsIndividual">
                            @else
                            <span id="obsIndividual" name="obsIndividual"></span>
                            @endif
                            </td>
                        </tr>
                    </thead>
                    </tbody> 
                    </table>
                    <!--Tabla informativa Acreditacion Actividad 3.10-->
                    <table>
                        <thead>
                            <tr>
                                <th class="acreditacion" scope="col">Acreditacion: </th>

                                <th class="descripcion"><b>DDIE</b> </th>

                            </tr>
                        </thead>
                    </table>
                    @if ($userType != '')
                        <button id="btn3_10" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
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
        <x-form-renderer :forms="[['view' => 'form3_10', 'startPage' => 16, 'endPage' => 16]]" />
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
                                       document.getElementById('score3_10').textContent = data.form3_10.score3_10 || '0';
                                        document.getElementById('grupalesCant').textContent = data.form3_10.grupalesCant || '0';
                                        document.getElementById('evaluarGrupales').textContent = data.form3_10.evaluarGrupales || '0';
                                        document.getElementById('individualCant').textContent = data.form3_10.individualCant || '0';
                                        document.getElementById('evaluarIndividual').textContent = data.form3_10.evaluarIndividual || '0';

                                        // Populate hidden inputs
                                        document.querySelector('input[name="user_id"]').value = data.form3_10.user_id || '';
                                        document.querySelector('input[name="email"]').value = data.form3_10.email || '';
                                        document.querySelector('input[name="user_type"]').value = data.form3_10.user_type || '';

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
                                    const selectedResponseForm3_10 = dictaminatorResponses.form3_10.find(res => res.email === email);
                                    if (selectedResponseForm3_10) {

                                        document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_10.dictaminador_id || '0';
                                        document.querySelector('input[name="user_id"]').value = selectedResponseForm3_10.user_id || '';
                                        document.querySelector('input[name="email"]').value = selectedResponseForm3_10.email || '';
                                        document.querySelector('input[name="user_type"]').value = selectedResponseForm3_10.user_type || '';

                                        document.getElementById('grupalesCant').textContent = selectedResponseForm3_10.grupalesCant || '0';
                                        document.getElementById('evaluarGrupales').textContent = selectedResponseForm3_10.evaluarGrupales || '0';
                                        document.getElementById('individualCant').textContent = selectedResponseForm3_10.individualCant || '0';
                                        document.getElementById('evaluarIndividual').textContent = selectedResponseForm3_10.evaluarIndividual || '0';

                                        document.querySelector('span[name="comisionGrupal"]').textContent = selectedResponseForm3_10.comisionGrupal || '0';
                                        document.querySelector('span[name="comisionIndividual"]').textContent = selectedResponseForm3_10.comisionIndividual || '0';
                                        document.querySelector('span[name="obsGrupal"]').textContent = selectedResponseForm3_10.obsGrupal || '';
                                        document.querySelector('span[name="obsIndividual"]').textContent = selectedResponseForm3_10.obsIndividual || '';
                                        document.getElementById('score3_10').textContent = selectedResponseForm3_10.score3_10 || '0';
                                        document.getElementById('comision3_10').textContent = selectedResponseForm3_10.comision3_10 || '0';


                                    } else {

                                        console.error('No form3_10 data found for the selected dictaminador.');
                                        // Reset input values if no data found
                                        document.querySelector('input[name="dictaminador_id"]').value = '0';
                                        document.querySelector('input[name="user_id"]').value = '0';
                                        document.querySelector('input[name="email"]').value = '';
                                        document.querySelector('input[name="user_type"]').value = '';

                                        document.getElementById('grupalesCant').textContent = '0';
                                        document.getElementById('evaluarGrupales').textContent = '0';
                                        document.getElementById('individualCant').textContent = '0';
                                        document.getElementById('evaluarIndividual').textContent = '0';

                                        document.querySelector('span[name="comisionGrupal"]').textContent = '0';
                                        document.querySelector('span[name="comisionIndividual"]').textContent = '0';
                                        document.querySelector('span[name="obsGrupal"]').textContent = '';
                                        document.querySelector('span[name="obsIndividual"]').textContent = '';
                                        document.getElementById('score3_10').textContent = '0';
                                        document.getElementById('comision3_10').textContent = '0';
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
            formData['grupalesCant'] = document.getElementById('grupalesCant').textContent;
            formData['evaluarGrupales'] = document.getElementById('evaluarGrupales').textContent;
            formData['individualCant'] = document.getElementById('individualCant').textContent;
            formData['evaluarIndividual'] = document.getElementById('evaluarIndividual').textContent;
            formData['comisionGrupal'] = form.querySelector('input[id="comisionGrupal"]').value;
            formData['comisionIndividual'] = form.querySelector('input[id="comisionIndividual"]').value;
            formData['obsGrupal'] = document.querySelector('input[name="obsGrupal"]').textContent = '';
            formData['obsIndividual'] = document.querySelector('input[name="obsIndividual"]').textContent = '';
            formData['score3_10'] = document.getElementById('score3_10').textContent;
            formData['comision3_10'] = document.getElementById('comision3_10').textContent;


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