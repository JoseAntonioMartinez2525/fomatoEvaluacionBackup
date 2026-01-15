@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$docenteConfig = $docenteConfig ?? [
    'formKey' => 'form3_12',

    // Endpoints base
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
    'dictEndpoint'        => '/formato-evaluacion/get-dictaminators-responses',

    // Clave de colección dentro del JSON de dictaminadores
    'dictCollectionKey'   => 'form3_12',

    // Tipo de usuario que debe gatillar la carga de respuestas de dictaminadores
    'userTypeForDict'     => '',

    // ---- Mapeos cuando se selecciona un docente ----
    'docenteMappings' => [
        // puntajes principales
        'score3_12'          => 'score3_12',

        // cantidades y subtotales
        'cantCientifico'     => 'cantCientifico',
        'subtotalCientificos'  => 'subtotalCientificos',
        'cantDivulgacion'     => 'cantDivulgacion',
        'subtotalDivulgacion'  => 'subtotalDivulgacion',
        'cantTraduccion'     => 'cantTraduccion',
        'subtotalTraduccion'  => 'subtotalTraduccion',
        'cantArbitrajeInt'     => 'cantArbitrajeInt',
        'subtotalArbitrajeInt'  => 'subtotalArbitrajeInt',
        'cantArbitrajeNac'     => 'cantArbitrajeNac',
        'subtotalArbitrajeNac'  => 'subtotalArbitrajeNac',
        'cantSinInt'     => 'cantSinInt',
        'subtotalSinInt'  => 'subtotalSinInt',
        'cantSinNac'     => 'cantSinNac',
        'subtotalSinNac'  => 'subtotalSinNac',
        'cantAutor'     => 'cantAutor',
        'subtotalAutor'  => 'subtotalAutor',
        'cantEditor'     => 'cantEditor',
        'subtotalEditor'  => 'subtotalEditor',
        'cantWeb'     => 'cantWeb',
        'subtotalWeb'  => 'subtotalWeb',




    ],

    // ---- Mapeos de datos desde dictaminadores ----
    'dictMappings' => [

        // cantidades y subtotales
        'cantCientifico'     => 'cantCientifico',
        'subtotalCientificos'  => 'subtotalCientificos',
        'cantDivulgacion'     => 'cantDivulgacion',
        'subtotalDivulgacion'  => 'subtotalDivulgacion',
        'cantTraduccion'     => 'cantTraduccion',
        'subtotalTraduccion'  => 'subtotalTraduccion',
        'cantArbitrajeInt'     => 'cantArbitrajeInt',
        'subtotalArbitrajeInt'  => 'subtotalArbitrajeInt',
        'cantArbitrajeNac'     => 'cantArbitrajeNac',
        'subtotalArbitrajeNac'  => 'subtotalArbitrajeNac',
        'cantSinInt'     => 'cantSinInt',
        'subtotalSinInt'  => 'subtotalSinInt',
        'cantSinNac'     => 'cantSinNac',
        'subtotalSinNac'  => 'subtotalSinNac',
        'cantAutor'     => 'cantAutor',
        'subtotalAutor'  => 'subtotalAutor',
        'cantEditor'     => 'cantEditor',
        'subtotalEditor'  => 'subtotalEditor',
        'cantWeb'     => 'cantWeb',
        'subtotalWeb'  => 'subtotalWeb',


        // comisiones y observaciones
        'comisionDivulgacion'   => 'comisionDivulgacion',
        'obsDivulgacion' => 'obsDivulgacion',
        'comisionCientificos'   => 'comisionCientificos',
        'obsCientificos' => 'obsCientificos',
        'comisionTraduccion'   => 'comisionTraduccion',
        'obsTraduccion' => 'obsTraduccion',
        'comisionArbitrajeInt'   => 'comisionArbitrajeInt',
        'obsArbitrajeInt' => 'obsArbitrajeInt',
        'comisionArbitrajeNac'   => 'comisionArbitrajeNac',
        'obsArbitrajeNac' => 'obsArbitrajeNac',
        'comisionSinInt'   => 'comisionSinInt',
        'obsSinInt' => 'obsSinInt',
        'comisionSinNac'   => 'comisionSinNac',
        'obsSinNac' => 'obsSinNac',
        'comisionAutor'   => 'comisionAutor',
        'obsAutor' => 'obsAutor',
        'comisionEditor'   => 'comisionEditor',
        'obsEditor' => 'obsEditor',
        'comisionWeb'   => 'comisionWeb',
        'obsWeb' => 'obsWeb',



        // totales
        'score3_12'                     => 'score3_12',
        'comision3_12'                  => 'comision3_12',
    ],

    // ---- Inputs ocultos que se llenan desde docenteData.form3_12 ----
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
        'score3_12' => '0',
        '#comision3_12' => '0',
        'cantCientifico'=> '0',
        'subtotalCientificos'=> '0',
        'comisionCientificos'=> '0',
        'obsCientificos'=> '',
        'cantDivulgacion'=> '0',
        'subtotalDivulgacion'=> '0',
        'comisionDivulgacion'=> '0',
        'obsDivulgacion'=> '',
        'cantTraduccion'=> '0',
        'subtotalTraduccion'=> '0',
        'comisionTraduccion'=> '0',
        'obsTraduccion'=> '',
        'cantArbitrajeInt'=> '0',
        'subtotalArbitrajeInt'=> '0',
        'comisionArbitrajeInt'=> '0',
        'obsArbitrajeInt'=> '',
        'cantArbitrajeNac'=> '0',
        'subtotalArbitrajeNac'=> '0',
        'comisionArbitrajeNac'=> '0',
        'obsArbitrajeNac'=> '',
        'cantSinInt'=> '0',
        'subtotalSinInt'=> '0',
        'comisionSinInt'=> '0',
        'obsSinInt'=> '',
        'cantSinNac'=> '0',
        'subtotalSinNac'=> '0',
        'comisionSinNac'=> '0',
        'obsSinNac'=> '',
        'cantAutor'=> '0',
        'subtotalAutor'=> '0',
        'comisionAutor'=> '0',
        'obsAutor'=> '',
        'cantEditor'=> '0',
        'subtotalEditor'=> '0',
        'comisionEditor'=> '0',
        'obsEditor'=> '',
        'cantWeb'=> '0',
        'subtotalWeb'=> '0',
        'comisionWeb'=> '0',
        'obsWeb'=> '',


    ],

    'convocatoriaSelectors' => [
        '#convocatoria',
        '#convocatoria2',
    ],
];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        // Campos adicionales que se enviarán junto al form
        'extraFields' => [
            'comision3_12',
            'score3_12',
        // Demas datos
            'cantCientifico',
            'subtotalCientificos',
            'comisionCientificos',
            'obsCientificos',
            'cantDivulgacion',
            'subtotalDivulgacion',
            'comisionDivulgacion',
            'obsDivulgacion',
            'cantTraduccion',
            'subtotalTraduccion',
            'comisionTraduccion',
            'obsTraduccion',
            'cantArbitrajeInt',
            'subtotalArbitrajeInt',
            'comisionArbitrajeInt',
            'obsArbitrajeInt',
            'cantArbitrajeNac',
            'subtotalArbitrajeNac',
            'comisionArbitrajeNac',
            'obsArbitrajeNac',
            'cantSinInt',
            'subtotalSinInt',
            'comisionSinInt',
            'obsSinInt',
            'cantSinNac',
            'subtotalSinNac',
            'comisionSinNac',
            'obsSinNac',
            'cantAutor',
            'subtotalAutor',
            'comisionAutor',
            'obsAutor',
            'cantEditor',
            'subtotalEditor',
            'comisionEditor',
            'obsEditor',    
            'cantWeb',
            'subtotalWeb',
            'comisionWeb',
            'obsWeb',

     
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

    <style>
        body.chrome @media print {
            #convocatoria {
                font-size: 1.2rem;
                color: blue;
                /* Ejemplo de estilo específico para Chrome */
            }
        }

        @media print {

            footer {
                position: fixed;
                font-size: .9rem;
                bottom: 0;
                left: 0;
                width: 100%;
                text-align: center;
                font-size: 10px;
                background-color: white;
                /* Para asegurar que el footer no interfiera visualmente */
                z-index: 10;
                padding: 5px 0;
                border-top: 1px solid #ccc;
            }

            footer::after {
                position: fixed;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                font-size: 12px;
                background: white;
                padding: 5px;
                z-index: 10;
            }

            #convocatoria,
            #convocatoria2,
            #piedepagina1,
            #piedepagina2 {
                margin: 0;
                font-size: .6rem;
            }


            .page-number:before {
                content: "Página " counter(page) " de 33";
            }

            .secretaria-style {
                font-weight: normal;
                font-size: 14px;
                margin-top: 10px;
                text-align: left;
            }

            .secretaria-style #piedepagina1 {
                display: flex;
                justify-content: flex-end;
                font-weight: normal;
                /* Opcional, si quieres menos énfasis */
                color: #000;
            }

            .dictaminador-style {
                font-weight: bold;
                font-size: 16px;
                margin-top: 10px;
                text-align: center;
            }

            .dictaminador-style#piedepagina2 {
                display: flex;
                justify-content: flex-end;
                margin-top: 10px;
                font-weight: normal !important;
            }

            /* Estilo para secretaria o userType vacío */
            .secretaria-style#piedepagina2 {
                display: flex;
                justify-content: flex-end;
                margin-top: 0;
                font-weight: normal !important;
                display: inline-block;
            }

        }
        td{
            font-size: 1rem;
        }

        button#edit-btn-form3_12{
            margin-left: 15rem;
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
$checkFields = ['comision3_12'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_12';
$formNumber = '312';
    @endphp
{{-- @php dd($userType) @endphp --}}
    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo
        Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
            {{-- Buscar Docentes: --}}
            <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_1 -->
        <form id="form3_12" action="/formato-evaluacion/store-form312" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <!--3.12 Trabajos dirigidos para la titulación de estudiantes-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">150</label>
            </h4>
            <table class="table table-sm tutorias">
                <x-sub-headers-form3_12 :componentIndex="0" />
                <tbody data-page="18">
                    <!--3_12 Publicaciones de investigación incisos-->
                    <tr>
                        <td>a)</td>
                        <td>Autor(a) o coautor(a) de libros, técnicos, científicos y humanísticos</td>
                        <td>--</td>
                        <td>--</td>
                        <td id="puntajeCientificos">100</td>
                        <td id="cantCientifico"></td>
                        <td colspan="2"></td>
                        <td id="subtotalCientificos"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionCientificos" name="comisionCientificos"
                                    value="{{ oldValueOrDefault('comisionCientificos') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionCientificos" name="comisionCientificos" class="form3_19_dark"></span>
                            @endif                         
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsCientificos" name="obsCientificos">
                            @else
                                <span id="obsCientificos" name="obsCientificos" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>Autor(a) o coautor(a) de libros de divulgación</td>
                        <td>--</td>
                        <td>--</td>
                        <td id="puntajeDivulgacion">50</td>
                        <td id="cantDivulgacion"></td>
                        <td colspan="2" ></td>   
                        <td id="subtotalDivulgacion"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionDivulgacion" name="comisionDivulgacion"
                                    value="{{ oldValueOrDefault('comisionDivulgacion') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionDivulgacion" name="comisionDivulgacion" class="form3_19_dark"></span>
                            @endif                          </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsDivulgacion" name="obsDivulgacion">
                            @else
                                <span id="obsDivulgacion" name="obsDivulgacion" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td>Traducción de libros</td>
                        <td>--</td>
                        <td>--</td>
                        <td id="puntajeTraduccion">40</td>
                        <td id="cantTraduccion"></td>
                        <td colspan="2"></td>
                        <td id="subtotalTraduccion"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionTraduccion" name="comisionTraduccion"
                                    value="{{ oldValueOrDefault('comisionTraduccion') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionTraduccion" name="comisionTraduccion" class="form3_19_dark"></span>
                            @endif  
 
                     </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsTraduccion" name="obsTraduccion">
                            @else
                                <span id="obsTraduccion" name="obsTraduccion" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>d)</td>
                        <td>Autor(a) o coautor(a) de artículos</td>
                        <td>Con Arbitraje</td>
                        <td>Internacional</td>
                        <td id="puntajeArbitrajeInt">60</td>
                        <td id="cantArbitrajeInt"></td>
                        <td colspan="2"></td>
                        <td id="subtotalArbitrajeInt"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionArbitrajeInt" name="comisionArbitrajeInt"
                                    value="{{ oldValueOrDefault('comisionArbitrajeInt') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionArbitrajeInt" name="comisionArbitrajeInt" class="form3_19_dark"></span>
                            @endif  
  
                    </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsArbitrajeInt" name="obsArbitrajeInt">
                            @else
                                <span id="obsArbitrajeInt" name="obsArbitrajeInt" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>e)</td>
                        <td>Autor(a) o coautor(a) de artículos</td>
                        <td>Con Arbitraje</td>
                        <td>Nacional</td>
                        <td id="puntajeArbitrajeNac">30</td>
                        <td id="cantArbitrajeNac"></td>
                        <td colspan="2"></td>
                        <td id="subtotalArbitrajeNac"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionArbitrajeNac" name="comisionArbitrajeNac"
                                    value="{{ oldValueOrDefault('comisionArbitrajeNac') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionArbitrajeNac" name="comisionArbitrajeNac" class="form3_19_dark"></span>
                            @endif  
 
                     </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsArbitrajeNac" name="obsArbitrajeNac">
                            @else
                                <span id="obsArbitrajeNac" name="obsArbitrajeNac" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="10">
                            <!-- Mostrar convocatoria -->
                            <div id="convocatoria">
                                @if(isset($convocatoria))
                                        <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                                @endif
                            </div>
                        </td>
                        <td id="piedepagina1"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">Página 18 de 33</td>
                    </tr>
                </tbody>
            </table>

            <!--Tabla 2-->
            <table class="table table-sm tutorias">
                <x-sub-headers-form3_12 :componentIndex="1" />
                <tbody data-page="19">
                    <tr>
                        <td>f)</td>
                        <td>Autor(a) o coautor(a) de artículos</td>
                        <td>Sin Arbitraje</td>
                        <td>Internacional</td>
                        <td id="puntajeSinInt">15</td>
                        <td id="cantSinInt"></td>
                        <td colspan="2"></td>
                        <td id="subtotalSinInt"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionSinInt" name="comisionSinInt"
                                    value="{{ oldValueOrDefault('comisionSinInt') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionSinInt" name="comisionSinInt" class="form3_19_dark"></span>
                            @endif  
      
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsSinInt" name="obsSinInt">
                            @else
                                <span id="obsSinInt" name="obsSinInt" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>g)</td>
                        <td>Autor(a) o coautor(a) de artículos</td>
                        <td>Sin Arbitraje</td>
                        <td>Nacional</td>
                        <td id="puntajeSinNac">10</td>
                        <td id="cantSinNac"></td>
                        <td colspan="2"></td>
                        
                        <td id="subtotalSinNac"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionSinNac" name="comisionSinNac"
                                    value="{{ oldValueOrDefault('comisionSinNac') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionSinNac" name="comisionSinNac" class="form3_19_dark"></span>
                            @endif  
              
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsSinNac" name="obsSinNac">
                            @else
                                <span id="obsSinNac" name="obsSinNac" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>h)</td>
                        <td>Capítulo de libro especializado</td>
                        <td>Autor(a) o coautor (a) de capítulo de libro internacional o nacional</td>
                        <td>--</td>
                        <td id="puntajeAutor">25</td>
                        <td id="cantAutor"></td>
                        <td colspan="2"></td>
                        
                        <td id="subtotalAutor"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionAutor" name="comisionAutor"
                                    value="{{ oldValueOrDefault('comisionAutor') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionAutor" name="comisionAutor" class="form3_19_dark"></span>
                            @endif  
        
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsAutor" name="obsAutor">
                            @else
                                <span id="obsAutor" name="obsAutor" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>i)</td>
                        <td>Capítulo de libro especializado</td>
                        <td>Editor(a) o coeditor(a) de libro</td>
                        <td>--</td>
                        <td id="puntajeEditor">25</td>
                        <td id="cantEditor"></td>
                        <td colspan="2"></td>
                        
                        <td id="subtotalEditor"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionEditor" name="comisionEditor"
                                    value="{{ oldValueOrDefault('comisionEditor') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionEditor" name="comisionEditor" class="form3_19_dark"></span>
                            @endif  
   
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsEditor" name="obsEditor">
                            @else
                                <span id="obsEditor" name="obsEditor" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>j)</td>
                        <td>Sitio web</td>
                        <td>Diseño de sitio web</td>
                        <td>--</td>
                        <td id="puntajeWeb">20</td>
                        <td id="cantWeb"></td>
                        <td colspan="2"></td>
                        
                        <td id="subtotalWeb"></td>
                        <td class="td_obs text-center">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionWeb" name="comisionWeb"
                                    value="{{ oldValueOrDefault('comisionWeb') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionWeb" name="comisionWeb" class="form3_19_dark"></span>
                            @endif  
          
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsWeb" name="obsWeb">
                            @else
                                <span id="obsWeb" name="obsWeb" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.12-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>

                        <th class="descripcion"><b>Instancia que la otorga</b> </th>
                        <th>
                        @if($userType != 'docente')
                        <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                        @endif
                        {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                        @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                        <button type="submit" class="btn custom-btn printButtonClass" id="btn3_12">Enviar</button>
                        @endif
                        </th>
                    </tr>
                </thead>
            </table>
            <div style="display: flex; justify-content: space-between;padding-top: 150px;">
                <div>
                    <!-- Mostrar convocatoria -->
                    <div id="convocatoria2" style="margin-right: -700px;">
                        @if(isset($convocatoria))
                            <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                        @endif
                    </div>
                </div>

                <div id="piedepagina2"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">
                    Página 19 de 33
                </div>
            </div><br><br>
        </form>
    </main>
    <script>
        

        let cant3_12 = ['cantCientifico', 'cantDivulgacion', 'cantTraduccion', 'cantArbitrajeInt', 'cantArbitrajeNac', 'cantSinInt', 'cantSinNac', 'cantAutor', 'cantEditor', 'cantWeb'];
        let subtotal3_12 = ['subtotalCientificos', 'subtotalDivulgacion', 'subtotalTraduccion', 'subtotalArbitrajeInt', 'subtotalArbitrajeNac', 'subtotalSinInt', 'subtotalSinNac', 'subtotalAutor', 'subtotalEditor', 'subtotalWeb'];
        let comision3_12 = ['comisionCientificos', 'comisionDivulgacion', 'comisionTraduccion', 'comisionArbitrajeInt', 'comisionArbitrajeNac', 'comisionSinInt', 'comisionSinNac', 'comisionAutor', 'comisionEditor', 'comisionWeb'];
        let obs3_12 = ['obsCientificos', 'obsDivulgacion', 'obsTraduccion', 'obsArbitrajeInt', 'obsArbitrajeNac', 'obsSinInt', 'obsSinNac', 'obsAutor', 'obsEditor', 'obsWeb'];



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