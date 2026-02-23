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
$docenteConfig =  $docenteConfig ?? [
        'formKey' => 'form3_7',
        'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data', 
        'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data37',
        'dictCollectionKey' => 'form3_7', // Ensure this matches the key in dictaminator responses
        'userTypeForDict' => '',
        'docenteMappings' => [
        // score y su copia
        'score3_7' => 'score3_7',     
        // cantidades y subtotales
        'puntaje3_7' => 'puntaje3_7',
        'puntajeHoras3_7' => 'puntajeHoras3_7',
        ],
        // Mapeos para respuestas de dictaminadores (si aplica)
    'dictMappings' => [
        // comisiones / comIncisos
        '#comision3_7' => 'comision3_7',
        'comisionDict3_7' => 'comisionDict3_7',

        // observaciones (span o elementos de texto)
        '#obs3_7_1' => 'obs3_7_1',

        // repetir score/rc/stotals para sobrescribir si vienen desde dictaminador
        'score3_7' => 'score3_7',
        // cantidades y subtotales
        'puntaje3_7' => 'puntaje3_7',
        'puntajeHoras3_7' => 'puntajeHoras3_7',
    ],

    // Inputs ocultos que deben llenarse desde docenteData.form3_7
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
        'score3_7' => '0',
        '#comision3_7' => '0',
        '#obs3_7_1' => '',


    ],

];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'score3_7',
            'puntaje3_7',
            'puntajeHoras3_7',
            'comision3_7',
            '#comisionDict3_7',
            'obs3_7_1',
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
<style>
#btn3_7{
    margin-left: 60rem;
}

body.dark-mode [id^="btn3_"]{
        background-color: #456483;
        color: floralwhite;
}

body.dark-mode [id^="btn3_"]:hover {
    background-color: #6a5b9f;
    
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
$user = Auth::user();
$userType = $user->user_type;
$user_identity = $user->id; 

    $hasData = false;
    $checkFields = ['comision3_7'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }
$formId = $docenteConfigForm['formId'] ?? 'form3_7';
$formNumber = '37';

@endphp

<button id="toggle-dark-mode" class="btn btn-secondary printButtonClass dark-mode-button"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

<div class="container mt-4" id="seleccionDocente">
    @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        {{-- Buscar Docentes: --}}
            <x-docente-search />
    @endif
</div>

    <main class="container">
        <!-- Form for Part 3_1 -->
        <form id="form3_7" action="/formato-evaluacion/store-form37" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <div>
                <!-- 3.7 Cursos de actualización disciplinaria recibidos dentro de su área de conocimiento  -->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">40</label>
                </h4>
            </div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">Actividad</th>
                        <th class="table-ajust" scope="col"></th>
                        <th class="table-ajust" scope="col"></th>
            
                        <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                        </th>
                        
                    </tr>
                </thead>
                <tbody>
                    <thead>
                        <tr>
                            <th id="seccion3_7" colspan=3 class="punto3_7" scope=col style="padding:5px;">3.7
                                Cursos de actualización
                                disciplinaria recibidos dentro de su área de conocimiento </th>
                            <td id="score3_7" for="">0</td>
                            <td id="comision3_7">0</td>
            
                        </tr>
                        <tr>
                            <td colspan="1"></td>
                            <td class="punto3_7">Factor</td>
                            <td class="punto3_7">Horas</td>
                            <td colspan="2"></td>
                            <td class="obsv table-ajust" scope="col">Observaciones</td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td>0.5 por cada hora</td>
                            <td id="pMedio2">0.5</td>
                            <td id="puntaje3_7" name="puntaje3_7"></td>
                            <td class="text-center" id="puntajeHoras3_7"></td>
                            <td class="td_obs" class="text-center">
                                @if ($userType == 'dictaminador')
                                    <input type="number" step="0.01" id="comisionDict3_7" name="comisionDict3_7" oninput="onActv3Comision3_7()" value="{{ oldValueOrDefault('comisionDict3_7') }}">
                                @else
                                    <span id="comisionDict3_7" name="comisionDict3_7"></span>
                                @endif
                                

                            </td>
                            <td class="td_obs">
                                @if ($userType == 'dictaminador')
                                    <input id="obs3_7_1" name="obs3_7_1" class="table-header" type="text">
                                @else
                                    <span id="obs3_7_1" name="obs3_7_1" class="table-header"></span>
                                @endif
                                
                            </td>
                        </tr>
                    </thead>
                    <!--Tabla informativa Acreditacion Actividad 3.7-->
                    <table>
                        <thead>
                            <tr>
                                <th class="acreditacion" scope="col">Acreditacion: </th>
                                <th class="descripcion"><b>JD,CAAC, instancia que organiza</b></th>
                            </tr>
                        </thead>
                    </table>
                </tbody>
            </table>
                {{-- Lógica de botones --}}
                @if($userType != 'docente')
                <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                @endif
                {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                @if(!$hasData && $userType !='controlador' && $userType != 'docente')
                    <button type="submit" class="btn custom-btn printButtonClass" id="btn3_7">Enviar</button>
                @endif
            </form>

    </main>
    <center>
<footer id="footerForm3_7">
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
        <x-form-renderer :forms="[['view' => 'form3_7', 'startPage' => 11, 'endPage' => 11]]" />
    </div>
</footer>
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