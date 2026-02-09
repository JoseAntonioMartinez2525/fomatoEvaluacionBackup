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
// --- BLOQUES BASE ---
$bloques = [
    'ComOrgInt',
    'ComOrgNac',
    'ComOrgReg',
    'ComApoyoInt',
    'ComApoyoNac',
    'ComApoyoReg',
    'CicloComOrgInt',
    'CicloComOrgNac',
    'CicloComOrgReg',
    'CicloComApoyoInt',
    'CicloComApoyoNac',
    'CicloComApoyoReg',
];

$docenteConfig = $docenteConfig ?? [
    'formKey' => 'form3_18',

    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'docentesEndpoint'    => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data318',

    'dictCollectionKey'   => 'form3_18',
    'userTypeForDict'     => '',

    // ---- MAPEOS CUANDO SE SELECCIONA UN DOCENTE ----
    'docenteMappings' => collect($bloques)->flatMap(fn($b) => [
        "cant{$b}"     => "cant{$b}",
        "subtotal{$b}" => "subtotal{$b}",
    ])->merge(['score3_18' => 'score3_18'])->toArray(),

    // ---- MAPEOS DE DATOS DESDE DICTAMINADORES ----
    'dictMappings' => collect($bloques)->flatMap(fn($b) => [
        "cant{$b}"         => "cant{$b}",
        "subtotal{$b}"     => "subtotal{$b}",
        "comision{$b}"     => "comision{$b}",
        "obs{$b}"          => "obs{$b}",
    ])->merge([
        'score3_18'     => 'score3_18',
        'comision3_18'  => 'comision3_18',
        '.comision3_18' => 'comision3_18',
        '#comision3_18' => 'comision3_18',
    ])->toArray(),

    // ---- INPUTS OCULTOS DESDE docenteData.form3_18 ----
    'fillHiddenFrom' => [
        'user_id'   => 'user_id',
        'email'     => 'email',
        'user_type' => 'user_type',
    ],

    // ---- INPUTS OCULTOS DESDE dictaminador ----
    'fillHiddenFromDict' => [
        'dictaminador_id' => 'dictaminador_id',
        'user_id'         => 'user_id',
        'email'           => 'email',
        'user_type'       => 'user_type',
    ],

    // ---- RESET AUTOMÁTICO ----
    'resetOnNotFound' => false,
    'resetValues' => collect($bloques)->flatMap(fn($b) => [
        "cant{$b}"       => '0',
        "subtotal{$b}"   => '0',
        "comision{$b}"   => '0',
        "obs{$b}"        => '',
    ])->merge([
        'score3_18'     => '0',
        '#comision3_18' => '0',
    ])->toArray(),

        'convocatoriaSelectors' => [
        '#convocatoria',
        '#convocatoria2',
    ],
];

// ---- CONFIGURACIÓN DEL FORM ----
if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => collect($bloques)->flatMap(fn($b) => [
            "cant{$b}",
            "subtotal{$b}",
            "comision{$b}",
            "obs{$b}",
        ])->merge([
            'comision3_18',
            'score3_18',
        ])->toArray(),

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
        #piedepagina {
            display: none;
        }

        td {
            font-size: 1rem;
        }

        @media print {
            #piedepagina {
                display: block !important;
            }

            footer {
                position: absolute;
                /* Usar absolute en lugar de fixed */
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
                page-break-inside: avoid;
                /* Evitar saltos dentro del pie de página */
            }

            div {
                page-break-after: avoid;
                page-break-before: avoid;
            }


            @page {
                size: landscape;
                margin: 20mm;
                /* Ajusta según sea necesario */

            }


            .page-number:after {
                content: "Página " counter(page);
            }

            #convocatoria,
            #convocatoria2,
            #piedepagina1,
            #piedepagina2 {
                margin: 0;
                font-size: 1rem;
            }


            .page-number:before {
                content: "Página " counter(page) " de 34";
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
                font-weight: normal !important;
                /* Opcional, si quieres menos énfasis */
                color: #000;
            }

            .dictaminador-style {
                font-weight: normal;
                font-size: 16px;
                margin-top: 10px;
                text-align: center;
            }

            .dictaminador-style #piedepagina2 {
                display: flex;
                justify-content: flex-end;
                margin-top: 10px;
                font-weight: normal !important;
            }

            /* Estilo para secretaria o userType vacío */
            .secretaria-style #piedepagina2 {
                display: flex;
                justify-content: flex-end;
                margin-top: 30px;
                font-weight: normal !important;
                display: inline-block;
            }

            /* Mostrar el footer correcto según la página */
            .page-break[data-page="26"] .first-page-footer {
                display: table-footer-group !important;
            }

            .page-break[data-page="27"] .second-page-footer {
                display: table-footer-group !important;
            }

            .page-number:before {
                content: "Página " counter(page) " de 34";
            }



        }

        .f3_18_table_margin_top{
            margin-top: 10rem;
        }



        button#edit-btn-form3_18{
            margin-left:67rem;
        }

        button#btn3_18{
            margin-left:60rem;
        }

        body.dark-mode input#obsComOrgInt, body.dark-mode input#obsComOrgNac, body.dark-mode input#obsComOrgReg, body.dark-mode input#obsComApoyoInt, body.dark-mode input#obsComApoyoNac, 
        body.dark-mode input#obsComApoyoReg, body.dark-mode input#obsCicloComOrgInt, body.dark-mode input#obsCicloComOrgNac, body.dark-mode input#obsCicloComOrgReg, body.dark-mode input#obsCicloComApoyoInt,
         body.dark-mode input#obsCicloComApoyoNac, body.dark-mode input#obsCicloComApoyoReg{
        color: white!important;
        font-size: .8rem;
        font-weight: bold;
        

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
$checkFields = ['comision3_18'];
foreach($checkFields as $f) {
    if (!empty($docenteConfig[$f] ?? null)) {
        $hasData = true;
        break;
    }
}
$formId = $docenteConfigForm['formId'] ?? 'form3_18';
$formNumber = '318';
    @endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo
        Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
                {{-- Buscar Docentes: --}}
                <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_18 -->
        <form id="form3_18" action="/formato-evaluacion/store-form318" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <!--3.18 Organización de congresos o eventos institucionales del área de conocimiento de la o el Docente-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">40</label>
            </h4>
            <table class="table table-sm tutorias">
                <x-sub-headers-form3_18 :componentIndex="0" />
                <tbody data-page="26">
                    <tr>
                        <td>a)</td>
                        <td>Comité organizador</td>
                        <td></td>
                        <td>Internacional**</td>
                        <td id="puntajeComOrgInt"><b>40</b></td>
                        <td id="cantComOrgInt" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalComOrgInt"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionComOrgInt" name="comisionComOrgInt"
                                    value="{{ oldValueOrDefault('comisionComOrgInt') }}" oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionComOrgInt" name="comisionComOrgInt" class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsComOrgInt" id="obsComOrgInt">
                            @else
                                <span id="obsComOrgInt" name="obsComOrgInt" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>Comité organizador</td>
                        <td></td>
                        <td>Nacional</td>
                        <td id="puntajeComOrgNac"><b>20</b></td>
                        <td id="cantComOrgNac" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalComOrgNac"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionComOrgNac" name="comisionComOrgNac"
                                    value="{{ oldValueOrDefault('comisionComOrgNac') }}" oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionComOrgNac" name="comisionComOrgNac" class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsComOrgNac" id="obsComOrgNac">
                            @else
                                <span id="obsComOrgNac" name="obsComOrgNac" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td>Comité organizador</td>
                        <td></td>
                        <td>Regional</td>
                        <td id="puntajeComOrgReg"><b>10</b></td>
                        <td id="cantComOrgReg" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalComOrgReg"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionComOrgReg" name="comisionComOrgReg"
                                    value="{{ oldValueOrDefault('comisionComOrgReg') }}" oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionComOrgReg" name="comisionComOrgReg" class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsComOrgReg" id="obsComOrgReg">
                            @else
                                <span id="obsComOrgReg" name="obsComOrgReg" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>d)</td>
                        <td>Comisiones de Apoyo</td>
                        <td></td>
                        <td>Internacional</td>
                        <td id="puntajeComApoyoInt"><b>40</b></td>
                        <td id="cantComApoyoInt" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalComApoyoInt"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionComApoyoInt" name="comisionComApoyoInt"
                                    value="{{ oldValueOrDefault('comisionComApoyoInt') }}" oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionComApoyoInt" name="comisionComApoyoInt" class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsComApoyoInt" id="obsComApoyoInt">
                            @else
                                <span id="obsComApoyoInt" name="obsComApoyoInt" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>e)</td>
                        <td>Comisiones de Apoyo</td>
                        <td></td>
                        <td>Nacional</td>
                        <td id="puntajeComApoyoNac"><b>20</b></td>
                        <td id="cantComApoyoNac" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalComApoyoNac"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionComApoyoNac" name="comisionComApoyoNac"
                                    value="{{ oldValueOrDefault('comisionComApoyoNac') }}" oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionComApoyoNac" name="comisionComApoyoNac" class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsComApoyoNac" id="obsComApoyoNac">
                            @else
                                <span id="obsComApoyoNac" name="obsComApoyoNac" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>f)</td>
                        <td>Comisiones de Apoyo</td>
                        <td></td>
                        <td>Regional</td>
                        <td id="puntajeComApoyoReg"><b>10</b></td>
                        <td id="cantComApoyoReg" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalComApoyoReg"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionComApoyoReg" name="comisionComApoyoReg"
                                    value="{{ oldValueOrDefault('comisionComApoyoReg') }}" oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionComApoyoReg" name="comisionComApoyoReg" class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsComApoyoReg" id="obsComApoyoReg">
                            @else
                                <span id="obsComApoyoReg" name="obsComApoyoReg" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; justify-content: space-between;padding-top: 80px;margin-top: 10rem;">
                <div>
                    <div id="convocatoria" class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
                        @if(isset($convocatoria))
                            @if($userType == 'dictaminador')
                                <div style="margin-right: -700px;"><span style="font-size: 1.5em;">Convocatoria: {{ $convocatoria }}</span></div>
                            @elseif($userType == 'secretaria')
                                <div style="margin-right: 60px; margin-left: 100px; padding-right: 12px; text-align:left;"><span style="font-size: 1.5em;">Convocatoria: {{ $convocatoria }}</span></div>
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
                </div>
                <div id="piedepagina1"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">
                    Página 26 de 34
                </div>
            </div><br>

            <!--Siguiente tabla-->
            <table class="table table-sm tutorias f3_18_table_margin_top">
                <x-sub-headers-form3_18 :componentIndex="1" />
                <tbody data-page="27">
                    <tr>
                        <td>g)</td>
                        <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                        <td>Comité organizador</td>
                        <td>Internacional</td>
                        <td id="puntajeCicloComOrgInt"><b>20</b></td>
                        <td id="cantCicloComOrgInt" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalCicloComOrgInt"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionCicloComOrgInt" name="comisionCicloComOrgInt"
                                    value="{{ oldValueOrDefault('comisionCicloComOrgInt') }}"
                                    oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionCicloComOrgInt" name="comisionCicloComOrgInt"
                                    class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsCicloComOrgInt" id="obsCicloComOrgInt">
                            @else
                                <span id="obsCicloComOrgInt" name="obsCicloComOrgInt" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>h)</td>
                        <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                        <td>Comité organizador</td>
                        <td>Nacional</td>
                        <td id="puntajeCicloComOrgNac"><b>15</b></td>
                        <td id="cantCicloComOrgNac" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalCicloComOrgNac"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionCicloComOrgNac" name="comisionCicloComOrgNac"
                                    value="{{ oldValueOrDefault('comisionCicloComOrgNac') }}"
                                    oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionCicloComOrgNac" name="comisionCicloComOrgNac"
                                    class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsCicloComOrgNac" id="obsCicloComOrgNac">
                            @else
                                <span id="obsCicloComOrgNac" name="obsCicloComOrgNac" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>i)</td>
                        <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                        <td>Comité organizador</td>
                        <td>Regional/Institucional</td>
                        <td id="puntajeCicloComOrgReg"><b>10</b></td>
                        <td id="cantCicloComOrgReg" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalCicloComOrgReg"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionCicloComOrgReg" name="comisionCicloComOrgReg"
                                    value="{{ oldValueOrDefault('comisionCicloComOrgReg') }}"
                                    oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionCicloComOrgReg" name="comisionCicloComOrgReg"
                                    class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsCicloComOrgReg" id="obsCicloComOrgReg">
                            @else
                                <span id="obsCicloComOrgReg" name="obsCicloComOrgReg" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>j)</td>
                        <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                        <td>Comisiones de apoyo</td>
                        <td>Internacional</td>
                        <td id="puntajeCicloComApoyoInt"><b>20</b></td>
                        <td id="cantCicloComApoyoInt" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalCicloComApoyoInt"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionCicloComApoyoInt"
                                    name="comisionCicloComApoyoInt"
                                    value="{{ oldValueOrDefault('comisionCicloComApoyoInt') }}"
                                    oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionCicloComApoyoInt" name="comisionCicloComApoyoInt"
                                    class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsCicloComApoyoInt" id="obsCicloComApoyoInt">
                            @else
                                <span id="obsCicloComApoyoInt" name="obsCicloComApoyoInt" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>k)</td>
                        <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                        <td>Comisiones de apoyo</td>
                        <td>Nacional</td>
                        <td id="puntajeCicloComApoyoNac"><b>15</b></td>
                        <td id="cantCicloComApoyoNac" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalCicloComApoyoNac"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionCicloComApoyoNac"
                                    name="comisionCicloComApoyoNac"
                                    value="{{ oldValueOrDefault('comisionCicloComApoyoNac') }}"
                                    oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionCicloComApoyoNac" name="comisionCicloComApoyoNac"
                                    class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsCicloComApoyoNac" id="obsCicloComApoyoNac">
                            @else
                                <span id="obsCicloComApoyoNac" name="obsCicloComApoyoNac" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>l)</td>
                        <td><textarea name="" class="textAreaForms" cols="30" rows="10">Ciclo de conferencias, simposio, coloquio, etc.</textarea></td>
                        <td>Comisiones de apoyo</td>
                        <td>Regional/Institucional</td>
                        <td id="puntajeCicloComApoyoReg"><b>10</b></td>
                        <td id="cantCicloComApoyoReg" class="cantidad_form_3_18"></td>
                        <td></td>
                        <td id="subtotalCicloComApoyoReg"></td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionCicloComApoyoReg"
                                    name="comisionCicloComApoyoReg"
                                    value="{{ oldValueOrDefault('comisionCicloComApoyoReg') }}"
                                    oninput="onActv3Comision3_18()">
                            @else
                                <span id="comisionCicloComApoyoReg" name="comisionCicloComApoyoReg"
                                    class="form3_18_dark"></span>
                            @endif
                        </td>
                        <td class="td_obs">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" name="obsCicloComApoyoReg" id="obsCicloComApoyoReg">
                            @else
                                <span id="obsCicloComApoyoReg" name="obsCicloComApoyoReg" class="form3_18_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="acreditacion" scope="col" colspan=2> **Coparticipación técnica y/o
                            académica y/o
                            financiera
                            de institución extranjera</th>
                        <th class="acreditacion" style="padding-left: 100px;">Acreditacion:</th>
                        <th class="descripcion"><b>Instancia que lo otorga</b></th>

                    </tr>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.18-->
            {{-- <table>
                <thead>
                    
                </thead>
            </table> --}}
                @if($userType != 'docente')
                <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                @endif
                {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                <button type="submit" class="btn custom-btn printButtonClass" id="btn3_18">Enviar</button>
                @endif
            <!--Convocatoria 2-->
            <div style="display: flex; justify-content: space-between;padding-top: 150px;">
                <div>
                    <div id="convocatoria2" class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
                        @if(isset($convocatoria))
                            @if($userType == 'dictaminador')
                                <div style="margin-right: -700px;"><span style="font-size: 1.5em;">Convocatoria: {{ $convocatoria }}</span></div>
                            @elseif($userType == 'secretaria')
                                <div style="margin-right: 60px; margin-left: 100px; padding-right: 12px; text-align:left;"><span style="font-size: 1.5em;">Convocatoria: {{ $convocatoria }}</span></div>
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
                </div>

                <div id="piedepagina2"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === 'secretaria' ? 'secretaria-style' : '') }}">
                    Página 27 de 34
                </div>

            </div>
            {{-- <div>
                @if ($userType != 'secretaria')
                <button id="btn3_18" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                @endif
            </div> --}}
        </form>
    </main>

    <script>
        
        window.onload = function () {
            const footer = document.querySelector('footer');
            if (footer) {
                const footerHeight = footer.offsetHeight;
                const elements = document.querySelectorAll('.prevent-overlap');

                elements.forEach(element => {
                    const rect = element.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;

                    // Verifica si el elemento está demasiado cerca del footer y aplica page-break-before si es necesario
                    if (rect.bottom + footerHeight > viewportHeight) {
                        element.style.pageBreakBefore = "always"; // Forzar salto antes
                    }
                });
            }

        };
        let cant3_18 = ['cantComOrgInt', 'cantComOrgNac', 'cantComOrgReg', 'cantComApoyoInt', 'cantComApoyoNac', 'cantComApoyoReg', 'cantCicloComOrgInt', 'cantCicloComOrgNac', 'cantCicloComOrgReg', 'cantCicloComApoyoInt', 'cantCicloComApoyoNac', 'cantCicloComApoyoReg'];
        let subtotal3_18 = ['subtotalComOrgInt', 'subtotalComOrgNac', 'subtotalComOrgReg', 'subtotalComApoyoInt', 'subtotalComApoyoNac', 'subtotalComApoyoReg', 'subtotalCicloComOrgInt', 'subtotalCicloComOrgNac', 'subtotalCicloComOrgReg', 'subtotalCicloComApoyoInt', 'subtotalCicloComApoyoNac', 'subtotalCicloComApoyoReg'];
        let comision3_18 = ['comisionComOrgInt', 'comisionComOrgNac', 'comisionComOrgReg', 'comisionComApoyoInt', 'comisionComApoyoNac', 'comisionComApoyoReg', 'comisionCicloComOrgInt', 'comisionCicloComOrgNac', 'comisionCicloComOrgReg', 'comisionCicloComApoyoInt', 'comisionCicloComApoyoNac', 'comisionCicloComApoyoReg'];
        let obs3_18 = ['obsComOrgInt', 'obsComOrgNac', 'obsComOrgReg', 'obsComApoyoInt', 'obsComApoyoNac', 'obsComApoyoReg', 'obsCicloComOrgInt', 'obsCicloComOrgNac', 'obsCicloComOrgReg', 'obsCicloComApoyoInt', 'obsCicloComApoyoNac', 'obsCicloComApoyoReg'];


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