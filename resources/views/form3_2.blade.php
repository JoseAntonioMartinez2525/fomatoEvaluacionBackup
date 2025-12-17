@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

 $docenteConfig = array_merge([
        'formKey' => 'form3_2',
        'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data', 
        'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-dictaminators-responses',
        'dictCollectionKey' => 'form3_2',
        'userTypeForDict' => '',
        'docenteMappings' => [
            'score3_2' => 'score3_2',
            'r1' => 'r1',
            'r2' => 'r2',
            'r3' => 'r3',
            'cant1' => 'cant1',
            'cant2' => 'cant2',
            'cant3' => 'cant3',
        ],
        'dictMappings' => [
            'comision3_2' => 'comision3_2',
            'prom90_100' => 'prom90_100',
            'prom80_90' => 'prom80_90',
            'prom70_80' => 'prom70_80',
            'obs3_2_1' => 'obs3_2_1',
            'obs3_2_2' => 'obs3_2_2',
            'obs3_2_3' => 'obs3_2_3',
            'score3_2' => 'score3_2',
            'r1' => 'r1',
            'r2' => 'r2',
            'r3' => 'r3',
            'cant1' => 'cant1',
            'cant2' => 'cant2',
            'cant3' => 'cant3',
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
        'score3_2' => '0',
        '#comision3_2' => '0',
        '#obs3_2_1' => '',
        '#obs3_2_2' => '',


    ],
        'printPagePairs' => [[3,4]],
    ],

);

    if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'score3_2',
            'comision3_2',
            'r1',
            'r2',
            'r3',
            'cant1',
            'cant2',
            'cant3',
            'prom90_100',
            'prom80_90',
            'prom70_80',
            'obs3_2_1',
            'obs3_2_2',
            'obs3_2_3',
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

#piedepagina { display: none; }

    @media print {
        #piedepagina{
            display: block !important;
        }
        body {
            margin-left: 200px;
            margin-top: -10px;
            padding: 0;
            font-size: .8rem;

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


        page-break-after: auto; /* La última página no necesita salto extra */

}
    body.dark-mode #r1, body.dark-mode #r2, body.dark-mode #r3, body.dark-mode #prom90_100, 
    body.dark-mode #prom80_90, body.dark-mode #prom70_80 {
        color: white;
        background-color: transparent;
    }

    body.dark-mode [id^="obs3_2"] {
        color: black;
    }

    [id^="btn3_"]{
    margin-left: 900px;
}

body.dark-mode [id^="btn3_"]{
        background-color: #456483;
        color: floralwhite;
}

body.dark-mode [id^="btn3_"]:hover {
    background-color: #6a5b9f;
    
}

button#edit-btn-form3_2{
    margin-left: 0rem;
}

button#form3_2Button {
    margin-top: 5rem !important;
    margin-left: -7rem !important;
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
    $checkFields = ['actv3Comision'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }
$formId = $docenteConfigForm['formId'] ?? 'form3_2';
$formNumber = '32';
@endphp

<button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

<div class="container mt-4" id="seleccionDocente">
    @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        <!-- Buscando docentes -->
        <x-docente-search />
    @endif
</div>

    <main class="container">
        <!-- Form for Part 3_1 -->
        <form id="form3_2" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <div>
            <!-- Actividad 3.2 Calidad del desempeño docente evaluada por el alumnado -->
            <h4 id="puntajeMaxForm3_2">Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">50</label>
            </h4>
            </div>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Actividad</th>
                            <th class="table-ajust" scope="col"></th>
                            <th class="table-ajust" scope="col"></th>
                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                            {{-- <th class="table-ajust" scope="col">Observaciones</th> --}}
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td id="seccion3_2" colspan="3" style="height: 50px; width: 200px;">3.2 Calidad del desempeño
                                docente
                                evaluada por el alumnado
                            </td>
                            <td id="score3_2" for="">0</td>
                            <td id="comision3_2">0</td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                              <td></td>
                            <td>Puntaje</td>
                            <td class="text-center bg-transparent">Cantidad</td>
                            <td colspan="2"></td>
                            
                            <td class="text-center table-ajust" scope="col">Observaciones</td>
                            
                        </tr>
                    </thead>
                    <thead>
                        <!--prom90-100-->
                        <tr>
                            <td class="ranges">
                                <center>Promedio 90-100</center>
                            </td>
                            <td id="ran1"><b>50</b></td>
                            <td class="elabInput" style="background-color: #c1cfd3;text-align:right;"><span id="r1" name="r1"></span></td>
                            <td id="cant1" name="cant1">0</td>
                            <td class="td_obs">
                            @if($userType == 'dictaminador')
                                <input id="prom90_100" type="number" step="0.01"
                                    oninput="onActv3_2Comision()" value="{{ oldValueOrDefault('prom90_100') }}">
                            @else
                            <span id="prom90_100" name="prom90_100"></span>
                            @endif
                            </td>
                            <td class="td_obs">
                            @if($userType == 'dictaminador')
                                <input id="obs3_2_1" name="obs3_2_1" type="text">
                            @else
                                <span id="obs3_2_1" name="obs3_2_1"></span>
                            @endif

                            </td>
                        </tr>
                        <!--prom80-90-->
                        <tr>
                            <td class="ranges">
                                <center>Promedio 80-90</center>
                            </td>
                            <td id="ran2"><b>40</b></td>
                            <td class="elabInput" style="background-color: #c1cfd3;"><span id="r2" name="r2"></span></td>
                            <td id="cant2" name="cant2">0</td>

                            <td class="td_obs">
                             @if($userType == 'dictaminador')   
                                <input id="prom80_90" type="number" step="0.01"
                                    oninput="onActv3_2Comision()" value="{{ oldValueOrDefault('prom80_90') }}">
                            @else
                                <span id="prom80_90" name="prom80_90"></span>
                            @endif
                            </td>
                            <td class="td_obs">
                            @if($userType == 'dictaminador')    
                                <input id="obs3_2_2" name="obs3_2_2" type="text">
                            @else
                                <span id="obs3_2_2" name="obs3_2_2"></span>
                            @endif
                            </td>
                        </tr>
                        <!--prom70-80-->
                        <tr>
                            <td class="ranges">
                                <center>Promedio 70-80</center>
                            </td>
                            <td id="ran3"><b>30</b></td>
                            <td class="elabInput" style="background-color: #c1cfd3;">
                                <span id="r3" name="r3"></span>
                            </td>
                            <td id="cant3">0</td>
                            <td class="td_obs">
                            @if($userType == 'dictaminador')  
                                <input id="prom70_80" placeholder="0" type="number" step="0.01"
                                        oninput="onActv3_2Comision()" value="{{ oldValueOrDefault('prom70_80') }}">
                            @else
                            <span id="prom70_80" name="prom70_80"></span>
                            @endif
                            </td>
                            <td class="td_obs">
                            @if($userType == 'dictaminador')  
                                <input id="obs3_2_3"  name="obs3_2_3" type="text">
                            @else
                                <span id="obs3_2_3" name="obs3_2_3"></span>
                            @endif
                            </td>
                        </tr>
                    </thead>
                    </table>
                    <!--Tabla informativa Acreditacion Actividad 3.2-->
                <table>
                    <thead>
                        <tr><br>
                            <th class="acreditacion" scope="col">Acreditacion: </th>

                            <th class="descripcionDDIE"><b>DDIE</b>
                            <th> 
                            {{-- Lógica de botones --}}
                            <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
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
            <x-form-renderer :forms="[['view' => 'form3_2', 'startPage' => 5, 'endPage' => 5]]" />
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

    {{-- partial blade para autocompletar datos--}}
    @include('partials.docente-autocomplete', ['config' => $docenteConfig])
    @include('partials.submit-form', ['config' => $docenteConfigForm])
</body>

</html>