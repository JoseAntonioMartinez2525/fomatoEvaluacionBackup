@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

// datos para cada formulario
$docenteConfig = [
        'formKey' => 'form3_5',
        'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data', 
        'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data35',
        'dictCollectionKey' => 'form3_5',
        'userTypeForDict' => '',
        'docenteMappings' => [
        // score y su copia
        'score3_5' => 'score3_5',     
        // cantidades y subtotales
        'cantDA' => 'cantDA',
        'cantCAAC' => 'cantCAAC',
        'cantDA2' => 'cantDA2',
        'cantCAAC2' => 'cantCAAC2',
        // comisiones y sus copias (puedes usar clase o id)
        '#comision3_5' => 'comision3_5',
        ],
        // Mapeos para respuestas de dictaminadores (si aplica)
    'dictMappings' => [
        // comisiones / comIncisos
        '#comision3_5' => 'comision3_5',
        'comDA' => 'comDA',
        'comNCAA' => 'comNCAA',
        // observaciones (span o elementos de texto)
        '#obs3_5_1' => 'obs3_5_1',
        '#obs3_5_2' => 'obs3_5_2',
        // repetir score/rc/stotals para sobrescribir si vienen desde dictaminador
        'score3_5' => 'score3_5',
        // cantidades y subtotales
        'cantDA' => 'cantDA',
        'cantCAAC' => 'cantCAAC',
        'cantDA2' => 'cantDA2',
        'cantCAAC2' => 'cantCAAC2',
    ],

    // Inputs ocultos que deben llenarse desde docenteData.form3_5
    'fillHiddenFrom' => [
        'user_id' => 'user_id',
        'email' => '',
        'user_type' => 'user_type',
    ],

    // Inputs ocultos que deben llenarse desde la respuesta de dictaminador seleccionada
    'fillHiddenFromDict' => [
        'dictaminador_id' => 'dictaminador_id',
        'user_id' => 'user_id',
        'email' => '',
        'user_type' => 'user_type',
    ],

    // comportamiento al no encontrar respuesta de dictaminador
    'resetOnNotFound' => false,
    'resetValues' => [
        // opcional: valores por defecto explícitos para targets 
        'score3_5' => '0',
        '#comision3_5' => '0',
        '#obs3_5_1' => '',
        '#obs3_5_2' => '',

    ],

];
@endphp
@php
if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'score3_5',
            'comision3_5',
            'cantDA', 'cantCAAC',
            'cantDA2', 'cantCAAC2',
            'comDA', 'comNCAA',
            'obs3_5_1', 'obs3_5_2',
        ],
        'exposeAs' => 'submitForm',
        'selectedEmailInputId' => 'selectedDocenteEmail',
        'searchInputId' => 'docenteSearch',
    ];
}

// Si se recibe un email desde la URL, se lo pasamos a la configuración del autocompletado.
if (isset($teacherEmailFromUrl) && $teacherEmailFromUrl) {
    $docenteConfig['preselectedEmail'] = $teacherEmailFromUrl;
}
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
<style>
     body.chrome @media print {
        #convocatoria {
            font-size: 1.2rem;
            color: blue;
            /* Ejemplo de estilo específico para Chrome */
        }
    }


    body.dark-mode #comDA, body.dark-mode #comNCAA{
        background-color: transparent;
    }

    body.dark-mode [id^="obs3_5"], body.dark-mode #cantDA, body.dark-mode #cantCAAC{
        color: white;
    }

[id^="btn3_"]{
    margin-left: 1200px;
}

body.dark-mode [id^="btn3_"]{
        background-color: #456483;
        color: floralwhite;
}

body.dark-mode [id^="btn3_"]:hover {
    background-color: #6a5b9f;
    
}
</style>
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

    $hasData = false;
    $checkFields = ['comision3_5'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }
$formId = $docenteConfigForm['formId'] ?? 'form3_5';
$formNumber = '35';
    @endphp
    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>


    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
            <!-- Buscando docentes -->
            <x-docente-search />
        @endif
    </div>
    <main class="container">
        <!-- Form for Part 3_5 -->
        <form id="form3_5" action="/formato-evaluacion/store-form35" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <div>
                <!-- 3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD y por CAAC  -->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">75</label>
                </h4>
            </div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col" colspan="4">Actividad</th>

            
                        <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                        </th>
                        
                    </tr>
                </thead>
                <tbody>
                    <thead>
                        <tr>
                            <td id="seccion3_5" colspan=4  class="punto3_5" scope=col>3.5
                                Asistencia, puntualidad y
                                permanencia en el desempeño docente, evaluada por el JD y por CAAC
                            </td>
                            <td id="score3_5">0</td>
                            <td id="comision3_5">0</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="punto3_5">Puntaje</td>
                            <td class="punto3_5">Cantidad</td>
                            <td colspan="2"></td>
                            <td class="text-center table-ajust" scope="col">Observaciones</td>

                        </tr>
                    </thead>
                    <thead>
                        <td class="punto3_5">a)</td>
                        <td>Evaluado por la persona titular de DA</td>
                        <td id="p35"><b>35</b></td>
                        <td id="cantDA" name="cantDA">
                        </td>
                        <td id="cantDA2"></td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comDA" name="comDA" placeholder="0" oninput="onActv3Comision3_5()" value="{{ oldValueOrDefault('comDA') }}">
                            @else
                            <span id="comDA" name="comDA"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                            <input id="obs3_5_1" name="obs3_5_1" class="table-header" type="text">
                            @else
                            <span id="obs3_5_1" name="obs3_5_1" class="table-header"></span>
                            @endif
                        </td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td class="punto3_5">b)</td>
                            <td>Evaluado por CAAC</td>
                            <td id="pCAAC40"><b>40</b></td>
                            <td id="cantCAAC" name="cantCAAC"></td>
                            <td id="cantCAAC2" name="cantCAAC2"></td>
                            <td class="td_obs">
                                @if($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comNCAA" name="comNCAA"
                                oninput="onActv3Comision3_5()" value="{{ oldValueOrDefault('comNCAA') }}">
                                @else
                                <span id="comNCAA" name="comNCAA"></span>
                                @endif
                            </td>
                            <td class="td_obs">
                                @if($userType == 'dictaminador')
                                <input id="obs3_5_2" name="obs3_5_2" class="table-header" type="text">
                                @else
                                <span id="obs3_5_2" name="obs3_5_2" class="table-header"></span>
                                @endif
                            </td>
                        </tr>
                    </thead>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.5-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>
            
                        <th class="descripcion"><b>JDA y CAAC</b>
                    </tr>
                </thead>
            </table>
                {{-- Lógica de botones --}}
                @if($userType != 'docente')
                <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                @endif
                {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                    <button type="submit" class="btn custom-btn printButtonClass" id="btn3_5">Enviar</button>
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
                <x-form-renderer :forms="[['view' => 'form3_5', 'startPage' => 9, 'endPage' => 9]]" />
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
    @include('partials.docente-autocomplete', ['config' => $docenteConfig])
    @include('partials.submit-form', ['config' => $docenteConfigForm])
</body>

</html>