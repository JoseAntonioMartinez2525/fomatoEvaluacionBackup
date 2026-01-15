@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$docenteConfig = $docenteConfig ?? [
    'formKey' => 'form3_13',

    // Endpoints base
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
    'dictEndpoint'        => '/formato-evaluacion/get-dictaminators-responses',

    // Clave de colección dentro del JSON de dictaminadores
    'dictCollectionKey'   => 'form3_13',

    // Tipo de usuario que debe gatillar la carga de respuestas de dictaminadores
    'userTypeForDict'     => '',

    // ---- Mapeos cuando se selecciona un docente ----
    'docenteMappings' => [
        // puntajes principales
        'score3_13'          => 'score3_13',

        // cantidades y subtotales
        'cantInicioFinanExt'       => 'cantInicioFinanExt',
        'cantInicioInvInterno'    => 'cantInicioInvInterno',
        'cantReporteFinanciamExt' => 'cantReporteFinanciamExt',
        'cantReporteInvInt'       => 'cantReporteInvInt',
        'subtotalInicioFinanExt'       => 'subtotalInicioFinanExt',
        'subtotalInicioInvInterno'    => 'subtotalInicioInvInterno',
        'subtotalReporteFinanciamExt' => 'subtotalReporteFinanciamExt',
        'subtotalReporteInvInt'       => 'subtotalReporteInvInt',


    ],

    // ---- Mapeos de datos desde dictaminadores ----
    'dictMappings' => [

        // cantidades y subtotales
        'cantInicioFinanExt'       => 'cantInicioFinanExt',
        'cantInicioInvInterno'    => 'cantInicioInvInterno',
        'cantReporteFinanciamExt' => 'cantReporteFinanciamExt',
        'cantReporteInvInt'       => 'cantReporteInvInt',
        'subtotalInicioFinanExt'       => 'subtotalInicioFinanExt',
        'subtotalInicioInvInterno'    => 'subtotalInicioInvInterno',
        'subtotalReporteFinanciamExt' => 'subtotalReporteFinanciamExt',
        'subtotalReporteInvInt'       => 'subtotalReporteInvInt',

        // comisiones y observaciones
        'comisionInicioFinancimientoExt'   => 'comisionInicioFinancimientoExt',
        'obsInicioFinancimientoExt'        => 'obsInicioFinancimientoExt',
        'comisionInicioInvInterno'        => 'comisionInicioInvInterno',
        'obsInicioInvInterno'             => 'obsInicioInvInterno',
        'comisionReporteFinanciamExt'     => 'comisionReporteFinanciamExt',
        'obsReporteFinanciamExt'          => 'obsReporteFinanciamExt',
        'comisionReporteInvInt'           => 'comisionReporteInvInt',
        'obsReporteInvInt'                => 'obsReporteInvInt',


        // totales
        'score3_13'                     => 'score3_13',
        '#comision3_13'                 => 'comision3_13',
    ],

    // ---- Inputs ocultos que se llenan desde docenteData.form3_13 ----
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
        'score3_13' => '0',
        '#comision3_13' => '0',
        'cantInicioFinanExt' => '0',
        'cantInicioInvInterno' => '0',
        'cantReporteFinanciamExt' => '0',
        'cantReporteInvInt' => '0',
        'subtotalInicioFinanExt' => '0',
        'subtotalInicioInvInterno' => '0',
        'subtotalReporteFinanciamExt' => '0',
        'subtotalReporteInvInt' => '0',
        'obsInicioFinancimientoExt' => '',
        'obsInicioInvInterno' => '',
        'obsReporteFinanciamExt' => '',
        'obsReporteInvInt' => '',
    ],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        // Campos adicionales que se enviarán junto al form
        'extraFields' => [
            'score3_13',
            'cantInicioFinanExt',
            'cantInicioInvInterno',
            'cantReporteInvInt',
            'cantReporteFinanciamExt',
            'subtotalInicioFinanExt',
            'subtotalInicioInvInterno',
            'subtotalReporteFinanciamExt',
            'subtotalReporteInvInt',
            'comisionInicioFinancimientoExt',
            'comisionInicioInvInterno',
            'comisionReporteFinanciamExt',
            'comisionReporteInvInt',
            'obsInicioFinancimientoExt',
            'obsInicioInvInterno',
            'obsReporteFinanciamExt',
            'obsReporteInvInt',
            'comision3_13',
        ],

        // Nombre global de la función que se expondrá (window.submitForm)
        'exposeAs' => 'submitForm',

        // IDs usados por el autocompletado docente
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
    <title>Evaluación docente</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-head-resources />

    <link href="{{ asset('css/onePage.css') }}" rel="stylesheet">
    <style>

#edit-btn-form3_13{
    margin-left: 17rem;
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
    $checkFields = ['comision3_13'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }
$formId = $docenteConfigForm['formId'] ?? 'form3_13';
$formNumber = '313';

@endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
            {{-- Buscar Docentes: --}}
            <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_13 -->
        <form id="form3_13" action="/formato-evaluacion/store-form313" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
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
                
            </tr>
            </thead>
            <thead>
                <tr>
                    <th class="acreditacion">Incisos</th>
                    <th class="acreditacion">Documento</th>
                    <th class="acreditacion">Puntaje</th>
                    <th class="acreditacion">Cantidad</th>
                    <th colspan="3"></th>
                    <th class="acreditacion">Subtotal</th>
                    <th colspan="1"></th>
                    <th class="obsv acreditacion" scope="col">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <!--Incisos 3.13-->
                <tr>
                    <td>a)</td>
                    <td class="td_3_13">Inicio de proyecto de investigación con financiamiento externo</td>
                    <td id="puntajeInicioFinanExt">50</td>
                    <td id="cantInicioFinanExt" class="cantidad" name="cantInicioFinanExt">
                    </td>
                    <td colspan="3"></td>
                    <td id="subtotalInicioFinanExt" name="subtotalInicioFinanExt"></td>
                    <td class="comision3_13">
                    @if ($userType == 'dictaminador')
                        <input type="number" step="0.01" id="comisionInicioFinancimientoExt" name="comisionInicioFinancimientoExt" value="{{ oldValueOrDefault('comisionInicioFinancimientoExt') }}" oninput="onActv3Comision3_13()">
                    @else
                        <span id="comisionInicioFinancimientoExt" name="comisionInicioFinancimientoExt"></span>
                    @endif
                        
                    </td>
                    <td class="td_obsInicioFinancimientoExt">
                    @if ($userType == 'dictaminador')
                        <input class="table-header" type="text" name="obsInicioFinancimientoExt" id="obsInicioFinancimientoExt">
                    @else
                        <span id="obsInicioFinancimientoExt" name="obsInicioFinancimientoExt" class="obsBackground"></span>
                    @endif                    
                    </td>
                </tr>
                <tr>
                    <td>b)</td>
                    <td class="td_3_13">Inicio de proyecto de investigación interno, aprobado por CAAC</td>
                    <td id="puntajeInicioInvInterno">25</td>
                    <td id="cantInicioInvInterno" class="cantidad" name="cantInicioInvInterno"></td>
                    <td colspan="3"></td>
                    <td id="subtotalInicioInvInterno" name="subtotalInicioInvInterno"></td>
                    <td class="comision3_13">
                     @if ($userType == 'dictaminador')   
                        <input type="number" step="0.01" id="comisionInicioInvInterno" name="comisionInicioInvInterno" value="{{ oldValueOrDefault('comisionInicioInvInterno') }}" oninput="onActv3Comision3_13()">
                    @else
                        <span id="comisionInicioInvInterno"name="comisionInicioInvInterno" ></span>
                    @endif
                    </td>
                    <td class="td_obsInicioInvInterno">
                    @if ($userType == 'dictaminador')
                        <input class="table-header" type="text" id="obsInicioInvInterno" name="obsInicioInvInterno">
                    @else
                        <span id="obsInicioInvInterno" name="obsInicioInvInterno" class="obsBackground"></span>
                    @endif
                    </td>
                </tr>
                <tr>
                    <td>c)</td>
                    <td class="td_3_13">Reporte cumplido del periodo anual del proyecto de investigación con
                        financiamiento externo
                    </td>
                    <td id="puntajeReporteFinanciamExt">100</td>
                    <td id="cantReporteFinanciamExt" class="cantidad"></td>
                    <td colspan="3"></td>

                    <td id="subtotalReporteFinanciamExt"></td>
                    <td class="comision3_13">
                     @if ($userType == 'dictaminador')     
                        <input type="number" step="0.01" name="comisionReporteFinanciamExt" id="comisionReporteFinanciamExt" value="{{ oldValueOrDefault('comisionReporteFinanciamExt') }}" oninput="onActv3Comision3_13()">
                    @else
                    <span id="comisionReporteFinanciamExt" name="comisionReporteFinanciamExt"></span>
                    @endif
                    </td>
                    <td class="td_obsReporteFinanciamExt">
                    @if ($userType == 'dictaminador')      
                        <input class="table-header" type="text" id="obsReporteFinanciamExt"  name="obsReporteFinanciamExt">
                    @else
                        <span id="obsReporteFinanciamExt" name="obsReporteFinanciamExt" class="obsBackground"></span>
                    @endif
                    </td>
                </tr>
                <tr>
                    <td>d)</td>
                    <td class="td_3_13">Reporte cumplido del periodo anual del proyecto de investigación interno,
                        aprobado por CAAC
                    </td>
                    <td id="puntajeReporteInvInt">50</td>
                    <td id="cantReporteInvInt" class="cantidad"></td>
                    <td colspan="3"></td>

                    <td id="subtotalReporteInvInt"></td>
                    <td class="comision3_13">
                    @if ($userType == 'dictaminador')      
                        <input type="number" step="0.01" name="comisionReporteInvInt" id="comisionReporteInvInt" value="{{ oldValueOrDefault('comisionReporteInvInt') }}" oninput="onActv3Comision3_13()">
                    @else
                        <span id="comisionReporteInvInt" name="comisionReporteInvInt" class="obsBackground"></span>
                    @endif
                    </td>
                    <td class="td_obsReporteInvInt">
                    @if ($userType == 'dictaminador')      
                        <input class="table-header" type="text" id="obsReporteInvInt" name="obsReporteInvInt">
                    @else
                        <span id="obsReporteInvInt" name="obsReporteInvInt" class="obsBackground"></span>
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
        
                    {{-- Lógica de botones --}}
                    @if($userType != 'docente')
                    <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                    @endif
                    {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                    @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                        <button type="submit" class="btn custom-btn printButtonClass" id="btn3_13">Enviar</button>
                    @endif
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