@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

use App\Models\UsersResponseForm1;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!isset($periodo)) {
    $targetUser = Auth::user();
    $teacherEmail = $teacherEmailFromUrl ?? request('email');
    if ($teacherEmail) {
        $found = User::where('email', $teacherEmail)->first();
        if ($found) $targetUser = $found;
    }
    
    $form1 = UsersResponseForm1::where('user_id', $targetUser->id)->first();
    $periodo = ($form1 && $form1->periodo) ? $form1->periodo : (UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido');
    $convocatoria = ($form1 && $form1->convocatoria) ? $form1->convocatoria : 'Convocatoria no asignada';
}
$docenteConfig = $docenteConfig ?? [
    'formKey' => 'form3_15',

    // Endpoints base
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data315',

    // Clave de colección dentro del JSON de dictaminadores
    'dictCollectionKey'   => 'form3_15',

    // Tipo de usuario que debe gatillar la carga de respuestas de dictaminadores
    'userTypeForDict'     => '',

    // ---- Mapeos cuando se selecciona un docente ----
    'docenteMappings' => [
        // puntajes principales
        'score3_15'          => 'score3_15',

        // cantidades y subtotales
        'cantPatentes'     => 'cantPatentes',
        'subtotalPatentes'  => 'subtotalPatentes',
        'cantPrototipos' => 'cantPrototipos',
        'subtotalPrototipos'=> 'subtotalPrototipos',

    ],

    // ---- Mapeos de datos desde dictaminadores ----
    'dictMappings' => [

        // cantidades y subtotales
        'cantPatentes'       => 'cantPatentes',
        'subtotalPatentes'    => 'subtotalPatentes',
        'cantPrototipos' => 'cantPrototipos',
        'subtotalPrototipos'=> 'subtotalPrototipos',


        // comisiones y observaciones
        'comisionPatententes'   => 'comisionPatententes',
        'comisionPrototipos' => 'comisionPrototipos',
        'obsPatentes'  => 'obsPatentes',
        'obsPrototipos'   => 'obsPrototipos',

        // totales
        'score3_15'                     => 'score3_15',
        'comision3_15'                 => 'comision3_15',
        '.comision3_15'                 => 'comision3_15',
        '#comision3_15'                 => 'comision3_15',
    ],

    // ---- Inputs ocultos que se llenan desde docenteData.form3_15 ----
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
        'score3_15' => '0',
        '#comision3_15' => '0',
        'cantPatentes' => '0',
        'subtotalPatentes' => '0',
        'cantPrototipos' => '0',
        'subtotalPrototipos' => '0',
        'comisionPatententes' => '0',
        'comisionPrototipos' => '0',
        'obsPatentes'   => '',
        'obsPrototipos' => '',

    ],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        // Campos adicionales que se enviarán junto al form
        'extraFields' => [
            'comision3_15',
            'score3_15',
        // Demas datos
        'cantPatentes',
        'subtotalPatentes',
        'cantPrototipos',
        'subtotalPrototipos',
        'comisionPatententes',
        'comisionPrototipos',
        'obsPatentes',
        'obsPrototipos',
            
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
    button#edit-btn-form3_15{
        margin-left:67rem;
    }
    .secretaria-style {
        font-size: 14px;
        margin-top: 10px;
        text-align: left;
    }
    .dictaminador-style {
        font-size: 16px;
        margin-top: 10px;
        text-align: center;
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
$checkFields = ['comision3_15'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_15';
$formNumber = '315';
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
        <form id="form3_15" action="/formato-evaluacion/store-form315" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
        <!--3.15 Registro de patentes y productos de investigación tecnológica y educativa -->
        <h4>Puntaje máximo
            <label class="bg-black text-white px-4 mt-3" for="">60</label>
        </h4>
        <table class="table table-sm tutorias">
            <thead>
                <tr>
                    <th scope="col" colspan=3>Actividad</th>
                    <th class="table-ajust" scope="col" colspan="5"></th>
                    <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                    <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th id="seccion3_15" class="acreditacion" colspan="8">3.15 Registro de patentes y productos de investigación tecnológica y educativa</th>
                    <th id="score3_15">0</th>
                    <th id="comision3_15">0</th>
                    
                </tr>
                <tr>
                    <th colspan="2"></th>
                    <th class="acreditacion">Puntaje</th>
                    <th class="acreditacion">Cantidad</th>

                    <th colspan="4"></th>
                    <th class="acreditacion">Subtotal</th>
                    <th colspan="1"></th>
                    <th class="obsv acreditacion" scope="col">Observaciones</th>
              
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>a)</td>
                    <td class="td_3_15">Registro de patentes</td>
                    <td id="puntajePatentes"><b>60</b></td>
                    <td id="cantPatentes"></td>
                    <td colspan="4"></td>
                    <td id="subtotalPatentes">0</td>
                    <td class="td_form3_15">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionPatententes" id="comisionPatententes" value="{{ oldValueOrDefault('comisionPatententes') }}" oninput="onActv3Comision3_15()">
                        @else
                            <span name="comisionPatententes" id="comisionPatententes"></span>
                        @endif               
                    </td>
                    <td class="td_form3_15">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsPatentes" id="obsPatentes">
                        @else
                            <span name="obsPatentes" id="obsPatentes"></span>
                        @endif                      
                    </td>
                </tr>
                <tr>
                    <td>b)</td>
                    <td class="td_3_15">Desarrollo de prototipos</td>
                    <td id="puntajePrototipos"><b>30</b></td>
                    <td id="cantPrototipos"></td>
                    <td colspan="4"></td>
                    <td id="subtotalPrototipos">0</td>
                    <td class="td_form3_15">
                        @if($userType == 'dictaminador')
                            <input type="number" step="0.01" name="comisionPrototipos" id="comisionPrototipos" value="{{ oldValueOrDefault('comisionPrototipos') }}" oninput="onActv3Comision3_15()">
                        @else
                            <span name="comisionPrototipos" id="comisionPrototipos"></span>
                        @endif
                    </td>
                    <td class="td_form3_15">
                        @if($userType == 'dictaminador')
                            <input class="table-header" type="text" name="obsPrototipos" id="obsPrototipos">
                        @else
                            <span name="obsPrototipos" id="obsPrototipos"></span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <!--Tabla informativa Acreditacion Actividad 3.15-->
        <table>
            <thead>
                <tr>
                    <th class="acreditacion" scope="col">Acreditacion: </th>
        
                    <th class="descripcion"><b>IMPI</b></th>
                </tr>
            </thead>
        </table>
            @if($userType != 'docente')
            <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
            @endif
            {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
            @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
            <button type="submit" class="btn custom-btn printButtonClass" id="btn3_15">Enviar</button>
            @endif
        </form>
    </main>
    <center>
    <footer id="footerForm3_15">
        <center>
            <div id="convocatoria" class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
                <!-- Mostrar convocatoria -->
                @if(isset($convocatoria))
                    @if($userType == 'dictaminador')
                        <div style="margin-right: -700px;">
                            <span style="font-size: 1.5em;">Convocatoria: {{ $convocatoria }}</span>
                        </div>
                    @elseif($userType == 'secretaria')
                        <div style="margin-right: 60px; margin-left: 100px; padding-right: 12px; text-align:left;">
                            <span style="font-size: 1.5em;">Convocatoria: {{ $convocatoria }}</span>
                        </div>
                    @else
                        <span>Convocatoria: {{ $convocatoria }}</span>
                    @endif
                @endif
            </div>
            <div class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
                @if(isset($periodo))
                    @if($userType == 'dictaminador' || $userType == 'secretaria')
                        <div><span style="font-size: 1.17em;">Periodo: </span> {{ $periodo }}</div>
                    @else
                        <span style="margin-left: 50px;">Periodo: {{ $periodo }}</span>
                    @endif
                @endif
            </div>
        </center>

        <div id="piedepagina" style="margin-left: 500px;margin-top:10px;">
            <x-form-renderer :forms="[['view' => 'form3_15', 'startPage' => 22, 'endPage' => 22]]" />
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