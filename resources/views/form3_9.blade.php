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
//mapping del partials docente-autocomplete.blade.php
$docenteMappings = [
    'score3_9' => 'score3_9',
    
];

$dictMappings = [
    'score3_9' => 'score3_9',
    'comision3_9' => 'comision3_9',
];

// Copias de score
foreach (range(0, 2) as $i) {
    $docenteMappings["#score3_9_$i"] = 'score3_9';
    $dictMappings["#comision3_9_$i"] = 'comision3_9';
}

// Observaciones
foreach (range(1, 18) as $i) {
    
    $dictMappings["#obs3_9_$i"] = "obs3_9_$i";
}

// Puntajes
foreach (range(1, 18) as $i) {
    $docenteMappings["puntaje3_9_$i"] = "puntaje3_9_$i";
    $dictMappings["puntaje3_9_$i"] = "puntaje3_9_$i";
}

// Tutorías
foreach (range(1, 18) as $i) {
    $docenteMappings["tutorias$i"] = "tutorias$i";
    $dictMappings["tutorias$i"] = "tutorias$i";
    $dictMappings["tutoriasComision$i"] = "tutorias$i";
}

$docenteConfig =  $docenteConfig ?? [
    'formKey' => 'form3_9',
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data39',
    'dictCollectionKey' => 'form3_9',
    'userTypeForDict' => '',
    'docenteMappings' => $docenteMappings,
    'dictMappings' => $dictMappings,

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

    'resetOnNotFound' => false,
    'resetValues' => array_merge(
        ['score3_9' => '0', 'comision3_9' => '0'],
        array_reduce(range(1, 17), function($acc, $i) {
            $acc["#obs3_9_$i"] = '';
            return $acc;
        }, [])
    ),
];

//mapping del partials submit-form.blade.php
if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'score3_9',
            // cantidades y subtotales
            'puntaje3_9_1',
            'puntaje3_9_2',
            'puntaje3_9_3',
            'puntaje3_9_4',
            'puntaje3_9_5',
            'puntaje3_9_6',
            'puntaje3_9_7',
            'puntaje3_9_8',
            'puntaje3_9_9',
            'puntaje3_9_10',
            'puntaje3_9_11',
            'puntaje3_9_12',
            'puntaje3_9_13',
            'puntaje3_9_14',
            'puntaje3_9_15',
            'puntaje3_9_16',
            'puntaje3_9_17',

            'tutorias1',
            'tutorias2',
            'tutorias3',
            'tutorias4',
            'tutorias5',
            'tutorias6',
            'tutorias7',
            'tutorias8',
            'tutorias9',
            'tutorias10',
            'tutorias11',
            'tutorias12',
            'tutorias13',
            'tutorias14',
            'tutorias15',
            'tutorias16',
            'tutorias17',

            'comision3_9',
            'tutoriasComision1',
            'tutoriasComision2',
            'tutoriasComision3',
            'tutoriasComision4',
            'tutoriasComision5',
            'tutoriasComision6',
            'tutoriasComision7',
            'tutoriasComision8',
            'tutoriasComision9',
            'tutoriasComision10',
            'tutoriasComision11',
            'tutoriasComision12',
            'tutoriasComision13',
            'tutoriasComision14',
            'tutoriasComision15',
            'tutoriasComision16',
            'tutoriasComision17',

            // observaciones (sin #)
            'obs3_9_1',
            'obs3_9_2',
            'obs3_9_3',
            'obs3_9_4',
            'obs3_9_5',
            'obs3_9_6',
            'obs3_9_7',
            'obs3_9_8',
            'obs3_9_9',
            'obs3_9_10',
            'obs3_9_11',
            'obs3_9_12',
            'obs3_9_13',
            'obs3_9_14',
            'obs3_9_15',
            'obs3_9_16',
            'obs3_9_17',
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

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
    #piedepagina, #convocatoria {
        visibility: visible !important;
        display: block !important;
    }

        /* Mostrar el footer correcto según la página */
    .page-break[data-page="14"] .first-page-footer {
        display: table-footer-group !important;
    }

    .page-break[data-page="15"] .second-page-footer {
        display: table-footer-group !important;
    }

    .page-number:before {
        content: "Página " counter(page) " de 34";
    }
            
        }

        .table2{
    margin-top: 300px;

    @media print {
    

.prevent-overlap {
    page-break-before: always;
    page-break-inside: avoid; 
}

    #convocatoria, #convocatoria2, #piedepagina1, #piedepagina2 {
        margin: 0;
        font-size: .7rem;
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

.secretaria-style #piedepagina1 {
    display: flex;
    justify-content: flex-end;
    font-weight: normal; /* Opcional, si quieres menos énfasis */
    color: #000;
}

.dictaminador-style {
    font-weight: bold;
    font-size: 12px;
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

    td{
        font-size: 12px;
    }

}
}


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

  .nav-max-content{

      color: white;
      width: max-content;

  }

  td{
    font-size: 1rem;
  }

  button#btn3_9Button{
    margin-left: 60rem;
  }
    </style>
</head>

<body class="bg-gray-50 text-black/50">

    <div class="relative min-h-screen flex flex-col items-center justify-center">
        @if (Route::has('login'))
            @if (Auth::check())
                <x-nav-menu :user="Auth::user()" navClass="nav-max-content" />
            @endif
        @endif
    </div>
    <x-general-header />
@php
$user = Auth::user();
$userType = $user->user_type;
$user_identity = $user->id; 

$hasData = false;
$checkFields = ['comision3_9'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_9';
$formNumber = '39';
@endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass dark-mode-button"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
            <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_9 -->
        <form id="form3_9" action="/formato-evaluacion/store-form39" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
             <div>
                <!--3.9 Trabajos dirigidos para la titulación de estudiantes-->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">200</label>
                    <h3 style="margin-left:400px;">Tutorias</h3>
                </h4>
            </div>
            <table class="table table-sm tutorias mb-3">
            <x-sub-headers-form3_9 :componentIndex="0" />
                <tbody data-page="14">
                    <tr>
                        <td>a)</td>
                        <td>Revisión de</td>
                        <td>Tesis</td>
                        <td>Doctorado</td>
                        <td id="puntajeTutorias20_1">20</td>
                        <td id="puntaje3_9_1" name="puntaje3_9_1">0</td>
                        <td id="tutorias1">0</td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision1" name="tutoriasComision1" 
                                oninput="onActv3Comision3_9()" value="{{ oldValueOrDefault('tutoriasComision1') }}">    
                        @else
                            <span id="tutoriasComision1" name="tutoriasComision1"></span>
                        @endif
                        </td>
                        <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_1" name="obs3_9_1" type="text">
                        @else
                            <span id="obs3_9_1" name="obs3_9_1"></span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>Proyecto de</td>
                        <td>Tesis</td>
                        <td>Maestría</td>
                        <td id="puntajeTutorias15_1">15</td>
                        <td id="puntaje3_9_2" name="puntaje3_9_2">0</td>
                        <td id="tutorias2">0</td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision2" name="tutoriasComision2" oninput="onActv3Comision3_9()" value="{{ oldValueOrDefault('tutoriasComision2') }}">
                            @else
                                <span id="tutoriasComision2" name="tutoriasComision2"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_2" name="obs3_9_2" type="text">
                            @else
                                <span id="obs3_9_2" name="obs3_9_2"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td>Proyecto de</td>
                        <td>Tesis y otras</td>
                        <td>TSU, Lic y especialidad</td>
                        <td id="puntajeTutorias10_1">10</td>
                        <td id="puntaje3_9_3" name="puntaje3_9_3">0</td>
                        <td id="tutorias3">0</td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision3" name="tutoriasComision3" value="{{ oldValueOrDefault('tutoriasComision3') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision3" name="tutoriasComision3"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_3" name="obs3_9_3" type="text">
                            @else
                                <span id="obs3_9_3" name="obs3_9_3"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>d)</td>
                        <td>Dirección trabajo en realización</td>
                        <td>Tesis</td>
                        <td>Doctorado</td>
                        <td id="puntajeTutorias55">55</td>
                        <td id="puntaje3_9_4" name="puntaje3_9_4">0</td>
                        <td id="tutorias4">0</td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision4" name="tutoriasComision4" value="{{ oldValueOrDefault('tutoriasComision4') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision4" name="tutoriasComision4"></span>
                            @endif
                        </td>
                        <td class="td_obs"> 
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_4" name="obs3_9_4" type="text">
                            @else
                                <span id="obs3_9_4" name="obs3_9_4"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>e)</td>
                        <td>Dirección trabajo en realización</td>
                        <td>Tesis</td>
                        <td>Maestría</td>
                        <td id="puntajeTutorias45">45</td>
                        <td id="puntaje3_9_5" name="puntaje3_9_5">0</td>
                        <td id="tutorias5">0</td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision5" name="tutoriasComision5" value="{{ oldValueOrDefault('tutoriasComision5') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision5" name="tutoriasComision5"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_5" name="obs3_9_5" type="text">
                            @else
                                <span id="obs3_9_5" name="obs3_9_5"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>f)</td>
                        <td>Dirección trabajo en realización</td>
                        <td>Tesis y otras</td>
                        <td>TSU, Lic y especialidad</td>
                        <td id="puntajeTutorias35">35</td>
                        <td id="puntaje3_9_6" name="puntaje3_9_6">0</td>
                        <td id="tutorias6">0</td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision6" name="tutoriasComision6" value="{{ oldValueOrDefault('tutoriasComision6') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision6" name="tutoriasComision6"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_6" name="obs3_9_6" type="text">
                            @else
                                <span id="obs3_9_6" name="obs3_9_6"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>g)</td>
                        <td>Dirección trabajo terminado</td>
                        <td>Tesis</td>
                        <td>Doctorado</td>
                        <td id="puntajeTutorias70">70</td>
                        <td id="puntaje3_9_7" name="puntaje3_9_7">0</td>
                        <td id="tutorias7">0</td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision7" name="tutoriasComision7" value="{{ oldValueOrDefault('tutoriasComision7') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision7" name="tutoriasComision7"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_7" name="obs3_9_7" type="text">
                            @else
                                <span id="obs3_9_7" name="obs3_9_7"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; justify-content: space-between; padding-top: 50px;">
                <div>
                    <div id="convocatoria" class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
                        @if(isset($convocatoria))
                            @if($userType == 'dictaminador')
                                <div style="margin-right: -700px;"><span style="font-size: 1em;">{{ $convocatoria }}</span></div>
                            @elseif($userType =='controlador')
                                <div style="margin-right: 60px; margin-left: 100px; padding-right: 12px; text-align:left;"><span style="font-size: 1em;">{{ $convocatoria }}</span></div>
                            @else
                                <span>{{ $convocatoria }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
                        @if(isset($periodo))
                            @if($userType == 'dictaminador' || $userType =='controlador')
                                <div><span style="font-size: 1em;"></span> {{ $periodo }}</div>
                            @else
                                <span style="margin-left: 50px;">{{ $periodo }}</span>
                            @endif
                        @endif
                    </div>
                </div>
                <div id="piedepagina1" class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType ==='controlador' ? 'secretaria-style' : '') }}">
                    Página 14 de 34
                </div>
            </div>
            <!---->
            <table class="table table-sm tutorias table2">
            <x-sub-headers-form3_9 :componentIndex="1" />
                <tbody data-page="15">
                    <tr>
                        <td>h)</td>
                        <td>Dirección trabajo terminado</td>
                        <td>Tesis</td>
                        <td>Maestría</td>
                        <td id="puntajeTutorias60">60</td>
                        <td id="puntaje3_9_8" name="puntaje3_9_8">0</td>
                        <td id="tutorias8">0</td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision8" name="tutoriasComision8"
                                    value="{{ oldValueOrDefault('tutoriasComision8') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision8" name="tutoriasComision8"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_8" name="obs3_9_8" type="text">
                            @else
                                <span id="obs3_9_8" name="obs3_9_8"></span>
                            @endif
                        </td>
                    </tr>
                <tr>
                    <td>i)</td>
                        <td>Dirección trabajo terminado</td>
                        <td>Tesis y otras</td>
                        <td>TSU, Lic y especialidad</td>
                        <td id="puntajeTutorias50">50</td>
                        <td id="puntaje3_9_9" name="puntaje3_9_9">0</td>
                        <td id="tutorias9">0</td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision9" name="tutoriasComision9" value="{{ oldValueOrDefault('tutoriasComision9') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision9" name="tutoriasComision9"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_9" name="obs3_9_9" type="text">
                            @else
                                <span id="obs3_9_9" name="obs3_9_9"></span>
                            @endif
                        </td>
                    </tr>
                <tr>
                    <td>j)</td>
                    <td>Revisión de trabajo terminado</td>
                    <td>Tesis</td>
                    <td>Doctorado</td>
                    <td id="puntajeTutorias30_1">30</td>
                    <td id="puntaje3_9_10" name="puntaje3_9_10">0</td>
                    <td id="tutorias10">0</td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision10" name="tutoriasComision10" value="{{ oldValueOrDefault('tutoriasComision10') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision10" name="tutoriasComision10"></span>
                        @endif
                    </td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_10" name="obs3_9_10" type="text">
                        @else
                            <span id="obs3_9_10" name="obs3_9_10"></span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>k)</td>
                    <td>Revisión de trabajo terminado</td>
                    <td>Tesis</td>
                    <td>Maestría</td>
                    <td id="puntajeTutorias20_2">50</td>
                    <td id="puntaje3_9_11" name="puntaje3_9_11">0</td>
                    <td id="tutorias11">0</td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision11" name="tutoriasComision11" value="{{ oldValueOrDefault('tutoriasComision11') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision11" name="tutoriasComision11"></span>
                        @endif
                    </td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_11" name="obs3_9_11" type="text">
                        @else
                            <span id="obs3_9_11" name="obs3_9_11"></span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>l)</td>
                    <td>Revisión de trabajo terminado</td>
                    <td>Tesis y otras</td>
                    <td>TSU, Lic y especialidad</td>
                    <td id="puntajeTutorias15_2">15</td>
                    <td id="puntaje3_9_12" name="puntaje3_9_12">0</td>
                    <td id="tutorias12">0</td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision12" name="tutoriasComision12" value="{{ oldValueOrDefault('tutoriasComision12') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision12" name="tutoriasComision12"></span>
                        @endif
                    </td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_12" name="obs3_9_12" type="text">
                        @else
                            <span id="obs3_9_12" name="obs3_9_12"></span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>m)</td>
                    <td>Sinodalía</td>
                    <td>Examen</td>
                    <td>Doctorado</td>
                    <td id="puntajeTutorias30_2">30</td>
                    <td id="puntaje3_9_13" name="puntaje3_9_13">0</td>
                    <td id="tutorias13">0</td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision13" name="tutoriasComision13" value="{{ oldValueOrDefault('tutoriasComision13') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision13" name="tutoriasComision13"></span>
                        @endif
                    </td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_13" name="obs3_9_13" type="text">
                        @else
                            <span id="obs3_9_13" name="obs3_9_13"></span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>n)</td>
                    <td>Sinodalía</td>
                    <td>Examen</td>
                    <td>Maestría</td>
                    <td id="puntajeTutorias20_3">15</td>
                    <td id="puntaje3_9_14" name="puntaje3_9_14">0</td>
                    <td id="tutorias14">0</td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision14" name="tutoriasComision14" value="{{ oldValueOrDefault('tutoriasComision14') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision14" name="tutoriasComision14"></span>
                        @endif
                    </td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_14" name="obs3_9_14" type="text">
                        @else
                            <span id="obs3_9_14" name="obs3_9_14"></span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>o)</td>
                    <td>Sinodalía</td>
                    <td>Examen</td>
                    <td>TSU, Lic y especialidad</td>
                    <td id="puntajeTutorias15_3">15</td>
                    <td id="puntaje3_9_15" name="puntaje3_9_15">0</td>
                    <td id="tutorias15">0</td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision15" name="tutoriasComision15" value="{{ oldValueOrDefault('tutoriasComision15') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision15" name="tutoriasComision15"></span>
                        @endif
                    </td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_15" name="obs3_9_15" type="text">
                        @else
                            <span id="obs3_9_15" name="obs3_9_15"></span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>p)</td>
                    <td>Distinciones</td>
                    <td></td>
                    <td>Doctorado</td>
                    <td id="puntajeTutorias15_4">15</td>
                    <td id="puntaje3_9_16" name="puntaje3_9_16">0</td>
                    <td id="tutorias16">0</td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision16" name="tutoriasComision16" value="{{ oldValueOrDefault('tutoriasComision16') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision16" name="tutoriasComision16"></span>
                        @endif
                    </td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_16" name="obs3_9_16" type="text">
                        @else
                            <span id="obs3_9_16" name="obs3_9_16"></span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>q)</td>
                    <td>Distinciones</td>
                    <td></td>
                    <td>Maestría</td>
                    <td id="puntajeTutorias10_2">10</td>
                    <td id="puntaje3_9_17" name="puntaje3_9_17">0</td>
                    <td id="tutorias17">0</td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision17" name="tutoriasComision17" value="{{ oldValueOrDefault('tutoriasComision17') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision17" name="tutoriasComision17"></span>
                        @endif
                    </td>
                    <td class="td_obs">
                        @if ($userType == 'dictaminador')
                            <input class="table-header" id="obs3_9_17" name="obs3_9_17" type="text">
                        @else
                            <span id="obs3_9_17" name="obs3_9_17"></span>
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.9-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>

                        <th class="descripcion"><b>DSE para pregrado, DIIP para posgrado</b>
                        </th>
                        {{-- Lógica de botones --}}
                        @if($userType != 'docente')
                        <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                        @endif
                        {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                        @if(!$hasData && $userType !='controlador' && $userType != 'docente')
                        <button type="submit" class="btn custom-btn printButtonClass" id="btn3_9Button">Enviar</button>
                        @endif
                    </tr>
                </thead>
            </table>
    <div style="display: flex; justify-content: space-between; padding-top: 200px;">
        <div>
            <div id="convocatoria2" class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
                @if(isset($convocatoria))
                    @if($userType == 'dictaminador')
                        <div style="margin-right: -700px;"><span style="font-size: 1.5em;">{{ $convocatoria }}</span></div>
                    @elseif($userType =='controlador')
                        <div style="margin-right: 60px; margin-left: 100px; padding-right: 12px; text-align:left;"><span style="font-size: 1.5em;">{{ $convocatoria }}</span></div>
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
        </div>
        <div id="piedepagina2" class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType ==='controlador' ? 'secretaria-style' : '') }}">
            Página 15 de 34
        </div>
    </div>
        </form>
    </main>

<script>
    
    window.onload = function () {
        // const footerHeight = document.querySelector('footer').offsetHeight;
        const elements = document.querySelectorAll('.prevent-overlap');

        // if(footerHeight){
            elements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                // Verifica si el elemento está demasiado cerca del footer
                if (rect.bottom > viewportHeight - footerHeight) {
                    element.style.pageBreakBefore = "always";
                }
            });
        

        // Múltiples eventos para mayor compatibilidad
        // window.addEventListener('beforeprint', updatePageNumberOnPrint);
        // window.matchMedia('print').addListener(updatePageNumberOnPrint);
        }
    // };

    function minWithSum(value1, value2) {
        const sum = value1 + value2;
        return Math.min(sum, 200);


    }

    window.addEventListener('afterprint', function () {
        // Opcional: Restaurar estado original
        document.querySelector('footer').style.display = 'none';
    });
    
    document.addEventListener('DOMContentLoaded', function () {

        toggleDarkMode();
    });   
</script>

    @include('partials.docente-autocomplete', ['config' => $docenteConfig])
    @include('partials.submit-form', ['config' => $docenteConfigForm])


</body>

</html>