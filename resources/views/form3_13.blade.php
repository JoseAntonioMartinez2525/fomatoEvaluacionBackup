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
        <form id="form3_13" method="POST" onsubmit="event.preventDefault(); submitForm('/store-form313', 'form3_13');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <!--3.13 Proyectos académicos de investigación-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">130</label>
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
            <tr>
                <th id="seccion3_13" class="acreditacion" colspan=7>3.13 Proyectos académicos de
                    investigación</th>
                <th id="score3_13">0</th>
                <th id="comision3_13">0</th>
                <th class="acreditacion" scope="col">Observaciones</th>
            </tr>
            </thead>
            <thead>
                <tr>
                    <th class="acreditacion">Incisos</th>
                    <th class="acreditacion">Documento</th>
                    <th class="acreditacion">Puntaje</th>
                    <th class="acreditacion">Cantidad</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="acreditacion">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <!--Incisos 3.13-->
                <tr>
                    <td>a)</td>
                    <td>Inicio de proyecto de investigación con financiamiento externo</td>
                    <td id="puntajeInicioFinanExt">50</td>
                    <td id="cantInicioFinanExt" class="cantidad">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="subtotalInicioFinanExt"></td>
                    <td class="comision3_13">
                    @if ($userType == 'dictaminador')
                        <input type="number" step="0.01" id="comisionInicioFinancimientoExt" name="comisionInicioFinancimientoExt" value="{{ oldValueOrDefault('comisionInicioFinancimientoExt') }}" oninput="onActv3Comision3_13()">
                    @else
                        <span id="comisionInicioFinancimientoExt" name="comisionInicioFinancimientoExt"></span>
                    @endif
                        
                    </td>
                    <td class="comision3_13">
                    @if ($userType == 'dictaminador')
                        <input class="table-header" type="text" id="obsInicioFinancimientoExt" name="obsInicioFinancimientoExt">
                    @else
                        <span id="obsInicioFinancimientoExt" name="obsInicioFinancimientoExt"></span>
                    @endif                    
                    </td>
                </tr>
                <tr>
                    <td>b)</td>
                    <td>Inicio de proyecto de investigación interno, aprobado por CAAC</td>
                    <td id="puntajeInicioInvInterno">25</td>
                    <td id="cantInicioInvInterno" class="cantidad"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="subtotalInicioInvInterno"></td>
                    <td class="comision3_13">
                     @if ($userType == 'dictaminador')   
                        <input type="number" step="0.01" id="comisionInicioInvInterno" name="comisionInicioInvInterno" value="{{ oldValueOrDefault('comisionInicioInvInterno') }}" oninput="onActv3Comision3_13()">
                    @else
                        <span id="comisionInicioInvInterno"name="comisionInicioInvInterno" ></span>
                    @endif
                    </td>
                    <td class="comision3_13">
                    @if ($userType == 'dictaminador')
                        <input class="table-header" type="text" id="obsInicioInvInterno" name="obsInicioInvInterno">
                    @else
                        <span id="obsInicioInvInterno" name="obsInicioInvInterno"></span>
                    @endif
                    </td>
                </tr>
                <tr>
                    <td>c)</td>
                    <td>Reporte cumplido del periodo anual del proyecto de investigación con
                        financiamiento externo
                    </td>
                    <td id="puntajeReporteFinanciamExt">100</td>
                    <td id="cantReporteFinanciamExt" class="cantidad"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="subtotalReporteFinanciamExt"></td>
                    <td class="comision3_13">
                     @if ($userType == 'dictaminador')     
                        <input type="number" step="0.01" id="comisionReporteFinanciamExt" value="{{ oldValueOrDefault('comisionReporteFinanciamExt') }}" oninput="onActv3Comision3_13()">
                    @else
                    <span id="comisionReporteFinanciamExt" name="comisionReporteFinanciamExt"></span>
                    @endif
                    </td class="comision3_13">
                    <td>
                    @if ($userType == 'dictaminador')      
                        <input class="table-header" type="text" id="obsReporteFinanciamExt"></td>
                    @else
                    <span id="obsReporteFinanciamExt" name="obsReporteFinanciamExt"></span>
                    @endif
                </tr>
                <tr>
                    <td>d)</td>
                    <td>Reporte cumplido del periodo anual del proyecto de investigación interno,
                        aprobado por CAAC
                    </td>
                    <td id="puntajeReporteInvInt">100</td>
                    <td id="cantReporteInvInt" class="cantidad"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="subtotalReporteInvInt"></td>
                    <td class="comision3_13">
                    @if ($userType == 'dictaminador')      
                        <input type="number" step="0.01" id="comisionReporteInvInt" value="{{ oldValueOrDefault('comisionReporteInvInt') }}" oninput="onActv3Comision3_13()">
                    @else
                        <span id="comisionReporteInvInt" name="comisionReporteInvInt"></span>
                    @endif
                    </td>
                    <td class="comision3_13">
                    @if ($userType == 'dictaminador')      
                        <input class="table-header" type="text" id="obsReporteInvInt">
                    @else
                        <span id="obsReporteInvInt" name="obsReporteInvInt"></span>
                    @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <!--Tabla informativa Acreditacion Actividad 3.13-->
        <table>
            <thead>
                <tr>
                    <th class="acreditacion" scope="col">Acreditacion: </th>
        
                    <th class="descripcion"><b>CAAC, DIIP</b> </th>
        
                    <th>
                    @if ($userType != '')
                        <button id="btn3_13" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
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
            <x-form-renderer :forms="[['view' => 'form3_13', 'startPage' => 20, 'endPage' => 20]]" />
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
                                    document.getElementById('score3_13').textContent = data.form3_13.score3_13 || '0';

                                    // Cantidades
                                    document.getElementById('cantInicioFinanExt').textContent = data.form3_13.cantInicioFinanExt || '0';
                                    document.getElementById('cantInicioInvInterno').textContent = data.form3_13.cantInicioInvInterno || '0';
                                    document.getElementById('cantReporteFinanciamExt').textContent = data.form3_13.cantReporteFinanciamExt || '0';
                                    document.getElementById('cantReporteInvInt').textContent = data.form3_13.cantReporteInvInt || '0';


                                    // Subtotales
                                    document.getElementById('subtotalInicioFinanExt').textContent = data.form3_13.subtotalInicioFinanExt || '0';
                                    document.getElementById('subtotalInicioInvInterno').textContent = data.form3_13.subtotalInicioInvInterno || '0';
                                    document.getElementById('subtotalReporteFinanciamExt').textContent = data.form3_13.subtotalReporteFinanciamExt || '0';
                                    document.getElementById('subtotalReporteInvInt').textContent = data.form3_13.subtotalReporteInvInt || '0';

                                    //  hidden inputs
                                    document.querySelector('input[name="user_id"]').value = data.form3_13.user_id || '';
                                    document.querySelector('input[name="email"]').value = data.form3_13.email || '';
                                    document.querySelector('input[name="user_type"]').value = data.form3_13.user_type || '';

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
                                const selectedResponseForm3_13 = dictaminatorResponses.form3_13.find(res => res.email === email);
                                if (selectedResponseForm3_13) {

                                    document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_13.dictaminador_id || '0';
                                    document.querySelector('input[name="user_id"]').value = selectedResponseForm3_13.user_id || '';
                                    document.querySelector('input[name="email"]').value = selectedResponseForm3_13.email || '';
                                    document.querySelector('input[name="user_type"]').value = selectedResponseForm3_13.user_type || '';
                                    document.getElementById('score3_13').textContent = selectedResponseForm3_13.score3_13 || '0';
                                    document.getElementById('comision3_13').textContent = selectedResponseForm3_13.comision3_13 || '0';

                                    // Cantidades
                                    document.getElementById('cantInicioFinanExt').textContent = selectedResponseForm3_13.cantInicioFinanExt || '0';
                                    document.getElementById('cantInicioInvInterno').textContent = selectedResponseForm3_13.cantInicioInvInterno || '0';
                                    document.getElementById('cantReporteFinanciamExt').textContent = selectedResponseForm3_13.cantReporteFinanciamExt || '0';
                                    document.getElementById('cantReporteInvInt').textContent = selectedResponseForm3_13.cantReporteInvInt || '0';


                                    // Subtotales
                                    document.getElementById('subtotalInicioFinanExt').textContent = selectedResponseForm3_13.subtotalInicioFinanExt || '0';
                                    document.getElementById('subtotalInicioInvInterno').textContent = selectedResponseForm3_13.subtotalInicioInvInterno || '0';
                                    document.getElementById('subtotalReporteFinanciamExt').textContent = selectedResponseForm3_13.subtotalReporteFinanciamExt || '0';
                                    document.getElementById('subtotalReporteInvInt').textContent = selectedResponseForm3_13.subtotalReporteInvInt || '0';


                                    // Comisiones
                                    document.querySelector('#comisionInicioFinancimientoExt').textContent = selectedResponseForm3_13.comisionInicioFinancimientoExt || '0';
                                    document.querySelector('#comisionInicioInvInterno').textContent = selectedResponseForm3_13.comisionInicioInvInterno || '0';
                                    document.querySelector('#comisionReporteFinanciamExt').textContent = selectedResponseForm3_13.comisionReporteFinanciamExt || '0';
                                    document.querySelector('#comisionReporteInvInt').textContent = selectedResponseForm3_13.comisionReporteInvInt || '0';


                                    // Observaciones
                                    document.querySelector('#obsInicioFinancimientoExt').textContent = selectedResponseForm3_13.obsInicioFinancimientoExt || '';
                                    document.querySelector('#obsInicioInvInterno').textContent = selectedResponseForm3_13.obsInicioInvInterno || '';
                                    document.querySelector('#obsReporteFinanciamExt').textContent = selectedResponseForm3_13.obsReporteFinanciamExt || '';
                                    document.querySelector('#obsReporteInvInt').textContent = selectedResponseForm3_13.obsReporteInvInt || '';



                                } else {
                                    console.error('No form3_13 data found for the selected dictaminador.');

                                    // Reset input values if no data found
                                    document.querySelector('input[name="dictaminador_id"]').value = '0';
                                    document.querySelector('input[name="user_id"]').value = '0';
                                    document.querySelector('input[name="email"]').value = '';
                                    document.querySelector('input[name="user_type"]').value = '';

                                    document.getElementById('score3_13').textContent = '0';

                                    // Cantidades
                                    document.getElementById('cantInicioFinanExt').textContent = '0';
                                    document.getElementById('cantInicioInvInterno').textContent = '0';
                                    document.getElementById('cantReporteFinanciamExt').textContent = '0';
                                    document.getElementById('cantReporteInvInt').textContent = '0';


                                    // Subtotales
                                    document.getElementById('subtotalInicioFinanExt').textContent = '0';
                                    document.getElementById('subtotalInicioInvInterno').textContent = '0';
                                    document.getElementById('subtotalReporteFinanciamExt').textContent = '0';
                                    document.getElementById('subtotalReporteInvInt').textContent = '0';


                                    // Comisiones
                                    document.querySelector('#comisionInicioFinancimientoExt').textContent = '0';
                                    document.querySelector('#comisionInicioInvInterno').textContent = '0';
                                    document.querySelector('#comisionReporteFinanciamExt').textContent = '0';
                                    document.querySelector('#comisionReporteInvInt').textContent = '0';


                                    // Observaciones
                                    document.querySelector('#obsInicioFinancimientoExt').textContent = '';
                                    document.querySelector('#obsInicioInvInterno').textContent = '';
                                    document.querySelector('#obsReporteFinanciamExt').textContent = '';
                                    document.querySelector('#obsReporteInvInt').textContent = '';

                                    document.getElementById('comision3_13').textContent = '0';
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
            formData['cantInicioFinanExt'] = form.querySelector('td[id="cantInicioFinanExt"]').textContent;
            formData['cantInicioInvInterno'] = form.querySelector('td[id="cantInicioInvInterno"]').textContent;
            formData['cantReporteFinanciamExt'] = form.querySelector('td[id="cantReporteFinanciamExt"]').textContent;
            formData['cantReporteInvInt'] = form.querySelector('td[id="cantReporteInvInt"]').textContent;


            // Subtotales
            formData['subtotalInicioFinanExt'] = document.getElementById('subtotalInicioFinanExt').textContent;
            formData['subtotalInicioInvInterno'] = document.getElementById('subtotalInicioInvInterno').textContent;
            formData['subtotalReporteFinanciamExt'] = document.getElementById('subtotalReporteFinanciamExt').textContent;
            formData['subtotalReporteInvInt'] = document.getElementById('subtotalReporteInvInt').textContent;


            // Comisiones
            formData['comisionInicioFinancimientoExt'] = form.querySelector('input[id="comisionInicioFinancimientoExt"]').value;
            formData['comisionInicioInvInterno'] = form.querySelector('input[id="comisionInicioInvInterno"]').value;
            formData['comisionReporteFinanciamExt'] = form.querySelector('input[id="comisionReporteFinanciamExt"]').value;
            formData['comisionReporteInvInt'] = form.querySelector('input[id="comisionReporteInvInt"]').value;

            // Observaciones
            formData['obsInicioFinancimientoExt'] = form.querySelector('input[id="obsInicioFinancimientoExt"]').value;
            formData['obsInicioInvInterno'] = form.querySelector('input[id="obsInicioInvInterno"]').value;
            formData['obsReporteFinanciamExt'] = form.querySelector('input[id="obsReporteFinanciamExt"]').value;
            formData['obsReporteInvInt'] = form.querySelector('input[id="obsReporteInvInt"]').value;

            formData['score3_13'] = document.getElementById('score3_13').textContent;
            formData['comision3_13'] = document.getElementById('comision3_13').textContent;

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