@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

 $docenteConfig = [
        'formKey' => 'form2_2',
        'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data', 
        'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-dictaminators-responses',
        'dictCollectionKey' => 'form2_2',
        'userTypeForDict' => '',
        'docenteMappings' => [
            'hoursText' => 'hours',
            'horasPosgrado' => 'horasPosgrado',
            'horasSemestre' => 'horasSemestre',
            'dse'=> 'dse',
            'dse2'=> 'dse2',


        ],
        'dictMappings' => [

            'hoursText' => 'hours',
            'horasPosgrado' => 'horasPosgrado',
            'horasSemestre' => 'horasSemestre',
            'dse'=> 'dse',
            'dse2'=> 'dse2',
            'actv2Comision' => 'actv2Comision',
            'comisionPosgrado' => 'comisionPosgrado',
            'comisionLic' => 'comisionLic',
            'obs2' => 'obs2',
            'obs2_2' => 'obs2_2',

        ],
        'fillHiddenFrom' => [
            'user_id' => 'user_id',
            'email' => '',
            'user_type' => 'user_type',
        ],
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
            'hoursText' => '0',
            'horasPosgrado' => '0',
            'horasSemestre' => '0',
            'dse'=> '0',
            'dse2'=> '0',
            'actv2Comision' => '0',
            'obs2' => '',
            'obs2_2' => '',


    ],
  
    ];


    if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'hoursText',
            'hours',
            'horasPosgrado',
            'horasSemestre',
            'dse',
            'dse2',
            'actv2Comision',
            'comisionPosgrado',
            'comisionLic',
            'obs2',
            'obs2_2',
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
    
    body.dark-mode span, body.dark-mode #horasPosgrado, body.dark-mode #horasSemestre, 
    body.dark-mode #comisionPosgrado,  body.dark-mode #comisionLic{
    background-color:transparent;
        color: #ffffff;
    }

body.dark-mode nav.nav.flex-column {
    background: linear-gradient(90deg, rgb(14, 34, 69),  rgb(13, 31, 63)) !important;
}

body.dark-mode nav.nav.flex-column a:hover {
   color:  rgb(122, 164, 237);
}

.edit-button {
    margin-top: 2rem!important;
    margin-left: 20rem!important;
}

</style>  
<script>
    window.isDarkModeGlobal = {{ $darkMode ?? false ? 'true' : 'false' }};
</script>  
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
    $checkFields = ['actv2Comision'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }

$formId = $docenteConfigForm['formId'] ?? 'form2_2';

@endphp
<button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

<div class="container mt-4 printButtonClass">
    @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        <!-- Buscando docentes -->
        <x-docente-search />
    @endif
</div>
    <main class="container">
        <!-- Form for Part 2_2 -->
        <form id="form2_2" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            <div>
                <!-- Activity 2: Commitment in Teaching Performance -->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">200</label>
                </h4>
            </div>
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th colspan="2" scope="col">Actividad</th>

                        <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2"><b>2. Dedicación en el Desempeño docente</b></td>

                        <td id="hours" name="hours" for=""><label id="hoursText" for="">0</label></td>
                        <td id="actv2Comision" for=""></td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td class="table-ajust text-center" scope="col">Horas</td>
                        <td colspan="2"></td>
                        <td class="obsv table-ajust" scope="col">Observaciones</td>
                    </tr>
                    <tr>
                        <td><label for="">a) Posgrado</label>
                            <label for="">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Semestre </label>
                        </td>
                        <td class="cantidad"><span id="horasPosgrado" name="horasPosgrado" class="horasActv2"></span>
                        </td>
                        <td class="puntajeEvaluar2"><label id="dse" name="dse" class="puntajeEvaluar" type="text"></label></td>
                            @if($userType == 'dictaminador')
                                <td class="comision actv filled">
                                    <input type="number" step="0.01" id="comisionPosgrado" name="comisionPosgrado" for="" oninput="onActv2Comision()"
                                    value="{{ oldValueOrDefault('comisionPosgrado') }}">
                                </input>
                                </td>
                            <td class="filled"><input id="obs2" name="obs2" class="table-header" type="text"></td>

                        @else
                            <td class="comision actv"><span id="comisionPosgrado" name="comisionPosgrado"></span></td>
                            <td class="td_obs"><span id="obs2" name="obs2" class="table-header"></span></td>

                        @endif

                    </tr>
                    <tr>
                        <td>b) Licenciatura y TSU
                            <label for="">&nbsp &nbsp &nbsp &nbsp Horas </label>
                        </td>
                        <td class="cantidad"><span id="horasSemestre" name="horasSemestre" class="horasActv2"></span>
                        </td>
                        <td class="puntajeEvaluar2"><label id="dse2" name="dse2" class="puntajeEvaluar" type="text"></label></td>
                        @if($userType == 'dictaminador')
                            <td class="comision actv"><input type="number" step="0.01" id="comisionLic" name="comisionLic" oninput="onActv2Comision()" 
                            value="{{ oldValueOrDefault('comisionLic') }}"></input>
                            </td>
                            <td><input id="obs2_2" name="obs2_2" class="table-header" type="text"></input></td>
                        @else
                        <td class="comision actv"><span id="comisionLic" name="comisionLic"></span>
                        </td>
                        <td class="td_obs"><span id="obs2_2" name="obs2_2" class="table-header"></span></td>
                        @endif
                    </tr>
                    </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <th style="font-weight: normal; text-size: 20px;" scope="col">Acreditacion: </th>
                                <th style="width:60px;padding-left: 100px;">DSE/DIIP</th>
                                <th style="font-weight: normal; padding-left: 100px;">8.5 puntos por cada hora/semana/año en cada
                                    caso
                                </th>
                                <th>
                                    {{-- Lógica de botones --}}
                                    <x-edit-button formId="{{ $formId }}" :has-data="$hasData" :user-type="$userType" />
                                    {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                                    @if(!$hasData && $userType != 'secretaria')
                                    <button type="submit" class="btn custom-btn printButtonClass" id="{{ $formId }}Button">Enviar</button>
                                    @endif
                                </th>
                            </tr>
                        </thead>
                    </table>
                </form>
            </main>
<center>
    <footer>
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
    <x-form-renderer :forms="[['view' => 'form2_2', 'startPage' => 2, 'endPage' => 2]]" />

        </div>
    </footer>
</center>
    <script>
document.getElementById('comisionLic').value = document.getElementById('comisionLic')?.textContent?.trim() || '0';
document.getElementById('comisionPosgrado').value = document.getElementById('comisionPosgrado')?.textContent?.trim() || '0';
document.getElementById('hoursText').value = document.getElementById('hours')?.textContent?.trim() || '0';
    
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