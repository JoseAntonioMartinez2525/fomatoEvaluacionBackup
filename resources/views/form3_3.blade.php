@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$user = Auth::user();
$userType = $user->user_type;
$user_identity = $user->id; 
// datos para cada formulario
$docenteConfig = [
        'formKey' => 'form3_3',
        'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data', 
        'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data33',
        'dictCollectionKey' => 'form3_3',
        'userTypeForDict' => '',
        'docenteMappings' => [
        // score y su copia
        'score3_3' => 'score3_3',
        '#score3_3_copy' => 'score3_3',           // selector explícito para el segundo valor
        // rc y subtotales
        'rc1' => 'rc1',
        'rc2' => 'rc2',
        'rc3' => 'rc3',
        'rc4' => 'rc4',
        'stotal1' => 'stotal1',
        'stotal2' => 'stotal2',
        'stotal3' => 'stotal3',
        'stotal4' => 'stotal4',
        // comisiones y sus copias (puedes usar clase o id)
        '.comision3_3' => 'comision3_3',
        '#comision3_3' => 'comision3_3',
        '.comision3_3_copy' => 'comision3_3',
        '#comision3_3_copy' => 'comision3_3',
        ],
        // Mapeos para respuestas de dictaminadores (si aplica)
    'dictMappings' => [
        // comisiones / comIncisos
        '.comision3_3' => 'comision3_3',
        '.comision3_3_copy' => 'comision3_3',
        '#comision3_3' => 'comision3_3',
        '#comision3_3_copy' => 'comision3_3',
        'comIncisoA' => 'comIncisoA',
        'comIncisoB' => 'comIncisoB',
        'comIncisoC' => 'comIncisoC',
        'comIncisoD' => 'comIncisoD',
        // observaciones (span o elementos de texto)
        '.obs3_3_1' => 'obs3_3_1',
        '.obs3_3_2' => 'obs3_3_2',
        '.obs3_3_3' => 'obs3_3_3',
        '.obs3_3_4' => 'obs3_3_4',
        // repetir score/rc/stotals para sobrescribir si vienen desde dictaminador
        'score3_3' => 'score3_3',
        '#score3_3_copy' => 'score3_3',
        'rc1' => 'rc1',
        'rc2' => 'rc2',
        'rc3' => 'rc3',
        'rc4' => 'rc4',
        'stotal1' => 'stotal1',
        'stotal2' => 'stotal2',
        'stotal3' => 'stotal3',
        'stotal4' => 'stotal4',
    ],

    // Inputs ocultos que deben llenarse desde docenteData.form3_3
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
        'score3_3' => '0',
        '#score3_3_copy' => '0',
        '.comision3_3' => '0',
        '.comision3_3_copy' => '0',
        '.obs3_3_1' => '',
        '.obs3_3_2' => '',
        '.obs3_3_3' => '',
        '.obs3_3_4' => '',
    ],

    // control de print/footers por pares de páginas
    'printPagePairs' => [[6, 7]],
     'convocatoriaSelectors' => ['#convocatoria_copy','#piedepagina_copy'],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'score3_3',
            'comision3_3',
            'rc1', 'rc2', 'rc3', 'rc4',
            'stotal1', 'stotal2', 'stotal3', 'stotal4',
            'comIncisoA',
            'comIncisoB',
            'comIncisoC',
            'comIncisoD',
            'obs3_3_1', 
            'obs3_3_2', 
            'obs3_3_3',
            'obs3_3_4',
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
        @media print {
            body.chrome #convocatoria, 
            /* body.chrome #convocatoria_copy {
                font-size: 1.2rem;
                color: blue;
            } */
        }


        html{
            font-size: 1rem;
        }

    
        .table3_3_2{
            margin-top: 200px;
        }

        #convocatoria_copy{
            font-weight: bold;
            
        }

        .espaciadoConvocatoria{
                margin-top: 100px;
            }


        @media print {
            .print-footer { /* Estilos comunes para ambos footers en la impresión */
                display: table-footer-group !important; /* Asegura que se muestre como footer */
                position: fixed; /* Para que se pegue al final de la página */
                bottom: 0;
                width: 100%;
            }
            .first-page-footer {
                /* Estilos específicos para el footer de la primera página */
            }
            .second-page-footer {
                /* Estilos específicos para el footer de la segunda página */
            }
            /* Oculta el footer que no corresponde a la página actual */
            .first-page-footer {
                display: table-footer-group;
            }
            .second-page-footer {
                display: none;
            }
            table:nth-of-type(2) ~ table .second-page-footer { /* Selecciona el segundo footer solo cuando hay dos tablas antes */
                display: table-footer-group;
            }
            table:nth-of-type(2) ~ table .first-page-footer { /* Oculta el primer footer cuando hay dos tablas antes */
                display: none;
            }
            body {
                -webkit-print-color-adjust: exact;
            }

            .espaciadoConvocatoria{
                margin-top: 100px;
            }
        }

            @media print {
            .page-footer {
                position: relative;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                font-size: 10px;
                background-color: white;
                padding: 10px 0;
                border-top: 1px solid #ccc;
                page-break-after: always; /* Asegura el salto de página después del footer */
            }
            body {
                
                margin-left: 200px ;
                margin-top: -10px;
                padding: 0;
                font-size: .7rem;
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

            #convocatoria, #convocatoria_copy, #piedepagina, #piedepagina_copy {
                margin: 0;
                font-size: .7rem;
            }

            #piedepagina {
                margin: 0;
            }

            @page {
                size: landscape;
                margin: 20mm; /* Ajusta según sea necesario */
                counter-increment: page;
                
            }
            
            @page:first {
            counter-reset: page 2; /* Initialize the counter to 2 for the first page */
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
            
            
        }

        .page-footer.hidden-footer {
            display: none !important;
        }


        @media print {
            hidden-footer {
                display: none !important;
            }

            /* Prevent page breaks within table rows */
            table tr {
                page-break-inside: avoid;
            }

            .table-wrap{
            height: 100px; 
            page-break-inside: avoid; 
            }


            /* Página 4 */
            /* Mostrar el footer correcto según la página */
            .page-break[data-page="6"] .first-page-footer {
                display: table-footer-group !important;
            }

            .page-break[data-page="7"] .second-page-footer {
                display: table-footer-group !important;
            }

                .page-number:before {
            content: "Página " counter(page) " de 34";
            }

            .secretaria-style {
                font-weight: bold;
                font-size: 14px;
                margin-top: 10px;
                text-align: left;
                
            }

            .secretaria-style #piedepagina {
                float: right;
                display: inline-block;
                margin-left: 5px;
                font-weight: normal; /* Opcional, si quieres menos énfasis */
                color: #000;
            }

            .dictaminador-style {
                font-weight: bold;
                font-size: 16px;
                margin-top: 10px;
                text-align: center;
            }

            .dictaminador-style#piedepagina_copy {
                margin-left: 0px;
                margin-top: 10px;
                font-weight: normal!important;

            }

            /* Estilo para secretaria o userType vacío */
            .secretaria-style#piedepagina_copy {
                float: right;
                display: inline-block;
                margin-left: 5px;
                font-weight: normal; /* Opcional, si quieres menos énfasis */
                color: #000;
            }
            }

            body.dark-mode [class^="obs3_3_"]{
                color: white;
                background-color: transparent;
            }

            body.dark-mode .comIncisoA, body.dark-mode .comIncisoB, body.dark-mode .comIncisoC,
            body.dark-mode .comIncisoD{
                color: black;
            }

            body.dark-mode td.cantidad{
                color:white;
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

            .elabInput{
                height: 2rem;
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
    $hasData = false;
    $checkFields = ['comision3_3'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }
$formId = $docenteConfigForm['formId'] ?? 'form3_3';
$formNumber = '33';
@endphp    
<button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

<div class="container mt-4" id="seleccionDocente">
    @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        <!-- Buscando docentes -->
        <x-docente-search />
    @endif
</div>

    <main class="container">
        <!--Form for Part 3_3 -->
        <form id="form3_3" action="/formato-evaluacion/store-form33" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <div>
                <!-- Actividad 3.3 Publicaciones relacionadas con la docencia -->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">100</label>
                </h4>
            </div>
            <table class="table table-sm">
                <x-table-header />
                <tbody class="page-break" data-page="6">
                    <tr>
                        <td class="seccion3_3" colspan="5">3.3 Publicaciones relacionadas con la docencia</td>
                        <td id="score3_3">0</td>
                        <td class="comision3_3" id="comision3_3"style="background-color: #ffcc6d; text-align: center; border: none; font-weight: bold;">0</td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <td class="incisos">Incisos</td>
                        <td class="obra">Obra</td>
                        <td>Actividad</td>
                        <td>Puntaje</td>
                        <td class="cantidad2">Cantidad</td>
                        <td colspan="2">SubTotal</td>
                        <td class="table-ajust2" scope="col">Observaciones</td>
                    </tr>
                    <!-- Primera tabla: Incisos a) y b) -->
                    <tr>
                        <td>a)</td>
                        <td>Libro de texto con editorial de reconocido prestigio</td>
                        <td>Autor(a)</td>
                        <td>
                            <center><b>100</b></center>
                        </td>
                        <td class="elabInput"><span class="rc1" id="rc1"></span></td>
                        <td class="stotal1" id="stotal1"></td>
                        <td class="comision actv">
                            @if($userType == 'dictaminador')
                                <input type="number" step="0.01" class="comIncisoA" id="comIncisoA" name="comIncisoA" oninput="onActv3Comision3()"
                                    value="{{ oldValueOrDefault('comIncisoA') }}">
                            @else
                                <span name="comIncisoA" class="comIncisoA"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                                <input id="obs3_3_1" name="obs3_3_1" class="obs3_3_1" type="text">
                            @else
                                <span class="obs3_3_1"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>1. Paquete didáctico, 2. Manual de operaciones</td>
                        <td>Autor(a)</td>
                        <td>
                            <center><b>50</b></center>
                        </td>
                        <td class="elabInput"><span class="rc2" id="rc2"></span></td>
                        <td class="stotal2" id="stotal2"></td>
                        <td class="comision actv">
                            @if($userType == 'dictaminador')
                                <input class="comIncisoB" id="comIncisoB" name="comIncisoB" type="number" step="0.01" oninput="onActv3Comision3()"
                                    value="{{ oldValueOrDefault('comIncisoB') }}">
                            @else
                                <span class="comIncisoB"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                                <input id="obs3_3_2" name="obs3_3_2" class="obs3_3_2" type="text">
                            @else
                                <span class="obs3_3_2"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="espaciadoConvocatoria">
                <div id="convocatoria" 
                    class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
                    @if(isset($convocatoria))
                        @if($userType == 'dictaminador')
                            <span style="margin-right: 700px; display: inline-block;">
                                <h1>Convocatoria: </h1>
                            </span>
                            <span>{{ $periodo }}</span>
                        @elseif($userType == 'secretaria')
                            <span style="margin-right: 60px; margin-left: 100px; display:nonek;padding-right: 12px; text-align:left;">
                                <h1>Convocatoria: </h1>
                            </span>
                            <span>{{ $periodo }}</span>
                            <span id="piedepagina" style="display: none; margin-left: 20px;">
                                Página 3 de 34
                            </span>
                        @endif
                    <span>Convocatoria: {{ $convocatoria }}</span>
                    <span style="margin-left: 50px;">Periodo: {{ $periodo }}</span>
                    @endif
                </div>
            <div>
                @if($userType == 'dictaminador')
                    <span id="piedepagina" style="display: none;margin-left:800px;">Página 6 de 34</span>
                    <span id="piedepagina" style="display: none; float: right;">Página 6 de 34</span>
                @endif
            </div>
            </div><br><br>

            <table class="table table-sm table3_3_2">
                <x-table-header />
                <tbody class="page-break" data-page="7">
                    <tr>
                        <td class="seccion3_3" colspan="5">3.3 Publicaciones relacionadas con la docencia</td>
                        <td id="score3_3_copy">0</td>
                        <td class="comision3_3_copy" id="comision3_3_copy" style="background-color: #ffcc6d; text-align: center; border: none; font-weight: bold;">0</td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <td class="incisos">Incisos</td>
                        <td class="obra">Obra</td>
                        <td>Actividad</td>
                        <td>Puntaje</td>
                        <td class="cantidad2">Cantidad</td>
                        <td>SubTotal</td>
                    </tr>
                    <!-- Segunda tabla: Incisos c) y d) -->
                    <tr>
                        <td>c)</td>
                        <td>
                            <textarea name="" class="textAreaForms" cols="30" rows="10">1. Capítulo de libro,&#10 2. Elaboración de Manuales de laboratorio o instructivos,&#10 3. Diseño y construcción de equipo de laboratorio,&#10 4. Elaboración de material audiovisual,&#10 5. Elaboración de software educativo,&#10 6. Notas de curso,&#10 7. Antología comentada,&#10 8.Monografía.</textarea>
                        </td>
                        <td>Autor(a)</td>
                        <td>
                            <center><b>30</b></center>
                        </td>
                        <td class="elabInput"><span class="rc3" id="rc3"></span></td>
                        <td class="stotal3" id="stotal3"></td>
                        <td class="comision actv">
                            @if($userType == 'dictaminador')
                                <input class="comIncisoC" id="comIncisoC" name="comIncisoC" type="number" step="0.01" oninput="onActv3Comision3()"
                                    value="{{ oldValueOrDefault('comIncisoC') }}">
                            @else
                                <span class="comIncisoC"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                                <input id="obs3_3_3" name="obs3_3_3" class="obs3_3_3" type="text">
                            @else
                                <span class="obs3_3_3"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>d)</td>
                        <td>
                            <textarea name="" class="textAreaForms" cols="30" rows="10">1. Traducción de libro,&#10 2.Traducción de material de apoyo didáctico,&#10 3. Traducciones publicadas de artículos.</textarea>
                        </td>
                        <td>Autor(a)</td>
                        <td>
                            <center><b>25</b></center>
                        </td>
                        <td class="elabInput"><span class="rc4" id="rc4"></span></td>
                        <td class="stotal4" id="stotal4"></td>
                        <td class="comision actv">
                            @if($userType == 'dictaminador')
                                <input class="comIncisoD" id="comIncisoD" name="comIncisoD" type="number" step="0.01" oninput="onActv3Comision3()"
                                    value="{{ oldValueOrDefault('comIncisoD') }}">
                            @else
                                <span class="comIncisoD"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if($userType == 'dictaminador')
                                <input id="obs3_3_4" name="obs3_3_4" class="obs3_3_4" type="text">
                            @else
                                <span class="obs3_3_4"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.3-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>

                        <th class="descripcionCAAC"><b>CAAC, Instancia que la otorga</b></th>
                    </tr>
                </thead>
            </table>
            <br>
                {{-- Lógica de botones --}}
                @if($userType != 'docente')
                <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                @endif
                {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                    <button type="submit" class="btn custom-btn printButtonClass" id="btn3_3">Enviar</button>
                @endif

            <div id="piedepagina_copy"
                class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">
                Página 7 de 34
            </div>
          
        </form>
    </main>
    <script>


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



    document.addEventListener('DOMContentLoaded', function () {

            const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
            if (toggleDarkModeButton) {
                const widthDarkButton = window.outerWidth - 230;
                toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
            }

            toggleDarkMode();
        });
    </script>
    <script>
        console.log('Form3_3 Blade Loaded');
        console.log('Docente Config:', @json($docenteConfig));
    </script>

    {{-- partial blade para autocompletar datos--}}
    @include('partials.docente-autocomplete', ['config' => $docenteConfig])
    @include('partials.submit-form', ['config' => $docenteConfigForm])
</body>

</html>