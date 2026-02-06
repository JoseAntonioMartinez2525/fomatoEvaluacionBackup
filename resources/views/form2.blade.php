@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

use App\Models\UsersResponseForm1;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!isset($convocatoria) || !isset($periodo)) {
    $targetUser = Auth::user();
    $teacherEmail = $teacherEmailFromUrl ?? request('email');
    if ($teacherEmail) {
        $found = User::where('email', $teacherEmail)->first();
        if ($found) $targetUser = $found;
    }
    
    $form1 = UsersResponseForm1::where('user_id', $targetUser->id)->first();
    $convocatoria = ($form1 && $form1->convocatoria) ? $form1->convocatoria : 'Convocatoria no asignada';
    $periodo = ($form1 && $form1->periodo) ? $form1->periodo : (UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido');
    
    $convocatoria2 = $convocatoria;
    $periodo2 = $periodo;
}

 $docenteConfig = [
        'formKey' => 'form2',
        'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data', 
        'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data2',
        'dictCollectionKey' => 'form2',
        'userTypeForDict' => '',
        'docenteMappings' => [
            'horasActv2' => 'horasActv2',
            'puntajeEvaluar' => 'puntajeEvaluar',
            'input[name="email"]' => 'email', // Llena el input oculto del email

        ],
        'dictMappings' => [
            'dictMode' => '', 
            'horasActv2' => 'horasActv2',
            'puntajeEvaluar' => 'puntajeEvaluar',
            'comision1' => 'comision1',
            'obs1' => 'obs1',

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
        'puntajeEvaluar' => '0',
        '#comision1' => '0',
        '#obs1' => '',



    ],
    'convocatoriaSelectors' => [
        '#convocatoria',
        '#convocatoria2',
    ]

    ];

    if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'horasActv2',
            'puntajeEvaluar',
            'comision1',
            'obs1',
        ],
        'exposeAs' => 'submitForm',
        'selectedEmailInputId' => 'selectedDocenteEmail',
        'searchInputId' => 'docenteSearch',
        'formId' => 'form2',
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

</head>
<style>
    .datosPrimarios{
        margin-left: 50px;
    }

    @media print{
    .datosPrimarios{
        margin-left: 100px;
        font-size: .8rem;
    }
    }

    .datosConvocatoria{
        margin-left:80px;
    }
    @media print{
        .datosConvocatoria{
            font-size: .8rem;
            margin-left: 100px;
        }


        page-break-after: auto; /* La última página no necesita salto extra */  
    }

    body.dark-mode #convocatoria2, body.dark-mode #periodo2, body.dark-mode #nombre2, 
    body.dark-mode #area2, body.dark-mode #departamento2 {
    background-color:transparent;
    color: #ffffff;
    font-weight: bold;
}

body.dark-mode #obs1, body.dark-mode #comision1 {
    color:white;
    background-color: transparent;
}

td{
    font-size: 1rem;
}

#convocatoria2{
    margin-left: 2rem!important;
}

#obs1{
    height: 50px;
}

textarea#obs1_input{
    text-align: left;
}

@media print {
    #obs1 {
        display: block; /* Oculta el textarea al imprimir */

    }
    #obs1_print {
        display: block; /* Muestra el span al imprimir */
        white-space: pre-wrap; /* Conserva los saltos de línea y ajustes */
        word-wrap: break-word;
    }
}

body.dark-mode .table-header {
    background-color: transparent!important;
    color: white!important;
    
}

</style>
<script>
    window.isDarkModeGlobal = {{ $darkMode ?? false ? 'true' : 'false' }};
</script>
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
    $checkFields = ['comision1'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }

$formId = $docenteConfigForm['formId'] ?? 'form2';
$formNumber = '2';
@endphp
<button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>
<div class="container mt-4 printButtonClass">
    @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        <!-- Buscando docentes -->
        <x-docente-search />

    @endif
</div> <br><br>


<div class="mostrar">
    <main class="container">
        <!-- Form for Part 2 -->
    <form id="form2" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">            
            @csrf
            <div><br>
               <div class="datosConvocatoria">
                <div class="row">
                    <label for="convocatoria">Convocatoria:</label>
                    <div class="valor"><span class="input-header" id="convocatoria2">{{ $convocatoria2 ?? '' }}</span></div>
                </div>
                <div class="row">
                    <label for="periodo">Periodo de evaluación:</label>
                    <div class="valor"><span id="periodo2" class="input-header">{{ $periodo2 ?? '' }}</span></div>
                </div>
                <div class="row">
                    <label for="nombre">Nombre del personal académico:</label>
                    <div class="valor"><span id="nombre2" class="input-header">{{ $nombre2 ?? '' }}</span></div>
                </div>
                <div class="row">
                    <label for="area">Área de Conocimiento:</label>
                    <div class="valor"><span id="area2" class="input-header">{{ $area2 ?? '' }}</span></div>
                </div>
                <div class="row">
                    <label for="departamento">Departamento Académico:</label>
                    <div class="valor"><span id="departamento2" class="input-header">{{ $departamento2 ?? '' }}</span></div>
                </div>
            </div><br>
            <center class="printCenter"><h5>Instrucciones</h5></center>
            
            <div class="container flex">
                <p class="instrucciones">1 La persona a ser evaluada deberá completar la información en
                    cantidades u horas en los campos
                    marcados en <u>color gris</u>. <br>
                    2 La Comisión Dictaminadora deberá llenar los campos marcados en color azul cielo (puntajes totales o
                    subtotales, según sea el caso). <br>
                    3 No se deberán modificar fórmulas, ni agregar o quitar renglones. <br>
                    4 Este formato deberá presentarse en forma independiente de la documentación que acrediten las
                    actividades realizadas. <b>Para la evaluación no es necesario entregar las obras completas-libros,
                    manuales, publicaciones,etc.,</b> sino entregar el documento probatorio que se indique en la Guía de
                    definiciones. <br>
                    5 La Comisión Dictaminadora no tomará en cuenta documentación que no esté contemplada dentro del
                    formato de evaluación, asimismo no se aceptará documentación presentada de forma extemporánea.
            </div>
            </div>
            <div class="datosPrimarios">
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4" for="">100</label>
                </h4>
            </div>
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <input type="hidden" id="puntajeEvaluarInput" name="puntajeEvaluar" value="0">
            <input type="hidden" id="horasActv2Input" name="horasActv2" value="">

            <table class="table table-sm datosPrimarios">
                <thead>
                    <tr>
                        <th scope="col">Actividad</th>
                        <th class="table-ajust" scope="col">Años</th>
                        <th class="table-ajust" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust" scope="col">Puntaje de la Comisión Dictaminadora</th>
                        <th class="table-ajust" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="margin-right: auto;"><b>1. Permanencia en las actividades de la docencia</b></td>
                        <td class="horasActv2">
                            <span id="horasActv2"></span>
                        </td>
                        <td class="puntajeEvaluar">
                            <span id="puntajeEvaluar">0</span>
                        </td>
                        <td class="td_obs table-header comision">
                            <div class="filled">
                        @if($userType == 'dictaminador')
                            <!-- Mostrar input si es 'dictaminador' -->
                            <input type="number" step="0.01" id="comision1" name="comision1" class="table-header comision" step="any"
                            value="{{ oldValueOrDefault('comision1') }}">
                        @else
                            <!-- Mostrar span si es otro tipo de usuario -->
                            <span id="comision1" class="table-header comision"></span>
                        @endif
                             </div>
                        </td>
                        <td class="td_obs">
                            <div class="filled">
                        @if($userType == 'dictaminador')
                            <!-- Mostrar textarea para edición -->
                            <textarea id="obs1" name="obs1" class="table-header" oninput="document.getElementById('obs1_print').textContent = this.value"></textarea>
                            <!-- Span solo para impresión -->
                            <span id="obs1_print" class="d-none d-print-block"></span>
                        @else
                            <!-- Mostrar span para otros usuarios -->
                            <span id="obs1" class="table-header"></span>
                        @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="datosPrimarios">
                <thead>
                    <tr>
                        <th style="font-weight: normal; text-size: 20px;" scope="col">Acreditación: </th>
                        <th style="width:60px;padding-left: 100px;">SG</th>
                        <th style="font-weight: normal; padding-left: 100px;">6.25 puntos por cada año de experiencia
                            docente cumplido. A partir de los 16 años de experiencia docente se otorgarán los 100 puntos
                        </th>
                    </tr>
                </thead>
            </table>
               {{-- Debug temporal visible en la vista (quítalo después)
                <div style="background:#ffd; padding:6px; margin-bottom:8px;">
                    <strong>DEBUG:</strong>
                    hasData = {{ $hasData ? 'true' : 'false' }} |
                    userType = {{ $userType ?? 'NULL' }} |
                    formId = {{ $formId }}
                </div> --}}

                {{-- Lógica de botones --}}
                <x-edit-button formId="{{ $formId }}" :has-data="$hasData" :user-type="$userType" />
                {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                @if(!$hasData && $userType != 'secretaria')
                    <button type="submit" class="btn custom-btn printButtonClass" id="{{ $formId }}_1Button">Enviar</button>
                @endif
        </form>
    </main>

    </div>
    <center>
    <footer>
        <center>
            <div id="convocatoria">
                <!-- Mostrar convocatoria -->
                @if(isset($convocatoria))
                    <div style="margin-right: -700px;">
                        <h1>Convocatoria: {{ is_string($convocatoria) ? $convocatoria : ($convocatoria->convocatoria ?? 'No disponible') }}</h1>
                    </div>
                     
                @endif
            </div>
            <div>{{ $periodo }}</div>
        </center>

        <div id="piedepagina" style="margin-left: 500px; margin-top: 10px;">

    <x-form-renderer :forms="[['view' => 'form2', 'startPage' => 1, 'endPage' => 1]]" />
        </div>
    </footer>
</center>

    <script>

document.getElementById('horasActv2Input').value =
    document.getElementById('horasActv2')?.textContent?.trim() || '0';


    document.addEventListener("DOMContentLoaded", function () {
        // Aseguramos que las páginas físicas se calculen en el momento de impresión
        window.addEventListener('beforeprint', () => {
            const totalPagesElement = document.querySelectorAll('.total-pages');
            totalPagesElement.forEach(el => {
                el.textContent = Math.ceil(document.body.offsetHeight / window.innerHeight);
            });
        });

        // Mostrar el número actual de página en cada footer
        const footers = document.querySelectorAll('#piedepagina');
        footers.forEach((footer, index) => {
            const pageNumberElement = footer.querySelector('.page-number');
            pageNumberElement.textContent = "página "+ (index + 1) + " de 34";
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

        const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
        if (toggleDarkModeButton) {
            const widthDarkButton = window.outerWidth - 230;
            toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
        }

        toggleDarkMode();
    });

        document.addEventListener('DOMContentLoaded', function () {
            // Evento antes de imprimir
            window.addEventListener('beforeprint', function () {
                const isLandscape = window.matchMedia('(orientation: landscape)').matches;
                const isScale100 = window.matchMedia('(resolution: 96dpi)').matches; // Verifica si la escala es 100%
                const form = document.getElementById('form2');
                const footer = document.querySelector('footer');
                const main = document.querySelector('main');

                if (form && footer && isLandscape && isScale100) {
                    // Tamaño del papel en píxeles para Letter (8.5 x 11 pulgadas) a 96 DPI
                    const paperHeight = isLandscape ? 816 : 1056; // 816px (horizontal), 1056px (vertical)
                    const formRect = form.getBoundingClientRect();
                    const formStyles = window.getComputedStyle(form);
                    const formMarginBottom = parseFloat(formStyles.marginBottom);

                    // Calcula la posición del footer
                    const footerTop = formRect.bottom + formMarginBottom + 20; // Incluye margen inferior
                    if (footerTop + footer.offsetHeight <= paperHeight) {
                        // Si el footer cabe en la misma página
                        footer.style.position = 'absolute';
                        footer.style.top = `${footerTop}px`;
                    } else {
                        // Si no cabe, reduce el tamaño de letra dentro del formulario y main
                        if (main) {
                            main.style.setProperty('font-size', '9px', 'important'); // Ajusta el tamaño de letra
                        }
                        if (form) {
                            form.style.setProperty('font-size', '9px', 'important'); // Ajusta el tamaño de letra
                        }
                    }
                }
            });

            // Evento después de imprimir
            window.addEventListener('afterprint', function () {
                const footer = document.querySelector('footer');
                const main = document.querySelector('main');
                const form = document.getElementById('form2');

                if (footer) {
                    // Restaura los estilos originales del footer
                    footer.style.position = '';
                    footer.style.top = '';
                }
                if (main) {
                    main.style.fontSize = ''; // Restaura el tamaño de letra original
                }
                if (form) {
                    form.style.fontSize = ''; // Restaura el tamaño de letra original
                }
            });
        });

    </script>
    <script>
        console.log('Form2 Blade Loaded');
        console.log('Docente Config:', @json($docenteConfig));
    </script>
    @include('partials.docente-autocomplete', ['config' => $docenteConfig])
    @include('partials.submit-form', ['config' => $docenteConfigForm])
</body>


</html>
