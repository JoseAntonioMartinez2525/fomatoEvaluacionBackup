@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$docenteConfig = $docenteConfig ?? [
    'formKey' => 'form3_14',

    // Endpoints base
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
    'dictEndpoint'        => '/formato-evaluacion/get-dictaminators-responses',

    // Clave de colección dentro del JSON de dictaminadores
    'dictCollectionKey'   => 'form3_14',

    // Tipo de usuario que debe gatillar la carga de respuestas de dictaminadores
    'userTypeForDict'     => '',

    // ---- Mapeos cuando se selecciona un docente ----
    'docenteMappings' => [
        // puntajes principales
        'score3_14'          => 'score3_14',

        // cantidades y subtotales
        'cantCongresoInt'       => 'cantCongresoInt',
        'cantCongresoNac'    => 'cantCongresoNac',
        'cantCongresoLoc' => 'cantCongresoLoc',
        'subtotalCongresoInt'       => 'subtotalCongresoInt',
        'subtotalCongresoNac'    => 'subtotalCongresoNac',
        'subtotalCongresoLoc' => 'subtotalCongresoLoc',



    ],

    // ---- Mapeos de datos desde dictaminadores ----
    'dictMappings' => [

        // cantidades y subtotales
        'cantCongresoInt'       => 'cantCongresoInt',
        'cantCongresoNac'    => 'cantCongresoNac',
        'cantCongresoLoc' => 'cantCongresoLoc',
        'subtotalCongresoInt'       => 'subtotalCongresoInt',
        'subtotalCongresoNac'    => 'subtotalCongresoNac',
        'subtotalCongresoLoc' => 'subtotalCongresoLoc',

        // comisiones y observaciones
        'comisionCongresoInt'   => 'comisionCongresoInt',
        'comisionCongresoNac' => 'comisionCongresoNac',
        'comisionCongresoLoc'  => 'comisionCongresoLoc',
        'obsCongresoInt'   => 'obsCongresoInt',
        'obsCongresoNac' => 'obsCongresoNac',
        'obsCongresoLoc'  => 'obsCongresoLoc',

        // totales
        'score3_14'                     => 'score3_14',
        'comision3_14'                 => 'comision3_14',
        '.comision3_14'                 => 'comision3_14',
        '#comision3_14'                 => 'comision3_14',
    ],

    // ---- Inputs ocultos que se llenan desde docenteData.form3_14 ----
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
        'score3_14' => '0',
        '#comision3_14' => '0',
        'cantCongresoInt' => '0',
        'cantCongresoNac' => '0',
        'cantCongresoLoc' => '0',
        'subtotalCongresoInt' => '0',
        'subtotalCongresoNac' => '0',
        'subtotalCongresoLoc' => '0',
        'comisionCongresoInt'   => '0',
        'comisionCongresoNac' => '0',
        'comisionCongresoLoc'  => '0',
        'obsCongresoInt'   => '',
        'obsCongresoNac' => '',
        'obsCongresoLoc'  => '',


    ],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        // Campos adicionales que se enviarán junto al form
        'extraFields' => [
            'comision3_14',
            'score3_14',
            'cantCongresoInt',
            'cantCongresoNac',
            'cantCongresoLoc',
            'subtotalCongresoInt',
            'subtotalCongresoNac',
            'subtotalCongresoLoc',
            'comisionCongresoInt',
            'comisionCongresoNac',
            'comisionCongresoLoc',
            'obsCongresoInt',
            'obsCongresoNac',
            'obsCongresoLoc',

            
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
    button#edit-btn-form3_14{
        margin-left: 67rem;
    }

    button#btn3_14{
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
$checkFields = ['comision3_14'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_14';
$formNumber = '314';
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
        <form id="form3_14" action="/formato-evaluacion/store-form314" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
                <!--3.14 Participación como ponente en congresos o eventos académicos del Área de Conocimiento o afines del docente-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">40</label>
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
                <thead>
                    <tr>
                        <th id="seccion3_14" class="acreditacion" colspan=7>3.14 Participación como ponente
                            en congresos
                            o eventos
                            académicos
                            del Área de Conocimiento o afines del docente</th>
                        <th id="score3_14">0</th>
                        <th id="comision3_14">0</th>
                        
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th class="acreditacion" colspan=2>Congresos y eventos académicos</th>
                        <th class="acreditacion">Puntaje</th>
                        <th class="acreditacion">Cantidad</th>
                        <th colspan="3"></th>
                        <th class="acreditacion">Subtotal</th>
                        <th colspan="1"></th>
                        <th class="obsv acreditacion" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!--Incisos 3.14-->
                    <tr>
                        <td>a)</td>
                        <td class="td_3_14">Internacional</td>
                        <td id="puntajeCongresoInt"><b>25</b></td>
                        <td id="cantCongresoInt"></td>
                        <td colspan="3"></td>
                        <td id="subtotalCongresoInt">0</td>
                        <td class="obsv_3_14">
                        @if($userType == 'dictaminador')                                            
                            <input type="number" step="0.01" name="comisionCongresoInt" id="comisionCongresoInt" value="{{ oldValueOrDefault('comisionCongresoInt') }}"
                                oninput="onActv3Comision3_14()">
                        @else
                            <span name="comisionCongresoInt" id="comisionCongresoInt"></span>

                        @endif        

                        </td>
                        <td class="obsv_3_14">
                        @if($userType == 'dictaminador')   
                            <input class="table-header" type="text" name="obsCongresoInt" id="obsCongresoInt">
                        @else
                            <span name="obsCongresoInt" id="obsCongresoInt"></span>
                        @endif  
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td class="td_3_14">Nacional</td>
                        <td id="puntajeCongresoNac"><b>20</b></td>
                        <td id="cantCongresoNac"></td>
                        <td colspan="3"></td>
                        <td id="subtotalCongresoNac">0</td>
                        <td class="obsv_3_14">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionCongresoNac" id="comisionCongresoNac" value="{{ oldValueOrDefault('comisionCongresoNac') }}"
                                oninput="onActv3Comision3_14()">
                        @else
                            <span name="comisionCongresoNac" id="comisionCongresoNac"></span>
                        @endif        
                        
                        </td>
                        <td class="obsv_3_14">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsCongresoNac" id="obsCongresoNac">
                        @else
                            <span name="obsCongresoNac" id="obsCongresoNac"></span>
                        @endif  
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td class="td_3_14">Local</td>
                        <td id="puntajeCongresoLoc"><b>10</b></td>
                        <td id="cantCongresoLoc"></td>
                        <td colspan="3"></td>
                        <td id="subtotalCongresoLoc">0</td>
                        <td class="obsv_3_14">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01"  name="comisionCongresoLoc" id="comisionCongresoLoc" value="{{ oldValueOrDefault('comisionCongresoLoc') }}"
                                oninput="onActv3Comision3_14()">
                        @else                                           
                        <span  name="comisionCongresoLoc" id="comisionCongresoLoc"></span>

                        @endif        
                        
                        </td>
                        <td class="obsv_3_14">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text"  name="obsCongresoLoc" id="obsCongresoLoc">
                        @else                                           
                            <span  name="obsCongresoLoc" id="obsCongresoLoc"></span>

                        @endif  
                        </td>
                    </tr>
                </tbody>
            </table>
    <!--Tabla informativa Acreditacion Actividad 3.14-->
    <table>
        <thead>
            <tr>
                <th class="acreditacion" scope="col">Acreditacion: </th>

                <th class="descripcion"><b>Instancia que otorga</b> </th>
                    @if($userType != 'docente')
                    <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                    @endif
                    {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                    @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                    <button type="submit" class="btn custom-btn printButtonClass" id="btn3_14">Enviar</button>
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
        <x-form-renderer :forms="[['view' => 'form3_14', 'startPage' => 21, 'endPage' => 21]]" />
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