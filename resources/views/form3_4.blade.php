@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

// datos para cada formulario
$docenteConfig = [
        'formKey' => 'form3_4',
        'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data', 
        'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data34',
        'dictCollectionKey' => 'form3_4',
        'userTypeForDict' => '',
        'docenteMappings' => [
        // score y su copia
        'score3_4' => 'score3_4',     
        // cantidades y subtotales
        'cantInternacional' => 'cantInternacional',
        'cantNacional' => 'cantNacional',
        'cantidadRegional' => 'cantidadRegional',
        'cantPreparacion' => 'cantPreparacion',
        'cantInternacional2' => 'cantInternacional2',
        'cantNacional2' => 'cantNacional2',
        'cantidadRegional2' => 'cantidadRegional2',
        'cantPreparacion2' => 'cantPreparacion2',
        // comisiones y sus copias (puedes usar clase o id)
        '#comision3_4' => 'comision3_4',
        ],
        // Mapeos para respuestas de dictaminadores (si aplica)
    'dictMappings' => [
        // comisiones / comIncisos
        '#comision3_4' => 'comision3_4',
        'comInternacional' => 'comInternacional',
        'comNacional' => 'comNacional',
        'comRegional' => 'comRegional',
        'comPreparacion' => 'comPreparacion',
        // observaciones (span o elementos de texto)
        '#obs3_4_1' => 'obs3_4_1',
        '#obs3_4_2' => 'obs3_4_2',
        '#obs3_4_3' => 'obs3_4_3',
        '#obs3_4_4' => 'obs3_4_4',
        // repetir score/rc/stotals para sobrescribir si vienen desde dictaminador
        'score3_4' => 'score3_4',
        // cantidades y subtotales
        'cantInternacional' => 'cantInternacional',
        'cantNacional' => 'cantNacional',
        'cantidadRegional' => 'cantidadRegional',
        'cantPreparacion' => 'cantPreparacion',
        'cantInternacional2' => 'cantInternacional2',
        'cantNacional2' => 'cantNacional2',
        'cantidadRegional2' => 'cantidadRegional2',
        'cantPreparacion2' => 'cantPreparacion2',
    ],

    // Inputs ocultos que deben llenarse desde docenteData.form3_4
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
        'score3_4' => '0',
        '#comision3_4' => '0',
        '#obs3_4_1' => '',
        '#obs3_4_2' => '',
        '#obs3_4_3' => '',
        '#obs3_4_4' => '',
    ],

];
@endphp
@php
if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'score3_4',
            'comision3_4',
            'cantInternacional',
            'cantNacional',
            'cantidadRegional',
            'cantPreparacion',
            'comInternacional',
            'comNacional',
            'comRegional',
            'comPreparacion',
            'obs3_4_1', 'obs3_4_2', 'obs3_4_3', 'obs3_4_4',
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
    <title>Evaluación docente</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-head-resources />

    <style>

    body.chrome @media print {
    #convocatoria {
        font-size: 1.2rem;
        color: blue;
        /* Ejemplo de estilo específico para Chrome */
    }
}
    @media print{
    .datosPrimarios{

        font-size: .9rem;
    }

    .descripcionCAAC{
        font-size: .75rem;
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

body.dark-mode #cantInternacional, body.dark-mode #cantNacional, body.dark-mode #cantidadRegional, body.dark-mode #cantPreparacion{
    color: white;
}

body.dark-mode [id^="obs3_4_"]{
    color: white;
    background-color: transparent!important;
}

#btn3_4{
    margin-left: 1100px;
}

body.dark-mode [id^="btn3_"]{
        background-color: #456483;
        color: floralwhite;
}

body.dark-mode [id^="btn3_"]:hover {
    background-color: #6a5b9f;
    
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

    $hasData = false;
    $checkFields = ['comision3_4'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }
$formId = $docenteConfigForm['formId'] ?? 'form3_4';
$formNumber = '34';
@endphp

<button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

<div class="container mt-4" id="seleccionDocente">
    @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        <!-- Buscando docentes -->
        <x-docente-search />
    @endif
</div>
    <main class="container">
        <!-- Form for Part 3_4 -->
        <form id="form3_4" action="/formato-evaluacion/store-form34" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <div>
                <!-- 3.4 Distinciones académicas recibidas por el docente  -->
                <h4 class="datosPrimarios">Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">60</label>
                </h4>
            </div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">Actividad</th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust2 cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust2 cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th id="seccion3_4" colspan="4" class="punto3_4" scope="col" style="padding:5px;">3.4 Distinciones
                            académicas recibidas por el docente</th>
                        <td id="score3_4">0</td>
                        <td id="comision3_4">0</td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="punto3_4">Puntaje</td>
                        <td class="punto3_4">Cantidad</td>
                        <td colspan="2"></td>
                        <td class="table-ajust">Observaciones</td>
                    </tr>
                    <tr>
                        <td class="punto3_4">a)</td>
                        <td>Internacional</td>
                        <td id="p60"><b>60</b></td>
                        <td class="td_form3_4">
                            <span id="cantInternacional" name="cantInternacional"></span>
                        </td>
                        <td id="cantInternacional2"></td>
                        <td class="td_obs">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comInternacional" name="comInternacional" oninput="onActv3Comision3_4()" value="{{ oldValueOrDefault('comInternacional') }}">
                        @else
                            <span id="comInternacional" name="comInternacional"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                            <input id="obs3_4_1" name="obs3_4_1" class="table-header" type="text">
                        @else
                            <span id="obs3_4_1" name="obs3_4_1" class="table-header"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="punto3_4">b)</td>
                        <td>Nacional</td>
                        <td id="p30Nac"><b>30</b></td>
                        <td class="td_form3_4">
                            <span type="number" step="0.01" id="cantNacional"></span>
                        </td>
                        <td id="cantNacional2"></td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comNacional"name="comNacional" oninput="onActv3Comision3_4()" value="{{ oldValueOrDefault('comNacional') }}">
                             @else
                            <span id="comNacional" name="comNacional"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                            <input id="obs3_4_2" name="obs3_4_2" class="table-header" type="text">
                            @else
                                <span id="obs3_4_2" name="obs3_4_2" class="table-header"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="punto3_4">c)</td>
                        <td>Regional o estatal</td>
                        <td id="p20"><b>20</b></td>
                        <td class="td_form3_4">
                            <span id="cantidadRegional"></span>
                        </td>
                        <td id="cantidadRegional2"></td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comRegional" name="comRegional" oninput="onActv3Comision3_4()" value="{{ oldValueOrDefault('comRegional') }}">
                            @else
                            <span id="comRegional" name="comRegional"></span>
                            @endif
                        </td>
                        <td class="td_obs"> 
                            @if($userType == 'dictaminador')
                            <input id="obs3_4_3" name="obs3_4_3" class="table-header" type="text">
                            @else
                            <span id="obs3_4_3" name="obs3_4_3" class="table-header"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="punto3_4">d)</td>
                        <td>Preparación de grupos de alumnado para olimpiadas competencias académicas o exámenes generales.</td>
                        <td id="p30Prep"><b>30</b></td>
                        <td class="td_form3_4">
                            <span id="cantPreparacion"></span>
                        </td>
                        <td id="cantPreparacion2"></td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comPreparacion" name="comPreparacion" oninput="onActv3Comision3_4()" value="{{ oldValueOrDefault('comPreparacion') }}">
                            @else
                            <span id="comPreparacion" name="comPreparacion"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                            <input id="obs3_4_4" name="obs3_4_4" class="table-header" type="text">
                            @else
                                <span id="obs3_4_4" name="obs3_4_4" class="table-header"></span>
                            @endif
                        </td>
                    </tr>
                    <tr><!--Tabla informativa Acreditacion Actividad 3.4-->
                        <td class="acreditacion" scope="col">Acreditacion: </td>
                        <td class="descripcionCAAC"><b>CAAC, Instancia que la otorga</b></td>
                    </tr>
                </tbody>
            </table>
                {{-- Lógica de botones --}}
                @if($userType != 'docente')
                <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                @endif
                {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                    <button type="submit" class="btn custom-btn printButtonClass" id="btn3_4">Enviar</button>
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
                        <h1>Convocatoria: {{ $convocatoria }}</h1>
                    </div>
                     <div><h3>Periodo: </h3> {{ $periodo }}</div>
                @endif
            </div>
        </center>
    
        <div id="piedepagina" style="margin-left: 500px;margin-top:10px;">
            <x-form-renderer :forms="[['view' => 'form3_4', 'startPage' => 8, 'endPage' => 8]]" />
        </div>
    </footer>

    </center>

    <script>
    

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