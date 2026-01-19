@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';


$elaboracionMappings = [];
for ($i = 1; $i <= 5; $i++) {
    $elaboracionMappings["elaboracion{$i}"] = "elaboracion{$i}";
    $elaboracionMappings["elaboracionSubTotal{$i}"] = "elaboracionSubTotal{$i}";
}

// --- Campos comunes a docente y dictaminador ---
$commonFields = [
    'comisionIncisoA' => 'comisionIncisoA',
    'comisionIncisoB' => 'comisionIncisoB',
    'comisionIncisoC' => 'comisionIncisoC',
    'comisionIncisoD' => 'comisionIncisoD',
    'comisionIncisoE' => 'comisionIncisoE',
    'actv3Comision' => 'actv3Comision',
    // 'score3_1' => 'score3_1',
    'obs3_1_1' => 'obs3_1_1',
    'obs3_1_2' => 'obs3_1_2',
    'obs3_1_3' => 'obs3_1_3',
    'obs3_1_4' => 'obs3_1_4',
    'obs3_1_5' => 'obs3_1_5',
];

// --- Configuración principal ---
$docenteConfig = array_merge([
    'formKey' => 'form3_1',
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint' => '/formato-evaluacion/get-docentes',
    'dictEndpoint' => '/formato-evaluacion/get-form-data31',
    'dictCollectionKey' => 'form3_1',
    'userTypeForDict' => 'dictaminador',

    // --- Mappings ---
    'docenteMappings' => array_merge(
        ['elaboracion' => 'elaboracion'], 
        $elaboracionMappings, 
        ['score3_1' => 'score3_1']
    ),
    'dictMappings' => array_merge(['elaboracion' => 'elaboracion'], $elaboracionMappings, $commonFields),

    // --- Campos ocultos ---
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

    'excludeTargets' => [],
    // --- Comportamiento al no encontrar datos ---
    'resetOnNotFound' => false,
    'resetValues' => (function() {
        $reset = [];
        for ($i = 1; $i <= 5; $i++) {
            $reset["elaboracion{$i}"] = '0';
            $reset["elaboracionSubTotal{$i}"] = '0';
        }
        $extra = [
            'comisionIncisoA' => '0',
            'comisionIncisoB' => '0',
            'comisionIncisoC' => '0',
            'comisionIncisoD' => '0',
            'comisionIncisoE' => '0',
            'actv3Comision_hidden' => '0',
            'obs3_1_1' => '',
            'obs3_1_2' => '',
            'obs3_1_3' => '',
            'obs3_1_4' => '',
            'obs3_1_5' => '',
        ];
        return array_merge($reset, $extra);
    })(),

    'printPagePairs' => [[2,3]],
],
);

// --- Configuración del formulario docente ---
if (!isset($docenteConfigForm)) {
    $extraFields = [];
    for ($i = 1; $i <= 5; $i++) {
        $extraFields[] = "elaboracion{$i}";
        $extraFields[] = "elaboracionSubTotal{$i}";
    }

    $extraFields = array_merge(
        $extraFields,
        ['comisionIncisoA','comisionIncisoB','comisionIncisoC','comisionIncisoD','comisionIncisoE',
         'actv3Comision','score3_1','obs3_1_1','obs3_1_2','obs3_1_3','obs3_1_4','obs3_1_5', 'score3_1', 'elaboracion']
    );

    $docenteConfigForm = [
        'extraFields' => $extraFields,
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
    @include('components.partials.partials')

    <x-head-resources />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/3.0.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
   <style>
/* --- Estilos específicos para Chrome en impresión --- */
@media print {
    body.chrome #convocatoria,
    body.chrome #convocatoria2 {
        font-size: 0.8rem;
        color: blue;
    }

    html {
        font-size: 2rem;
    }

    .print-footer {
        display: table-footer-group !important;
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    .first-page-footer { }
    .second-page-footer { }

    .first-page-footer {
        display: table-footer-group;
    }

    .second-page-footer {
        display: none;
    }

    table:nth-of-type(2) ~ table .second-page-footer {
        display: table-footer-group;
    }

    table:nth-of-type(2) ~ table .first-page-footer {
        display: none;
    }

    body {
        -webkit-print-color-adjust: exact;
        margin-left: 200px;
        margin-top: -10px;
        padding: 0;
        font-size: 0.7rem;
        padding-bottom: 50px;
    }

    .footerForm3_1 {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
    }

    .prevent-overlap {
        page-break-before: always;
        page-break-inside: avoid;
    }

    #convocatoria,
    #convocatoria2,
    #piedepagina1,
    #piedepagina2 {
        margin: 0;
        font-size: 0.7rem;
    }

    #piedepagina {
        margin: 0;
    }

    @page {
        size: landscape;
        margin: 20mm;
        counter-increment: page;
    }

    @page:first {
        counter-reset: page 2;
        counter-increment: page;
    }

    .page-number-display {
        display: block;
        text-align: center;
        font-size: 12px;
        position: fixed;
        bottom: 10px;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .page-footer {
        position: relative;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 12px;
        background-color: white;
        padding: 10px 0;
        border-top: 1px solid #ccc;
        page-break-after: always;
    }

    .page-footer.hidden-footer {
        display: none !important;
    }

    table tr {
        page-break-inside: avoid;
    }

    .table-wrap {
        height: 50px;
        page-break-inside: avoid;
    }

    /* Página 4 */
    .page-break[data-page="3"] .first-page-footer {
        display: table-footer-group !important;
    }

    .page-break[data-page="4"] .second-page-footer {
        display: table-footer-group !important;
    }

    .secretaria-style {
        font-weight: normal;
        font-size: 14px;
        margin-top: 10px;
        text-align: left;
    }

    .secretaria-style #piedepagina1 {
        float: right;
        display: inline-block;
        margin-left: 5px;
        font-weight: normal;
        color: #000;
    }

    .dictaminador-style {
        font-weight: normal !important;
        font-size: 16px;
        margin-top: 10px;
        text-align: center;
        white-space: nowrap;
    }

    .dictaminador-style#piedepagina2 {
        margin-left: 800px;
        margin-top: 10px;
        font-weight: normal !important;
        white-space: nowrap;
    }

    .secretaria-style#piedepagina2 {
        margin-left: 100px;
        margin-top: 0;
        font-weight: normal !important;
        display: inline-block;
        white-space: nowrap;
    }
}

/* --- Fuera de media queries --- */
#convocatoria2 {
    font-weight: normal;
    width: 100%;
    text-align: left;
    margin-top: 20px;
}

.table2 {
    margin-top: 30px;
}

body.dark-mode #elaboracion,
body.dark-mode #elaboracion2,
body.dark-mode #elaboracion3,
body.dark-mode #elaboracion4,
body.dark-mode #elaboracion5,
body.dark-mode #comisionIncisoA,
body.dark-mode #comisionIncisoB,
body.dark-mode #comisionIncisoC,
body.dark-mode #comisionIncisoD,
body.dark-mode #comisionIncisoE {
    background-color: transparent;
    color: #ffffff;
}

body.dark-mode [id^="obs3_1_"] {
    background-color: transparent;
    color: #ffffff;
}

.avoid-page-break {
    page-break-inside: avoid;
    page-break-after: avoid;
}

.table td,
.table th {
    padding: 2px !important;
    margin: 0 !important;
    --vertical-align: middle;
}

.table tr {
    height: auto !important;
}

body.light-mode [id^="btn3_"] {
    margin-left: 56.25rem;
    background-color: #528fb3;
}

body.dark-mode [id^="btn3_"] {
    margin-left: 56.25rem;
    background-color: #456483;
    color: floralwhite;
}

body.dark-mode [id^="btn3_"]:hover {
    background-color: #6a5b9f;
}

button#form3_1Button {
    margin-top: 5rem !important;
    margin-left: 61rem !important;
}

button.edit-button{
    margin-left: 67rem !important;
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

$formId = $docenteConfigForm['formId'] ?? 'form3_1';
$formNumber = '31';
@endphp

<button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

<div class="container mt-4" id="seleccionDocente">
    @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        <!-- Buscando docentes -->
        <x-docente-search />
    @endif
</div>

    <main class="container">
        <!--Form for Part 3_1 -->
        <form id="form3_1" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <input type="hidden" name="score3_1" id="score3_1_hidden">
            <input type="hidden" name="actv3Comision" id="actv3Comision_hidden">
           
                <!-- Actividad 3.1 Participación en actividades de diseño curricular -->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4" id="pMax60" for="">60</label>
                </h4>
            
                    <table class="table table-sm">
                        <x-table-header />
                        <tr>
                            <td colspan="5"><b>3. Calidad en la docencia</b></td>
                        <td id="docencia2">{{ $docencia ?? '0' }}</td>
                            <td class="actv3Comision" style="background-color: #ffcc6d; text-align: center; border: none; font-weight: bold;"></td>
                            <td></td>
                        </tr>
                        <!-- Sub-encabezados -->
                        <x-sub-headers-form3_1 :canonical="true"/>

                        <!-- Contenido Incisos a) y b) -->
                        <tbody data-page="3">
                            <tr class="table-wrap">
                                <td>a)</td>
                                <td>
                                    <label style="height:84px; width: 170px;">Plan de estudios de una carrera o posgrado nuevo o
                                        actualización</label>
                                </td>
                                <td>
                                    <label style="height:94px; width: 180px;">Responsable de la Comisión para la elaboración del
                                    documento</label>
                                </td>
                                <td id="puntaje60"><b>60</b></td>
                                <td class="elabInput"><span id="elaboracion">0</span></td>
                                <td><span id="elaboracionSubTotal1"></span></td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="comisionIncisoA" type="number" step="0.01" oninput="onActv3Comision()"
                                            value="{{ oldValueOrDefault('comisionIncisoA') }}" name="comisionIncisoA">
                                    @else
                                        <label id="comisionIncisoA" name="comisionIncisoA"></label>
                                    @endif
                                </td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="obs3_1_1" name="obs3_1_1" class="table-header" type="text">
                                    @else
                                        <label id="obs3_1_1" name="obs3_1_1" class="table-header"></label>
                                    @endif
                                </td>
                            </tr>
                            <tr  class="table-wrap">
                                <td>b)</td>
                                <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o
                                        actualización</label></td>
                                <td><label class="form3_1LabelDoc" for="">Colaboración en la Comisión para la elaboración del
                                        documento</label></td>
                                <td><span id="puntaje40"><b>40</b></span></td>
                                <td class="elabInput"><span id="elaboracion2">0</span></td>
                                <td><span id="elaboracionSubTotal2" for="" type="text"></span></td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="comisionIncisoB" type="number" step="0.01" oninput="onActv3Comision()"
                                            value="{{ oldValueOrDefault('comisionIncisoB') }}" name="comisionIncisoB">
                                    @else
                                        <label id="comisionIncisoB"></label>
                                    @endif
                                </td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="obs3_1_2" name="obs3_1_2" type="text">
                                    @else
                                        <label id="obs3_1_2" name="obs3_1_2"></label>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="avoid-page-break" style="display: flex; justify-content: space-between; padding-top: -20px;">
                        <div id="convocatoria">
                            <!-- Mostrar convocatoria -->
                            @if(isset($convocatoria))
                                <div style="margin-right: -500px;">
                                    <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                                </div>
                            @endif
                        </div>
                        <div id="piedepagina1"
                            class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">
                            Página 3 de 33
                        </div>
                    </div><br>

                    <table class="table table-sm table2">
                        <x-table-header />
                        
                        <tr>
                            <td colspan="5"><b>3. Calidad en la docencia</b></td>
                        <td id="docencia">{{ $docencia ?? '0' }}</td>
                            <td class="actv3Comision" style="background-color: #ffcc6d; text-align: center; border: none; font-weight: bold;"></td>
                            <td></td>
                        </tr>
                        <!--Sub Encabezados-->
                        <x-sub-headers-form3_1 />
                        
                        <!-- Contenido Incisos c), d) y e) -->
                        <tbody class="page-break" data-page="4">
                            <tr class="table-wrap">
                                <td>c)</td>
                                <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                <td><label class="form3_1LabelDoc">Elaboración de contenidos mínimos</label></td>
                                <td><label id="puntaje10" for=""><b>10</b></label></td>
                                <td class="elabInput"><span id="elaboracion3">0</span></td>
                                <td><span id="elaboracionSubTotal3" for="" type="text"></span></td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="comisionIncisoC" for="" type="number" step="0.01" oninput="onActv3Comision()" value="{{ oldValueOrDefault('comisionIncisoC') }}" name="comisionIncisoC">
                                    @else
                                        <label id="comisionIncisoC" name="comisionIncisoC"></label>
                                    @endif
                                </td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="obs3_1_3" name="obs3_1_3" class="table-header" type="text">
                                    @else
                                        <label id="obs3_1_3" name="obs3_1_3" class="table-header"></label>
                                    @endif
                                </td>
                            </tr>
                            <tr  class="table-wrap">
                                <td>d)</td>
                                <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                <td><label class="form3_1LabelDoc">Elaboración de programas de asignatura</label></td>
                                <td><label id="puntaje20" for=""><b>20</b></label></td>
                                <td class="elabInput"><span id="elaboracion4">0</span></td>
                                <td><span id="elaboracionSubTotal4"></span></td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="comisionIncisoD" for="" type="number" step="0.01" oninput="onActv3Comision()" value="{{ oldValueOrDefault('comisionIncisoD') }}" name="comisionIncisoD">
                                    @else
                                        <label id="comisionIncisoD" name="comisionIncisoD"></label>
                                    @endif
                                </td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="obs3_1_4" name="obs3_1_4" class="table-header" type="text">
                                    @else
                                        <label id="obs3_1_4" name="obs3_1_4" class="table-header"></label>
                                    @endif
                                </td>
                            </tr>
                            <tr  class="table-wrap">
                                <td>e)</td>
                                <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                <td><label class="form3_1LabelDoc">Actualización de programas de asignatura</label></td>
                                <td><label id="p10" for=""><b>10</b></label></td>
                                <td class="elabInput"><span id="elaboracion5">0</span></td>
                                <td><span id="elaboracionSubTotal5"></span></td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="comisionIncisoE" for="" type="number" step="0.01" oninput="onActv3Comision()" value="{{ oldValueOrDefault('comisionIncisoE') }}" name="comisionIncisoE">
                                    @else
                                        <label id="comisionIncisoE" name="comisionIncisoE"></label>
                                    @endif
                                </td>
                                <td class="comision actv comEstilos">
                                    @if($userType == 'dictaminador')
                                        <input id="obs3_1_5" name="obs3_1_5" class="table-header" type="text">
                                    @else
                                        <label id="obs3_1_5" name="obs3_1_5" class="table-header"></label>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <!-- Tabla informativa Acreditacion Actividad 3_1 -->

                                <th class="acreditacion" scope="col">Acreditacion: </th>

                                <th class="descripcion" style="white-space: nowrap;"><b>H.CGU</b> puntos a,b y e; <b>CAAC</b> puntos d y e</th>
                            </tr>
                        </thead>
                                </table>
                            {{-- Lógica de botones --}}
                            <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                            {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                            @if(!$hasData && $userType != 'secretaria')
                                <button type="submit" class="btn custom-btn printButtonClass" id="{{ $formId }}Button">Enviar</button>
                            @endif
                    <!--Convocatoria 2-->
                                <div class="avoid-page-break" style="display: flex; justify-content: space-between;padding-top: 15px;">
                                    <div id="convocatoria2">
                                        <!-- Mostrar convocatoria -->
                                        @if(isset($convocatoria))

                                            <div style="margin-right: -200px;white-space: nowrap;">
                                                <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                                            </div>
                                        @endif
                                    </div>

                                    <div id="piedepagina2"
                                        class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">
                                        Página 4 de 33
                                    </div>
                                </div>

        </form>
            </div>
    </main>
    <script>

//         document.addEventListener('evaluationDataLoaded', () => {
//     const visible = document.getElementById('score3_1');
//     const hidden = document.getElementById('score3_1_hidden');

//     if (visible && hidden) {
//         hidden.value = visible.textContent?.trim() || '0';
//     }
// });

    window.onload = function () {

                function preventOverlap() {
            const footerHeight = document.querySelector('footer')?.offsetHeight || 0;
            const elements = document.querySelectorAll('.prevent-overlap');

            elements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                if (rect.bottom > viewportHeight - footerHeight) {
                    element.style.pageBreakBefore = "always";
                }
            });
        }

        preventOverlap();

        // Actualizar la paginación antes de imprimir
        // window.addEventListener('beforeprint', updatePagination);
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

            console.log('DOM score3_1 antes init:', document.getElementById('score3_1')?.value || document.getElementById('score3_1')?.textContent);

        // initializeDataFromDOM();
        document.addEventListener('form3_1:modelHydrated', () => {
            console.log('Modelo listo:', window.data);
        });

        console.log('data.score3_1 despues init:', data.score3_1);
        console.log('docencia despues init:', docencia);
    </script>
    <script>
        console.log('Form3_1 Blade Loaded');
        console.log('Docente Config:', @json($docenteConfig));
    </script>

    @include('partials.docente-autocomplete', ['config' => $docenteConfig])
    @include('partials.submit-form', ['config' => $docenteConfigForm])
</body>

</html>
