@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$docenteConfig = $docenteConfig ?? [
    'formKey' => 'form3_17',

    // Endpoints base
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data317',

    // Clave de colección dentro del JSON de dictaminadores
    'dictCollectionKey'   => 'form3_17',

    // Tipo de usuario que debe gatillar la carga de respuestas de dictaminadores
    'userTypeForDict'     => '',

    // ---- Mapeos cuando se selecciona un docente ----
    'docenteMappings' => [
        // puntajes principales
        'score3_17'          => 'score3_17',

        // cantidades y subtotales
        'cantDifusionExt'     => 'cantDifusionExt',
        'subtotalDifusionExt'  => 'subtotalDifusionExt',
        'cantDifusionInt'     => 'cantDifusionInt',
        'subtotalDifusionInt'  => 'subtotalDifusionInt',
        'cantRepDifusionExt'     => 'cantRepDifusionExt',
        'subtotalRepDifusionExt'  => 'subtotalRepDifusionExt',
        'cantRepDifusionInt'     => 'cantRepDifusionInt',
        'subtotalRepDifusionInt'  => 'subtotalRepDifusionInt',



    ],

    // ---- Mapeos de datos desde dictaminadores ----
    'dictMappings' => [

        // cantidades y subtotales
        'cantDifusionExt'     => 'cantDifusionExt',
        'subtotalDifusionExt'  => 'subtotalDifusionExt',
        'cantDifusionInt'     => 'cantDifusionInt',
        'subtotalDifusionInt'  => 'subtotalDifusionInt',
        'cantRepDifusionExt'     => 'cantRepDifusionExt',
        'subtotalRepDifusionExt'  => 'subtotalRepDifusionExt',
        'cantRepDifusionInt'     => 'cantRepDifusionInt',
        'subtotalRepDifusionInt'  => 'subtotalRepDifusionInt',


        // comisiones y observaciones
        'comisionDifusionExt'   => 'comisionDifusionExt',
        'obsDifusionExt' => 'obsDifusionExt',
        'comisionDifusionInt'   => 'comisionDifusionInt',
        'obsDifusionInt' => 'obsDifusionInt',
        'comisionRepDifusionExt'   => 'comisionRepDifusionExt',
        'obsRepDifusionExt' => 'obsRepDifusionExt',
        'comisionRepDifusionInt'   => 'comisionRepDifusionInt',
        'obsRepDifusionInt' => 'obsRepDifusionInt',


        // totales
        'score3_17'                     => 'score3_17',
        'comision3_17'                 => 'comision3_17',
        '.comision3_17'                 => 'comision3_17',
        '#comision3_17'                 => 'comision3_17',
    ],

    // ---- Inputs ocultos que se llenan desde docenteData.form3_17 ----
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
        'score3_17' => '0',
        '#comision3_17' => '0',
        'cantDifusionExt'=> '0',
        'subtotalDifusionExt'=> '0',
        'cantDifusionInt'=> '0',
        'subtotalDifusionInt'=> '0',
        'cantRepDifusionExt'=> '0',
        'subtotalRepDifusionExt'=> '0',
        'cantRepDifusionInt'=> '0',
        'subtotalRepDifusionInt'=> '0',
        'comisionDifusionExt'=> '0',
        'comisionDifusionInt'=> '0',
        'comisionRepDifusionExt'=> '0',
        'comisionRepDifusionInt'=> '0',
        'obsDifusionExt'=> '',
        'obsDifusionInt'=> '',
        'obsRepDifusionExt'=> '',
        'obsRepDifusionInt'=> '',

    ],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        // Campos adicionales que se enviarán junto al form
        'extraFields' => [
            'comision3_17',
            'score3_17',
        // Demas datos
        'comisionDifusionExt',
        'comisionDifusionInt',
        'comisionRepDifusionExt',
        'comisionRepDifusionInt',
        'cantDifusionExt',
        'subtotalDifusionExt',
        'cantDifusionInt',
        'subtotalDifusionInt',
        'cantRepDifusionExt',
        'subtotalRepDifusionExt',
        'cantRepDifusionInt',
        'subtotalRepDifusionInt',
        'obsDifusionExt',
        'obsDifusionInt',
        'obsRepDifusionExt',
        'obsRepDifusionInt',
            
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

   <style>
    #piedepagina { display: none; }

    td{
    font-size: 1rem
    }
    @media print {
        #piedepagina{
            display: block !important;
        }
        body {
            margin-left: 450px;
            margin-top: -10px;
            padding: 0;
            font-size: .9rem;

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

button#edit-btn-form3_17{
    margin-left:67rem;
}

button#btn3_17{
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
$checkFields = ['comision3_17'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_17';
$formNumber = '317';
@endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        {{-- Buscar Docentes: --}}
        <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_1 -->
        <form id="form3_17" action="/formato-evaluacion/store-form317" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <!--3.17 Proyectos académicos de extensión y difusión-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">50</label>
            </h4>
            <table class="table table-sm tutorias">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                        <th>
                            <h3 style="width: 350px;" id="cuerpos_colegiados">Cuerpos Colegiados</h3>
                        </th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th scope="col" colspan=6>Actividad</th>
                        <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th id="seccion3_17" class="acreditacion" colspan=3> 3.17 Proyectos académicos de
                            extensión y
                            difusión</th>

                        <th colspan="3"></th>
                        <th id="score3_17">0</th>
                        <th id="comision3_17">0</th>

                    </tr>
                    <tr>
                        <th colspan="3"></th>
                        <th class="acreditacion" colspan="1">Puntaje</th>
                        <th class="acreditacion">Cantidad</th>
                        <th colspan="3"></th>
                        <th class="obsv acreditacion" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>a)</td>
                        <td id="form3_17_a"colspan="2">Inicio de proyectos de extensión y difusión con financiamiento externo</td>
                        <td id="puntajeDifusionExt"><b>15</b></td>
                        <td id="cantDifusionExt"></td>
                        <td></td>
                        <td id="subtotalDifusionExt"></td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionDifusionExt" id="comisionDifusionExt" value="{{ oldValueOrDefault('comisionDifusionExt') }}" oninput="onActv3Comision3_17()">
                        @else
                            <span name="comisionDifusionExt" id="comisionDifusionExt"></span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsDifusionExt" id="obsDifusionExt">
                        @else
                            <span name="obsDifusionExt" id="obsDifusionExt"></span>
                        @endif
                         </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td colspan="2">Inicio de proyectos de extensión y difusión internos, aprobados por CAAC
                        </td>
                        <td id="puntajeDifusionInt"><b>10</b></td>
                        <td id="cantDifusionInt"></td>
                        <td></td>
                        <td id="subtotalDifusionInt"></td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')    
                            <input type="number" step="0.01" name="comisionDifusionInt" id="comisionDifusionInt" value="{{ oldValueOrDefault('comisionDifusionInt') }}" oninput="onActv3Comision3_17()">
                        @else
                             <span name="comisionDifusionInt" id="comisionDifusionInt"></span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')      
                            <input class="table-header" type="text" name="obsDifusionInt" id="obsDifusionInt">
                        @else
                            <span name="obsDifusionInt" id="obsDifusionInt"></span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td colspan="2">Reporte cumplido del periodo anual de proyecto de extensión y difusión con
                            financiamiento
                            externo
                        </td>
                        <td id="puntajeRepDifusionExt"><b>35</b></td>
                        <td id="cantRepDifusionExt" ></td>
                        <td></td>
                        <td id="subtotalRepDifusionExt"></td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')  
                            <input type="number" step="0.01" name="comisionRepDifusionExt" id="comisionRepDifusionExt" value="{{ oldValueOrDefault('comisionRepDifusionExt') }}" oninput="onActv3Comision3_17()">
                        @else
                            <span name="comisionRepDifusionExt" id="comisionRepDifusionExt"></span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')  
                            <input class="table-header" type="text" name="obsRepDifusionExt" id="obsRepDifusionExt">
                        @else
                            <span name="obsRepDifusionExt" id="obsRepDifusionExt"></span>
                        @endif    
                        </td>
                    </tr>
                    <tr>
                        <td>d)</td>
                        <td colspan="2">Reporte cumplido del periodo anual de proyecto de extensión y difusión
                            internos, aprobados por
                            CAAC</td>
                        <td id="puntajeRepDifusionInt"><b>20</b></td>
                        <td id="cantRepDifusionInt">
                        </td>
                        <td></td>
                        <td id="subtotalRepDifusionInt"></td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')      
                            <input type="number" step="0.01" name="comisionRepDifusionInt" id="comisionRepDifusionInt" value="{{ oldValueOrDefault('comisionRepDifusionInt') }}" oninput="onActv3Comision3_17()">
                        @else
                            <span name="comisionRepDifusionInt" id="comisionRepDifusionInt"></span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')      
                            <input class="table-header" type="text" name="obsRepDifusionInt" id="obsRepDifusionInt">
                        @else
                            <span name="obsRepDifusionInt" id="obsRepDifusionInt"></span>
                        @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.17-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>
                        <th class="descripcion"><b>CAAC, DDCEU</b></th>
                        @if($userType != 'docente')
                        <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                        @endif
                        {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                        @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                        <button type="submit" class="btn custom-btn printButtonClass" id="btn3_17">Enviar</button>
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
        <x-form-renderer :forms="[['view' => 'form3_17', 'startPage' => 25, 'endPage' => 25]]" />
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