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

    @media print{
    .datosPrimarios{
        font-size: .9rem;
    }

            #convocatoria,
        #convocatoria2,
        #piedepagina1,
        #piedepagina2 {
            margin: 0;
            font-size: 1rem;
        }



        .page-number:before {
            content: "Página " counter(page) " de 33";
        }

        .secretaria-style {
            font-weight: normal;
            font-size: 14px;
            margin-top: 10px;
            text-align: left;
        }

        .secretaria-style #piedepagina1 {
            display: flex;
            justify-content: flex-end;
            font-weight: normal !important;
            /* Opcional, si quieres menos énfasis */
            color: #000;
            font-size: .7rem;
        }

        .dictaminador-style {
            font-weight: normal;
            font-size: 16px;
            margin-top: 10px;
            text-align: center;
        }

        .dictaminador-style #piedepagina2 {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
            font-weight: normal !important;
        }

        /* Estilo para secretaria o userType vacío */
        .secretaria-style #piedepagina2 {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            font-weight: normal !important;
            display: inline-block;
            font-size: .7rem;
        }

    /* Mostrar el footer correcto según la página */
    .page-break[data-page="23"] .first-page-footer {
        display: table-footer-group !important;
    }

    .page-break[data-page="24"] .second-page-footer {
        display: table-footer-group !important;
    }

    .page-number:before {
        content: "Página " counter(page) " de 33";
    }
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
        <!-- Form for Part 3_16 -->
        <form id="form3_16" method="POST" onsubmit="event.preventDefault(); submitForm('/store-form316', 'form3_16');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
        <!--3.16 Actividades de arbitraje, revisión, correción y edición -->
           <div>
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">30</label>
            </h4>
           </div>
            <table class="table table-sm tutorias">
                <thead>
                    <tr>
                        <th>
                            <h3></h3>
                        </th>
                        <th>
                            <h3></h3>
                        </th>
                        <th>
                            <h3 class="datosPrimarios">Investigación</h3>
                        </th>
                    </tr>
                </thead>
                <x-sub-headers-form3_16 :componentIndex="0" />
                <tbody data-page="23">
                    <tr>
                        <td>a)</td>
                        <td>Arbitraje a proyectos de investigación</td>
                        <td>Internacional</td>
                        <td id="puntajeArbInt"><b>30</b></td>
                        <td id="cantArbInt"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalArbInt"></td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionArbInt" value="{{ oldValueOrDefault('comisionArbInt') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span id="comisionArbInt"></span>
                        @endif
                        </td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsArbInt">
                        @else
                            <span id="obsArbInt"></span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>Arbitraje a proyectos de investigación</td>
                        <td>Nacional</td>
                        <td id="puntajeArbINac"><b>25</b></td>
                        <td id="cantArbNac"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalArbNac"></td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionArbNac" value="{{ oldValueOrDefault('comisionArbNac') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span id="comisionArbNac"></span>
                        @endif
                        
                        </td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsArbNac">
                        @else
                            <span id="obsArbNac"></span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td>Arbitraje de publicaciones</td>
                        <td>Internacional</td>
                        <td id="puntajePubInt"><b>20</b></td>
                        <td id="cantPubInt"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalPubInt"></td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionPubInt" value="{{ oldValueOrDefault('comisionPubInt') }}"
                                oninput="onActv3Comision3_16()"></td>
                        @else
                        <span id="comisionPubInt"></span>

                        @endif
                        <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsPubInt">
                        @else

                        <span id="obsPubInt"></span>
                        @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; justify-content: space-between;padding-top: 80px;">
                <div id="convocatoria">
                    <!-- Mostrar convocatoria -->
                    @if(isset($convocatoria))
                        <div style="margin-right: -500px;">
                            <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                        </div>
                    @endif
                </div>
                <div id="piedepagina1"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === '' ? 'secretaria-style' : '') }}">
                    Página 23 de 33
                </div>
            </div><br>
            <table class="table table-sm tutorias">
                <x-sub-headers-form3_16 :componentIndex="1" />
                <tbody data-page="24">
                    <tr>
                        <td>d)</td>
                        <td>Arbitraje de publicaciones</td>
                        <td>Nacional</td>
                        <td id="puntajePubINac"><b>10</b></td>
                        <td id="cantPubNac"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalPubNac"></td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionPubNac" value="{{ oldValueOrDefault('comisionPubNac') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span id="comisionPubNac"></span>
                        @endif
                        </td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsPubNac">
                        @else
                            <span id="obsPubNac"></span>
                        @endif
                        </td>
                    </tr>                    
                    <tr>
                        <td>e)</td>
                        <td>Revisor(a) de libros, corrector(a)</td>
                        <td>Internacional</td>
                        <td id="puntajeRevInt"><b>30</b></td>
                        <td id="cantRevInt"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalRevInt"></td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionRevInt" value="{{ oldValueOrDefault('comisionRevInt') }}"
                                oninput="onActv3Comision3_16()"></td>
                        @else
                            <span id="comisionRevInt"> </span>
                        @endif
                        <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsRevInt">
                        @else
                            <span id="obsRevInt"> </span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>f)</td>
                        <td>Revisor(a) de libros, corrector(a)</td>
                        <td>Nacional</td>
                        <td id="puntajeRevINac"><b>25</b></td>
                        <td id="cantRevNac"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalRevNac"></td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionRevNac" value="{{ oldValueOrDefault('comisionRevNac') }}"
                                oninput="onActv3Comision3_16()"></td>
                        @else
                            <span id="comisionRevNac"> </span>
                        @endif
                        <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsRevNac">
                        @else
                            <span id="obsRevNac"> </span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>g)</td>
                        <td>Consejo editorial de revista, edición de revista</td>
                        <td>----</td>
                        <td id="puntajeRevista"><b>10</b></td>
                        <td id="cantRevista"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalRevista"></td>
                        <td>
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionRevista" value="{{ oldValueOrDefault('comisionRevista') }}"
                                oninput="onActv3Comision3_16()"></td>
                        @else
                            <span id="comisionRevista"> </span>
                        @endif
                        <td>
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsRevista">
                        @else
                            <span id="obsRevista"> </span>
                        @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.16-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>

                        <th class="descripcion"><b>Institución que lo solicita. En el caso de la UABCS,
                                DIIP, SG, CA,
                                JD.</b>
                        </th>
                        <th>
                            @if($userType != '')
                            <button id="btn3_16" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                            @endif   
                        </th>
                    </tr>
                </thead>
            </table> 

            <!--convocatoria 2-->
            <div style="display: flex; justify-content: space-between;padding-top: 150px;">
                <div id="convocatoria2">
                    <!-- Mostrar convocatoria -->
                    @if(isset($convocatoria))

                        <div style="margin-right: -700px;">
                            <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                        </div>
                    @endif
                </div>

                <div id="piedepagina2"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === '' ? 'secretaria-style' : '') }}">
                    Página 24 de 33
                </div>
            </div>
            </form>
    </main>

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
                                        // Update all elements with the class 'score3_19'
                                        const scoreElements = document.querySelectorAll('.score3_16');
                                        scoreElements.forEach(element => {
                                            element.textContent = data.form3_16.score3_16 || '0';
                                        });

                                    // Cantidades
                                    document.getElementById('cantArbInt').textContent = data.form3_16.cantArbInt || '0';
                                    document.getElementById('cantArbNac').textContent = data.form3_16.cantArbNac || '0';
                                    document.getElementById('cantPubInt').textContent = data.form3_16.cantPubInt || '0';
                                    document.getElementById('cantPubNac').textContent = data.form3_16.cantPubNac || '0';
                                    document.getElementById('cantRevInt').textContent = data.form3_16.cantRevInt || '0';
                                    document.getElementById('cantRevNac').textContent = data.form3_16.cantRevNac || '0';
                                    document.getElementById('cantRevista').textContent = data.form3_16.cantRevista || '0';

                                    // Subtotales
                                    document.getElementById('subtotalArbInt').textContent = data.form3_16.subtotalArbInt || '0';
                                    document.getElementById('subtotalArbNac').textContent = data.form3_16.subtotalArbNac || '0';
                                    document.getElementById('subtotalPubInt').textContent = data.form3_16.subtotalPubInt || '0';
                                    document.getElementById('subtotalPubNac').textContent = data.form3_16.subtotalPubNac || '0';
                                    document.getElementById('subtotalRevInt').textContent = data.form3_16.subtotalRevInt || '0';
                                    document.getElementById('subtotalRevNac').textContent = data.form3_16.subtotalRevNac || '0';
                                    document.getElementById('subtotalRevista').textContent = data.form3_16.subtotalRevista || '0';

                                    //  hidden inputs
                                    document.querySelector('input[name="user_id"]').value = data.form3_16.user_id || '';
                                    document.querySelector('input[name="email"]').value = data.form3_16.email || '';
                                    document.querySelector('input[name="user_type"]').value = data.form3_16.user_type || '';

                                    // Actualizar convocatoria
                                    const convocatoriaElement = document.getElementById('convocatoria');
                                    const convocatoriaElement2 = document.getElementById('convocatoria2');
                                    if (convocatoriaElement) {
                                        if (data.form1) {
                                            convocatoriaElement.textContent = data.form1.convocatoria || '';
                                            convocatoriaElement2.textContent = data.form1.convocatoria || '';
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
                                        const convocatoriaElement2 = document.getElementById('convocatoria2');
                                        // Mostrar la convocatoria si existe
                                        if (convocatoriaElement) {
                                            if (data.docente.convocatoria) {
                                                convocatoriaElement.textContent = data.docente.convocatoria;
                                                convocatoriaElement2.textContent = data.docente.convocatoria;
                                            } else {
                                                convocatoriaElement.textContent = 'Convocatoria no disponible';
                                                convocatoriaElement2.textContent = 'Convocatoria no disponible';
                                            }
                                        }
                                    }
                                });
                            // Lógica para obtener datos de DictaminatorsResponseForm2
                            try {
                                const response = await fetch('/get-dictaminators-responses');
                                const dictaminatorResponses = await response.json();
                                // Filtrar la entrada correspondiente al email seleccionado
                                const selectedResponseForm3_16 = dictaminatorResponses.form3_16.find(res => res.email === email);
                                if (selectedResponseForm3_16) {

                                    document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_16.dictaminador_id || '0';
                                    document.querySelector('input[name="user_id"]').value = selectedResponseForm3_16.user_id || '';
                                    document.querySelector('input[name="email"]').value = selectedResponseForm3_16.email || '';
                                    document.querySelector('input[name="user_type"]').value = selectedResponseForm3_16.user_type || '';


                                    // Cantidades
                                    document.getElementById('cantArbInt').textContent = selectedResponseForm3_16.cantArbInt || '0';
                                    document.getElementById('cantArbNac').textContent = selectedResponseForm3_16.cantArbNac || '0';
                                    document.getElementById('cantPubInt').textContent = selectedResponseForm3_16.cantPubInt || '0';
                                    document.getElementById('cantPubNac').textContent = selectedResponseForm3_16.cantPubNac || '0';
                                    document.getElementById('cantRevInt').textContent = selectedResponseForm3_16.cantRevInt || '0';
                                    document.getElementById('cantRevNac').textContent = selectedResponseForm3_16.cantRevNac || '0';
                                    document.getElementById('cantRevista').textContent = selectedResponseForm3_16.cantRevista || '0';


                                    // Subtotales
                                    document.getElementById('subtotalArbInt').textContent = selectedResponseForm3_16.subtotalArbInt || '0';
                                    document.getElementById('subtotalArbNac').textContent = selectedResponseForm3_16.subtotalArbNac || '0';
                                    document.getElementById('subtotalPubInt').textContent = selectedResponseForm3_16.subtotalPubInt || '0';
                                    document.getElementById('subtotalPubNac').textContent = selectedResponseForm3_16.subtotalPubNac || '0';
                                    document.getElementById('subtotalRevInt').textContent = selectedResponseForm3_16.subtotalRevInt || '0';
                                    document.getElementById('subtotalRevNac').textContent = selectedResponseForm3_16.subtotalRevNac || '0';
                                    document.getElementById('subtotalRevista').textContent = selectedResponseForm3_16.subtotalRevista || '0';

                                    // Comisiones
                                    document.querySelector('#comisionArbInt').textContent = selectedResponseForm3_16.comisionArbInt || '0';
                                    document.querySelector('#comisionArbNac').textContent = selectedResponseForm3_16.comisionArbNac || '0';
                                    document.querySelector('#comisionPubInt').textContent = selectedResponseForm3_16.comisionPubInt || '0';
                                    document.querySelector('#comisionPubNac').textContent = selectedResponseForm3_16.comisionPubNac || '0';
                                    document.querySelector('#comisionRevInt').textContent = selectedResponseForm3_16.comisionRevInt || '0';
                                    document.querySelector('#comisionRevNac').textContent = selectedResponseForm3_16.comisionRevNac || '0';
                                    document.querySelector('#comisionRevista').textContent = selectedResponseForm3_16.comisionRevista || '0';
                                    // Observaciones
                                    document.querySelector('#obsArbInt').textContent = selectedResponseForm3_16.obsArbInt || '';
                                    document.querySelector('#obsArbNac').textContent = selectedResponseForm3_16.obsArbNac || '';
                                    document.querySelector('#obsPubInt').textContent = selectedResponseForm3_16.obsPubInt || '';
                                    document.querySelector('#obsPubNac').textContent = selectedResponseForm3_16.obsPubNac || '';
                                    document.querySelector('#obsRevInt').textContent = selectedResponseForm3_16.obsRevInt || '';
                                    document.querySelector('#obsRevNac').textContent = selectedResponseForm3_16.obsRevNac || '';
                                    document.querySelector('#obsRevista').textContent = selectedResponseForm3_16.obsRevista || '';

                                    // Update all elements with the class 'score3_16'
                                        const scoreElements = document.querySelectorAll('.score3_16');
                                        scoreElements.forEach(element => {
                                            element.textContent = selectedResponseForm3_16.score3_16 || '0';
                                        });

                                        // Update all elements with the class 'comision3_16'
                                        const comisionElements = document.querySelectorAll('.comision3_16');
                                        comisionElements.forEach(element => {
                                            element.textContent = selectedResponseForm3_16.comision3_16 || '0';
                                        });

                                } else {
                                    console.error('No form3_16 data found for the selected dictaminador.');

                                    // Reset input values if no data found
                                    document.querySelector('input[name="dictaminador_id"]').value = '0';
                                    document.querySelector('input[name="user_id"]').value = '0';
                                    document.querySelector('input[name="email"]').value = '';
                                    document.querySelector('input[name="user_type"]').value = '';

                                    document.querySelector('.score3_16').textContent = '0';

                                    // Cantidades
                                    document.getElementById('cantArbInt').textContent = '0';
                                    document.getElementById('cantArbNac').textContent = '0';
                                    document.getElementById('cantPubInt').textContent = '0';
                                    document.getElementById('cantPubNac').textContent = '0';
                                    document.getElementById('cantRevInt').textContent = '0';
                                    document.getElementById('cantRevNac').textContent = '0';
                                    document.getElementById('cantRevista').textContent = '0';

                                    // Subtotales
                                    document.getElementById('subtotalArbInt').textContent = '0';
                                    document.getElementById('subtotalArbNac').textContent = '0';
                                    document.getElementById('subtotalPubInt').textContent = '0';
                                    document.getElementById('subtotalPubNac').textContent = '0';
                                    document.getElementById('subtotalRevInt').textContent = '0';
                                    document.getElementById('subtotalRevNac').textContent = '0';
                                    document.getElementById('subtotalRevista').textContent = '0';

                                    // Comisiones
                                    document.querySelector('#comisionArbInt').textContent = '0';
                                    document.querySelector('#comisionArbNac').textContent = '0';
                                    document.querySelector('#comisionPubInt').textContent = '0';
                                    document.querySelector('#comisionPubNac').textContent = '0';
                                    document.querySelector('#comisionRevInt').textContent = '0';
                                    document.querySelector('#comisionRevNac').textContent = '0';
                                    document.querySelector('#comisionRevista').textContent = '0';


                                    // Observaciones
                                    document.querySelector('#obsArbInt').textContent = '';
                                    document.querySelector('#obsArbNac').textContent = '';
                                    document.querySelector('#obsPubInt').textContent = '';
                                    document.querySelector('#obsPubNac').textContent = '';
                                    document.querySelector('#obsRevInt').textContent = '';
                                    document.querySelector('#obsRevNac').textContent = '';
                                    document.querySelector('#obsRevista').textContent = '';

                                    document.querySelector('.comision3_16').textContent = '0';
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
            formData['cantArbInt'] = form.querySelector('td[id="cantArbInt"]').textContent;
            formData['cantArbNac'] = form.querySelector('td[id="cantArbNac"]').textContent;
            formData['cantPubInt'] = form.querySelector('td[id="cantPubInt"]').textContent;
            formData['cantPubNac'] = form.querySelector('td[id="cantPubNac"]').textContent;
            formData['cantRevInt'] = form.querySelector('td[id="cantRevInt"]').textContent;
            formData['cantRevNac'] = form.querySelector('td[id="cantRevNac"]').textContent;
            formData['cantRevista'] = form.querySelector('td[id="cantRevista"]').textContent;


            // Subtotales
            formData['subtotalArbInt'] = document.getElementById('subtotalArbInt').textContent;
            formData['subtotalArbNac'] = document.getElementById('subtotalArbNac').textContent;
            formData['subtotalPubInt'] = document.getElementById('subtotalPubInt').textContent;
            formData['subtotalPubNac'] = document.getElementById('subtotalPubNac').textContent;   
            formData['subtotalRevInt'] = document.getElementById('subtotalRevInt').textContent;
            formData['subtotalRevNac'] = document.getElementById('subtotalRevNac').textContent;
            formData['subtotalRevista'] = document.getElementById('subtotalRevista').textContent;                   


            // Comisiones
            formData['comisionArbInt'] = form.querySelector('input[id="comisionArbInt"]').value;
            formData['comisionArbNac'] = form.querySelector('input[id="comisionArbNac"]').value;
            formData['comisionPubInt'] = form.querySelector('input[id="comisionPubInt"]').value;
            formData['comisionPubNac'] = form.querySelector('input[id="comisionPubNac"]').value;
            formData['comisionRevInt'] = form.querySelector('input[id="comisionRevInt"]').value;
            formData['comisionRevNac'] = form.querySelector('input[id="comisionRevNac"]').value;
            formData['comisionRevista'] = form.querySelector('input[id="comisionRevista"]').value;

            // Observaciones
            formData['obsArbInt'] = form.querySelector('input[id="obsArbInt"]').value;
            formData['obsArbNac'] = form.querySelector('input[id="obsArbNac"]').value;
            formData['obsPubInt'] = form.querySelector('input[id="obsPubInt"]').value;
            formData['obsPubNac'] = form.querySelector('input[id="obsPubNac"]').value;
            formData['obsRevInt'] = form.querySelector('input[id="obsRevInt"]').value;
            formData['obsRevNac'] = form.querySelector('input[id="obsRevNac"]').value;
            formData['obsRevista'] = form.querySelector('input[id="obsRevista"]').value;


            formData['score3_16'] = document.querySelector('.score3_16').textContent;
            
            formData['comision3_16'] = document.querySelector('.comision3_16').textContent;

            // Observations

            console.log('Form data:', formData);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', 
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