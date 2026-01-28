@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$docenteConfig = $docenteConfig ?? [
    'formKey' => 'form3_11',

    // Endpoints base
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data311',

    // Clave de colección dentro del JSON de dictaminadores
    'dictCollectionKey'   => 'form3_11',

    // Tipo de usuario que debe gatillar la carga de respuestas de dictaminadores
    'userTypeForDict'     => '',

    // ---- Mapeos cuando se selecciona un docente ----
    'docenteMappings' => [
        // puntajes principales
        'score3_11'          => 'score3_11',

        //cantidades y subtotales
        'cantAsesoria'       => 'cantAsesoria',
        'cantServicio'    => 'cantServicio',
        'cantPracticas'     => 'cantPracticas',
        'subtotalAsesoria'   => 'subtotalAsesoria',
        'subtotalServicio' => 'subtotalServicio',
        'subtotalPracticas'  => 'subtotalPracticas',

    ],

    // ---- Mapeos de datos desde dictaminadores ----
    'dictMappings' => [
        // campos grupales e individuales (comisión y observaciones)
        'comisionAsesoria'   => 'comisionAsesoria',
        'obsAsesoria'        => 'obsAsesoria',
        'comisionServicio' => 'comisionServicio',
        'obsServicio'        => 'obsServicio',
        'comisionPracticas'  => 'comisionPracticas',
        'obsPracticas'       => 'obsPracticas',
        //cantidades y subtotales
        'cantAsesoria'       => 'cantAsesoria',
        'cantServicio'    => 'cantServicio',
        'cantPracticas'     => 'cantPracticas',
        'subtotalAsesoria'   => 'subtotalAsesoria',
        'subtotalServicio' => 'subtotalServicio',
        'subtotalPracticas'  => 'subtotalPracticas',

        // totales
        'score3_11'                     => 'score3_11',
        'comision3_11'                 => 'comision3_11',
        '.comision3_11'                 => 'comision3_11',
        '#comision3_11'                 => 'comision3_11',
    ],

    // ---- Inputs ocultos que se llenan desde docenteData.form3_11 ----
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
        'score3_11' => '0',
        '#comision3_11' => '0',
        'comisionAsesoria' => '0',
        'obsAsesoria' => '',
        'comisionServicio' => '',
        'obsServicio' => '',
        'comisionPracticas' => '0',
        'obsPracticas' => '',
        'cantAsesoria' => '0',
        'cantServicio' => '0',
        'cantPracticas' => '0',
        'subtotalAsesoria' => '0',
        'subtotalServicio' => '0',
        'subtotalPracticas' => '0',


    ],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        // Campos adicionales que se enviarán junto al form
        'extraFields' => [
            'score3_11',
            'comisionAsesoria',
            'obsAsesoria',
            'comisionServicio',
            'obsServicio',
            'comisionPracticas',
            'obsPracticas',
            'cantAsesoria',
            'cantServicio',
            'cantPracticas',
            'subtotalAsesoria',
            'subtotalServicio',
            'subtotalPracticas',
            'comision3_11',
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
$checkFields = ['comision3_11'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_11';
$formNumber = '311';
@endphp
{{-- @php dd($userType) @endphp --}}

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
            {{-- Buscar Docentes: --}}
            <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_11 -->
        <form id="form3_11" action="/formato-evaluacion/store-form311" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <!--3.11 Trabajos dirigidos para la titulación de estudiantes-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">95</label>
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
                        <th id="seccion3_11" class="acreditacion" colspan=5>3.11 Asesoría a estudiantes</th>
                        <th colspan="3"></th>
                        <th id="score3_11">0</th>
                        <th id="comision3_11">0</th>
                        
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="acreditacion">Incisos</th>
                        <th class="acreditacion">Documento</th>
                        <th class="acreditacion">Actividad</th>
                        <th class="acreditacion">Puntaje</th>
                        <th class="acreditacion">Cantidad</th>
                        <th colspan="3"></th>
                        <th class="acreditacion">Subtotal</th>
                        <th colspan="1"></th>
                        <th class="obsv acreditacion" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!--3_11 Asesoria a estudiantes incisos-->
                    <tr>
                        <td>a)</td>
                        <td class="td_3_11">Asesorías académicas</td>
                        <td class="td_3_11">Por alumno(a), por semestre</td>
                        <td id="academica">5</td>
                        <td id="cantAsesoria"></td>
                        <td colspan="3"></td>
                        <td id="subtotalAsesoria"></td>
                        <td id="td_comisionAsesoria">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionAsesoria" name="comisionAsesoria" oninput="onActv3Comision3_11()" value="{{ oldValueOrDefault('comisionAsesoria') }}">   
                            @else
                                <span  id="comisionAsesoria" name="comisionAsesoria" ></span>                      
                            @endif
                        </td>
                        <td id="td_obsAsesoria">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsAsesoria" name="obsAsesoria">
                            @else
                            <span id="obsAsesoria" name="obsAsesoria"></span>
                            @endif
                            
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td class="td_3_11">Servicio social*</td>
                        <td class="td_3_11">Por alumno(a), por semestre</td>
                        <td id="servicio">20</td>
                        <td id="cantServicio"></td>
                        <td colspan="3"></td>
                        <td id="subtotalServicio"></td>
                        <td id="td_comisionServicio">
                        @if ($userType == 'dictaminador')   
                            <input type="number" step="0.01" id="comisionServicio" name="comisionServicio" placeholder="0" oninput="onActv3Comision3_11()" value="{{ oldValueOrDefault('comisionServicio') }}">
                        @else
                            <span id="comisionServicio" name="comisionServicio"></span>
                        @endif

                        </td>
                        <td id="td_obsServicio">
                        @if ($userType == 'dictaminador')   
                            <input class="table-header" type="text" id="obsServicio" name="obsServicio"></td>
                        @else
                            <span id="comisionServicio" name="obsServicio"></span>
                        @endif

                    </tr>
                    <tr>
                        <td>c)</td>
                        <td class="td_3_11">Prácticas profesionales</td>
                        <td class="td_3_11">Por alumno(a), por semestre</td>
                        <td id="practicas">20</td>
                        <td id="cantPracticas"></td>
                        <td colspan="3"></td>
                        <td id="subtotalPracticas"></td>
                        <td  id="td_comisionPracticas">
                        @if ($userType == 'dictaminador')  
                            <input type="number" step="0.01" id="comisionPracticas"  name="comisionPracticas" oninput="onActv3Comision3_11()" value="{{ oldValueOrDefault('comisionPracticas') }}">
                        @else
                        <span id="comisionPracticas" name="comisionPracticas"></span
                        @endif  
                            
                        </td>
                        <td id="td_obsPracticas">
                        @if ($userType == 'dictaminador')  
                            <input class="table-header" type="text" id="obsPracticas" name="obsPracticas">
                        @else
                            <span id="obsPracticas" name="obsPracticas"></span>
                        @endif    
                            
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <!--Tabla informativa Acreditacion Actividad 3.11-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>
            
                        <th class="descripcion"><b>JD, *DSEs</b> </th>
                        {{-- Lógica de botones --}}
                        @if($userType != 'docente')
                        <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                        @endif
                        {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                        @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                        <button type="submit" class="btn custom-btn printButtonClass" id="btn3_11Button">Enviar</button>
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
            <x-form-renderer :forms="[['view' => 'form3_11', 'startPage' => 17, 'endPage' => 17]]" />
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