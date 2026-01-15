@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$docenteConfig = $docenteConfig ?? [
    'formKey' => 'form3_16',

    // Endpoints base
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
    'dictEndpoint'        => '/formato-evaluacion/get-dictaminators-responses',

    // Clave de colección dentro del JSON de dictaminadores
    'dictCollectionKey'   => 'form3_16',

    // Tipo de usuario que debe gatillar la carga de respuestas de dictaminadores
    'userTypeForDict'     => '',

    // ---- Mapeos cuando se selecciona un docente ----
    'docenteMappings' => [
        // puntajes principales
        'score3_16'          => 'score3_16',

        // cantidades y subtotales
        'cantArbInt'     => 'cantArbInt',
        'cantArbNac'  => 'cantArbNac',
        'cantPubInt' => 'cantPubInt',
        'cantPubNac'=> 'cantPubNac',
        'cantRevInt'   => 'cantRevInt',
        'cantRevNac' => 'cantRevNac',
        'cantRevista' => 'cantRevista',
        'subtotalArbInt'     => 'subtotalArbInt',
        'subtotalArbNac'  => 'subtotalArbNac',
        'subtotalPubInt' => 'subtotalPubInt',
        'subtotalPubNac'=> 'subtotalPubNac',
        'subtotalRevInt'   => 'subtotalRevInt',
        'subtotalRevNac' => 'subtotalRevNac',
        'subtotalRevista' => 'subtotalRevista',
       

    ],

    // ---- Mapeos de datos desde dictaminadores ----
    'dictMappings' => [

        // cantidades y subtotales
        'cantArbInt'     => 'cantArbInt',
        'cantArbNac'  => 'cantArbNac',
        'cantPubInt' => 'cantPubInt',
        'cantPubNac'=> 'cantPubNac',
        'cantRevInt'   => 'cantRevInt',
        'cantRevNac' => 'cantRevNac',
        'cantRevista' => 'cantRevista',
        'subtotalArbInt'     => 'subtotalArbInt',
        'subtotalArbNac'  => 'subtotalArbNac',
        'subtotalPubInt' => 'subtotalPubInt',
        'subtotalPubNac'=> 'subtotalPubNac',
        'subtotalRevInt'   => 'subtotalRevInt',
        'subtotalRevNac' => 'subtotalRevNac',
        'subtotalRevista' => 'subtotalRevista',


        // comisiones y observaciones
        'comisionArbInt'   => 'comisionArbInt',
        'comisionArbNac' => 'comisionArbNac',
        'comisionPubInt'  => 'comisionPubInt',
        'comisionPubNac' => 'comisionPubNac',
        'comisionRevInt'   => 'comisionRevInt',
        'comisionRevNac' => 'comisionRevNac',
        'comisionRevista' => 'comisionRevista',
        'obsArbInt'   => 'obsArbInt',
        'obsArbNac' => 'obsArbNac',
        'obsPubInt'  => 'obsPubInt',
        'obsPubNac' => 'obsPubNac',
        'obsRevInt'   => 'obsRevInt',
        'obsRevNac' => 'obsRevNac',
        'obsRevista' => 'obsRevista',


        // totales
        'score3_16'                     => 'score3_16',
        'comision3_16'                 => 'comision3_16',
        '.comision3_16'                 => 'comision3_16',
        '#comision3_16'                 => 'comision3_16',
    ],

    // ---- Inputs ocultos que se llenan desde docenteData.form3_16 ----
    'fillHiddenFrom' => [
        'user_id'    => 'user_id',
        'email'      => '',
        'user_type'  => 'user_type',
    ],

    // ---- Inputs ocultos que se llenan desde la respuesta de dictaminador ----
    'fillHiddenFromDict' => [
        'dictaminador_id' => 'dictaminador_id',
        'user_id'         => 'user_id',
        'email'           => 'email',
        'user_type'       => 'user_type',
    ],
    

    // ---- Comportamiento cuando no hay respuesta de dictaminador ----
    'resetOnNotFound' => false,
    'resetValues' => [
        'score3_16' => '0',
        '#comision3_16' => '0',
        'cantArbInt' => '0',
        'cantArbNac' => '0',
        'cantPubInt' => '0',
        'cantPubNac' => '0',
        'cantRevInt' => '0',
        'cantRevNac' => '0',
        'cantRevista' => '0',
        'subtotalArbInt' => '0',
        'subtotalArbNac' => '0',
        'subtotalPubInt' => '0',
        'subtotalPubNac' => '0',
        'subtotalRevInt' => '0',
        'subtotalRevNac' => '0',
        'subtotalRevista' => '0',
        'comisionArbInt' => '0',
        'comisionArbNac' => '0',
        'comisionPubInt' => '0',
        'comisionPubNac' => '0',
        'comisionRevInt' => '0',
        'comisionRevNac' => '0',
        'comisionRevista' => '0',
        'obsArbInt' => '',
        'obsArbNac' => '',
        'obsPubInt' => '',
        'obsPubNac' => '',
        'obsRevInt' => '',
        'obsRevNac' => '',
        'obsRevista' => '',


    ],

        'convocatoriaSelectors' => [
        '#convocatoria',
        '#convocatoria2',
    ],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        // Campos adicionales que se enviarán junto al form
        'extraFields' => [
        'comision3_16',
        'score3_16',
        // Demas datos
        'cantArbInt',
        'cantArbNac',
        'cantPubInt',
        'cantPubNac',
        'cantRevInt',
        'cantRevNac',
        'cantRevista',
        'subtotalArbInt',
        'subtotalArbNac',
        'subtotalPubInt',
        'subtotalPubNac',
        'subtotalRevInt',
        'subtotalRevNac',
        'subtotalRevista',
        'comisionArbInt',
        'comisionArbNac',
        'comisionPubInt',
        'comisionPubNac',   
        'comisionRevInt',
        'comisionRevNac',
        'comisionRevista',
        'obsArbInt',
        'obsArbNac',
        'obsPubInt',
        'obsPubNac',
        'obsRevInt',
        'obsRevNac',
        'obsRevista',
            
        ],

        // Nombre global de la función que se expondrá (window.submitForm)
        'exposeAs' => 'submitForm',

        // IDs usados por el autocompletado docente
        'selectedEmailInputId' => 'selectedDocenteEmail',
        'searchInputId' => 'docenteSearch',
    ];

    // Si se recibe un email desde la URL, se lo pasamos a la configuración del autocompletado.
if (isset($teacherEmailFromUrl) && $teacherEmailFromUrl) {
    $docenteConfig['preselectedEmail'] = $teacherEmailFromUrl;
}
}
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

td{
    font-size: 1rem;
}

    button#edit-btn-form3_16{
        margin-left:67rem;
    }

    button#btn3_16{
        margin-left:60rem;
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

$baseUrl = url('/formato-evaluacion');
$docenteConfig['baseUrl'] = $baseUrl;
$docenteConfigForm['baseUrl'] = $baseUrl;
$hasData = false;
$checkFields = ['comision3_16'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_16';
$formNumber = '316';
@endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        {{-- Buscar Docentes: --}}
        <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_16 -->
        <form id="form3_16" action="/formato-evaluacion/store-form316" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
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
                        <th colspan="2">
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
                        <td colspan="2"></td>
                        <td id="subtotalArbInt"></td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionArbInt" id="comisionArbInt" value="{{ oldValueOrDefault('comisionArbInt') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span name="comisionArbInt" id="comisionArbInt"></span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsArbInt" id="obsArbInt">
                        @else
                            <span name="obsArbInt" id="obsArbInt"></span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>Arbitraje a proyectos de investigación</td>
                        <td>Nacional</td>
                        <td id="puntajeArbINac"><b>25</b></td>
                        <td id="cantArbNac"></td>
                        <td colspan="2"></td>
                       
                        <td id="subtotalArbNac"></td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionArbNac" id="comisionArbNac" value="{{ oldValueOrDefault('comisionArbNac') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span name="comisionArbNac" id="comisionArbNac"></span>
                        @endif
                        
                        </td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsArbNac" id="obsArbNac">
                        @else
                            <span name="obsArbNac" id="obsArbNac"></span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td>Arbitraje de publicaciones</td>
                        <td>Internacional</td>
                        <td id="puntajePubInt"><b>20</b></td>
                        <td id="cantPubInt"></td>
                        <td colspan="2"></td>
                        
                        <td id="subtotalPubInt"></td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionPubInt" id="comisionPubInt" value="{{ oldValueOrDefault('comisionPubInt') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span name="comisionPubInt" id="comisionPubInt"></span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsPubInt" id="obsPubInt">
                        @else
                            <span name="obsPubInt" id="obsPubInt"></span>
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
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">
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
                        <td colspan="2"></td>
                       
                        <td id="subtotalPubNac"></td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionPubNac" id="comisionPubNac" value="{{ oldValueOrDefault('comisionPubNac') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span name="comisionPubNac" id="comisionPubNac"></span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsPubNac" id="obsPubNac">
                        @else
                            <span name="obsPubNac" id="obsPubNac"></span>
                        @endif
                        </td>
                    </tr>                    
                    <tr>
                        <td>e)</td>
                        <td>Revisor(a) de libros, corrector(a)</td>
                        <td>Internacional</td>
                        <td id="puntajeRevInt"><b>30</b></td>
                        <td id="cantRevInt"></td>
                        <td colspan="2"></td>
                       
                        <td id="subtotalRevInt"></td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionRevInt" id="comisionRevInt" value="{{ oldValueOrDefault('comisionRevInt') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span name="comisionRevInt" id="comisionRevInt"> </span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsRevInt" id="obsRevInt">
                        @else
                            <span name="obsRevInt" id="obsRevInt"> </span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>f)</td>
                        <td>Revisor(a) de libros, corrector(a)</td>
                        <td>Nacional</td>
                        <td id="puntajeRevINac"><b>25</b></td>
                        <td id="cantRevNac"></td>
                        <td colspan="2"></td>
                        
                        <td id="subtotalRevNac"></td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionRevNac" id="comisionRevNac" value="{{ oldValueOrDefault('comisionRevNac') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span name="comisionRevNac" id="comisionRevNac"> </span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsRevNac" id="obsRevNac">
                        @else
                            <span name="obsRevNac" id="obsRevNac"> </span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>g)</td>
                        <td>Consejo editorial de revista, edición de revista</td>
                        <td>----</td>
                        <td id="puntajeRevista"><b>10</b></td>
                        <td id="cantRevista"></td>
                        <td colspan="2"></td>
                        
                        <td id="subtotalRevista"></td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionRevista" id="comisionRevista" value="{{ oldValueOrDefault('comisionRevista') }}"
                                oninput="onActv3Comision3_16()">
                        @else
                            <span name="comisionRevista" id="comisionRevista"> </span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsRevista" id="obsRevista">
                        @else
                            <span name="obsRevista" id="obsRevista"> </span>
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
                            @if($userType != 'docente')
                            <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                            @endif
                            {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                            @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                            <button type="submit" class="btn custom-btn printButtonClass" id="btn3_16">Enviar</button>
                            @endif
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
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">
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