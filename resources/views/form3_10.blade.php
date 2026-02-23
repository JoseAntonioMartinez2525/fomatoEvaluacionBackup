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
    'formKey' => 'form3_10',

    // Endpoints base
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data310',

    // Clave de colección dentro del JSON de dictaminadores
    'dictCollectionKey'   => 'form3_10',

    // Tipo de usuario que debe gatillar la carga de respuestas de dictaminadores
    'userTypeForDict'     => '',

    // ---- Mapeos cuando se selecciona un docente ----
    'docenteMappings' => [
        // puntajes principales
        'score3_10'          => 'score3_10',

        // campos grupales e individuales
        'grupalesCant'       => 'grupalesCant',
        'evaluarGrupales'    => 'evaluarGrupales',
        'individualCant'     => 'individualCant',
        'evaluarIndividual'  => 'evaluarIndividual',
    ],

    // ---- Mapeos de datos desde dictaminadores ----
    'dictMappings' => [
        // campos grupales e individuales (comisión y observaciones)
        'comisionGrupal'   => 'comisionGrupal',
        'comisionIndividual'=> 'comisionIndividual',
        'obsGrupal'        => 'obsGrupal',
        'obsIndividual'    => 'obsIndividual',
        '#comisionGrupal'   => 'comisionGrupal',
        '#comisionIndividual'=> 'comisionIndividual',
        '#obsGrupal'        => 'obsGrupal',
        '#obsIndividual'    => 'obsIndividual',
        '.comisionGrupal'   => 'comisionGrupal',
        '.comisionIndividual'=> 'comisionIndividual',
        '.obsGrupal'        => 'obsGrupal',
        '.obsIndividual'    => 'obsIndividual',

        // totales
        'score3_10'                     => 'score3_10',
        'comision3_10'                 => 'comision3_10',
        '.comision3_10'                 => 'comision3_10',
        '#comision3_10'                 => 'comision3_10',
    ],

    // ---- Inputs ocultos que se llenan desde docenteData.form3_10 ----
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
        'score3_10' => '0',
        '#comision3_10' => '0',
        'comisionGrupal' => '0',
        'comisionIndividual' => '0',
        'obsGrupal' => '',
        'obsIndividual' => '',
        'grupalesCant' => '0',
        'evaluarGrupales' => '0',
        'individualCant' => '0',
        'evaluarIndividual' => '0',
    ],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        // Campos adicionales que se enviarán junto al form
        'extraFields' => [
            'score3_10',
            'grupalesCant',
            'evaluarGrupales',
            'individualCant',
            'evaluarIndividual',
            'comisionGrupal',
            'comisionIndividual',
            'obsGrupal',
            'obsIndividual',
            'comision3_10',
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
    <title>Evaluación docente</title>    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-head-resources />

    <link href="{{ asset('css/onePage.css') }}" rel="stylesheet">

    <style>
        button#btn3_10Button{
            margin-left: 60rem;
        }
    .secretaria-style {
        font-size: 14px;
        margin-top: 10px;
        text-align: center;
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
$userType = $userType ?? (Auth::user() ? Auth::user()->user_type : null);
$user_identity = Auth::user() ? Auth::user()->id : null;
$baseUrl = url('/formato-evaluacion');
$docenteConfig['baseUrl'] = $baseUrl;
$docenteConfigForm['baseUrl'] = $baseUrl;
$hasData = false;
$checkFields = ['comision3_10'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_10';
$formNumber = '310';
@endphp
{{-- @php dd($userType) @endphp --}}


    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass dark-mode-button"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
            {{-- Buscar Docentes: --}}
            <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_10 -->
        <form id="form3_10" action="/formato-evaluacion/store-form310" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <!--3.10 Trabajos dirigidos para la titulación de estudiantes-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">115</label>
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
                        <th id="seccion3_10" class="acreditacion" colspan="8">3.10 Tutorías a estudiantes</th>
                        <th id="score3_10">0</th>
                        <th id="comision3_10">0</th>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th class="acreditacion">Puntaje</th>
                        <th class="acreditacion">Cantidad</th>
                        <th colspan="6"></th>

                        <th class="obsv acreditacion" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <thead>
                        <tr>
                            <!--Tutorias a estudantes 3_10 individuales, grupales -->
                            <td>a)</td>
                            <td class="td_3_10">Por alumno(a) por semestre, grupales</td>
                            <td id="puntajeGrupales"><b>3</b> </td>
                            <td id="grupalesCant"></td>
                            <td colspan="4"></td>

                            <td id="evaluarGrupales"></td>
                            <td class="td_obs" id="td_comisionGrupal">
                            @if ($userType == 'dictaminador')

                                <input type="number" step="0.01" id="comisionGrupal" name="comisionGrupal" oninput="onActv3Comision3_10()"
                                    value="{{ oldValueOrDefault('comisionGrupal') }}">                         
                            @else
                                <span id="comisionGrupal" name="comisionGrupal"></span>
                            @endif    
                            </td>
                            <td class="td_obs" id="td_obsGrupal">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsGrupal" name="obsGrupal">
                            @else
                            <span id="obsGrupal" name="obsGrupal"></span>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td>b)</td>
                            <td class="td_3_10">Por alumno(a) por semestre, individuales</td>
                            <td id="puntajeIndividual"><b>6</b></td>
                            <td id="individualCant"></td>
                            <td colspan="4"></td>

                            <td id="evaluarIndividual"></td>
                            <td class="td_obs" id="td_comisionIndividual">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionIndividual" name="comisionIndividual" oninput="onActv3Comision3_10()"
                                        value="{{ oldValueOrDefault('comisionIndividual') }}"> 
                            @else
                                <span id="comisionIndividual"  name="comisionIndividual"></span>
                            @endif    
                            </td>
                            <td class="td_obs" id="td_obsIndividual">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsIndividual" name="obsIndividual">
                            @else
                            <span id="obsIndividual" name="obsIndividual"></span>
                            @endif
                            </td>
                        </tr>
                    </thead>
                    </tbody> 
                    </table>
                    <!--Tabla informativa Acreditacion Actividad 3.10-->
                    <table>
                        <thead>
                            <tr>
                                <th class="acreditacion" scope="col">Acreditacion: </th>

                                <th class="descripcion"><b>DDIE</b> </th>

                            </tr>
                        </thead>
                    </table>
                        {{-- Lógica de botones --}}
                        @if($userType != 'docente')
                        <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                        @endif
                        {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                        @if(!$hasData && $userType !='controlador' && $userType != 'docente')
                        <button type="submit" class="btn custom-btn printButtonClass" id="btn3_10Button">Enviar</button>
                        @endif
            </form>
    </main>
    <center>
<footer id="footerForm3_10">
    <center>
        <div id="convocatoria" class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
            <!-- Mostrar convocatoria -->
            @if(isset($convocatoria))
                @if($userType == 'dictaminador')
                    <div style="margin-right: -700px;">
                        <span style="font-size: 1.5em;">Convocatoria: {{ $convocatoria }}</span>
                    </div>
                @elseif($userType =='controlador')
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
                @if($userType == 'dictaminador' || $userType =='controlador')
                    <div><span style="font-size: 1.17em;">Periodo: </span> {{ $periodo }}</div>
                @else
                    <span style="margin-left: 50px;">Periodo: {{ $periodo }}</span>
                @endif
            @endif
        </div>
    </center>

    <div id="piedepagina" style="margin-left: 500px;margin-top:10px;">
        <x-form-renderer :forms="[['view' => 'form3_10', 'startPage' => 16, 'endPage' => 16]]" />
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

        toggleDarkMode();
    }); 
              
    </script>

    @include('partials.docente-autocomplete', ['config' => $docenteConfig])
    @include('partials.submit-form', ['config' => $docenteConfigForm])
</body>

</html>