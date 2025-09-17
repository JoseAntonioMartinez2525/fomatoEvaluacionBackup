@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
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
        content: "Página " counter(page) " de 33";
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
  content: "Página " counter(page) " de 33";
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


}

  .nav-max-content{

      color: white;
      width: max-content;

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
@endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if($userType !== 'docente')
            <!-- Select para dictaminador seleccionando docentes -->
            <label for="docenteSelect">Seleccionar Docente:</label>
            <select id="docenteSelect" class="form-select"> <!--name="docentes[]" multiple-->
                <option value="">Seleccionar un docente</option>
                <!-- Aquí se llenarán los docentes con JavaScript -->
            </select>
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_1 -->
        <form id="form3_9" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-form39', 'form3_9');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
             <div>
                <!--3.9 Trabajos dirigidos para la titulación de estudiantes-->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">200</label>
                    <h3 style="margin-left:400px;">Tutorias</h3>
                </h4>
            </div>
            <table class="table table-sm tutorias">
            <x-sub-headers-form3_9 :componentIndex="0" />
                <tbody data-page="14">
                    <tr>
                        <td>a)</td>
                        <td>Revisión de</td>
                        <td>Tesis</td>
                        <td>Doctorado</td>
                        <td id="puntajeTutorias20_1">20</td>
                        <td id="puntaje3_9_1"></td>
                        <td id="tutorias1">0</td>
                        <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision1" name="tutoriasComision1" 
                                oninput="onActv3Comision3_9()" value="{{ oldValueOrDefault('tutoriasComision1') }}">    
                        @else
                            <span id="tutoriasComision1" name="tutoriasComision1"></span>
                        @endif
                        </td>
                        <td id="obs3_9_1">
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
                        <td id="puntaje3_9_2"></td>
                        <td id="tutorias2">0</td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision2" name="tutoriasComision2" oninput="onActv3Comision3_9()" value="{{ oldValueOrDefault('tutoriasComision2') }}">
                            @else
                                <span id="tutoriasComision2" name="tutoriasComision2"></span>
                            @endif
                        </td>
                        <td id="obs3_9_2">
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
                        <td id="puntaje3_9_3"></td>
                        <td id="tutorias3">0</td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision3" name="tutoriasComision3" value="{{ oldValueOrDefault('tutoriasComision3') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision3" name="tutoriasComision3"></span>
                            @endif
                        </td>
                        <td id="obs3_9_3">
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
                        <td id="puntaje3_9_4"></td>
                        <td id="tutorias4">0</td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision4" name="tutoriasComision4" value="{{ oldValueOrDefault('tutoriasComision4') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision4" name="tutoriasComision4"></span>
                            @endif
                        </td>
                        <td id="obs3_9_4"> 
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
                        <td id="puntaje3_9_5"></td>
                        <td id="tutorias5">0</td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision5" name="tutoriasComision5" value="{{ oldValueOrDefault('tutoriasComision5') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision5" name="tutoriasComision5"></span>
                            @endif
                        </td>
                        <td id="obs3_9_5">
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
                        <td id="puntaje3_9_6"></td>
                        <td id="tutorias6">0</td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision6" name="tutoriasComision6" value="{{ oldValueOrDefault('tutoriasComision6') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision6" name="tutoriasComision6"></span>
                            @endif
                        </td>
                        <td id="obs3_9_6">
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
                        <td id="puntaje3_9_7"></td>
                        <td id="tutorias7">0</td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision7" name="tutoriasComision7" value="{{ oldValueOrDefault('tutoriasComision7') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision7" name="tutoriasComision7"></span>
                            @endif
                        </td>
                        <td id="obs3_9_7">
                            @if ($userType == 'dictaminador')
                                <input class="table-header" id="obs3_9_7" name="obs3_9_7" type="text">
                            @else
                                <span id="obs3_9_7" name="obs3_9_7"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; justify-content: space-between;padding-top: 50px;">
                <div id="convocatoria">
                        <!-- Mostrar convocatoria -->
                        @if(isset($convocatoria))

                            <div style="margin-right: -500px;">
                                <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                            </div>
                        @endif
                </div>
                    <div id="piedepagina1"
                        class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === '' ? 'secretaria-style' : '') }}">
                        Página 14 de 33
                    </div>                
            </div>
    
            <table class="table table-sm tutorias table2">
            <x-sub-headers-form3_9 :componentIndex="1" />
                <tbody data-page="15">
                    <tr>
                        <td>h)</td>
                        <td>Dirección trabajo terminado</td>
                        <td>Tesis</td>
                        <td>Maestría</td>
                        <td id="puntajeTutorias60">60</td>
                        <td id="puntaje3_9_8"></td>
                        <td id="tutorias8">0</td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision8" name="tutoriasComision8"
                                    value="{{ oldValueOrDefault('tutoriasComision8') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision8" name="tutoriasComision8"></span>
                            @endif
                        </td>
                        <td id="obs3_9_8">
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
                        <td id="puntaje3_9_9"></td>
                        <td id="tutorias9">0</td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="tutoriasComision9" name="tutoriasComision9" value="{{ oldValueOrDefault('tutoriasComision9') }}" oninput="onActv3Comision3_9()">
                            @else
                                <span id="tutoriasComision9" name="tutoriasComision9"></span>
                            @endif
                        </td>
                        <td id="obs3_9_9">
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
                    <td id="puntaje3_9_10">0</td>
                    <td id="tutorias10">0</td>
                    <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision10" name="tutoriasComision10" value="{{ oldValueOrDefault('tutoriasComision10') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision10" name="tutoriasComision10"></span>
                        @endif
                    </td>
                    <td id="obs3_9_10">
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
                    <td id="puntaje3_9_11">0</td>
                    <td id="tutorias11">0</td>
                    <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision11" name="tutoriasComision11" value="{{ oldValueOrDefault('tutoriasComision11') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision11" name="tutoriasComision11"></span>
                        @endif
                    </td>
                    <td id="obs3_9_11">
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
                    <td id="puntaje3_9_12">0</td>
                    <td id="tutorias12">0</td>
                    <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision12" name="tutoriasComision12" value="{{ oldValueOrDefault('tutoriasComision12') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision12" name="tutoriasComision12"></span>
                        @endif
                    </td>
                    <td id="obs3_9_12">
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
                    <td id="puntaje3_9_13">0</td>
                    <td id="tutorias13">0</td>
                    <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision13" name="tutoriasComision13" value="{{ oldValueOrDefault('tutoriasComision13') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision13" name="tutoriasComision13"></span>
                        @endif
                    </td>
                    <td id="obs3_9_13">
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
                    <td id="puntaje3_9_14">0</td>
                    <td id="tutorias14">0</td>
                    <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision14" name="tutoriasComision14" value="{{ oldValueOrDefault('tutoriasComision14') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision14" name="tutoriasComision14"></span>
                        @endif
                    </td>
                    <td id="obs3_9_14">
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
                    <td id="puntaje3_9_15">0</td>
                    <td id="tutorias15">0</td>
                    <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision15" name="tutoriasComision15" value="{{ oldValueOrDefault('tutoriasComision15') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision15" name="tutoriasComision15"></span>
                        @endif
                    </td>
                    <td id="obs3_9_15">
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
                    <td id="puntaje3_9_16">0</td>
                    <td id="tutorias16">0</td>
                    <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision16" name="tutoriasComision16" value="{{ oldValueOrDefault('tutoriasComision16') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision16" name="tutoriasComision16"></span>
                        @endif
                    </td>
                    <td id="obs3_9_16">
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
                    <td id="puntaje3_9_17">0</td>
                    <td id="tutorias17">0</td>
                    <td class="rightSelect">
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="tutoriasComision17" name="tutoriasComision17" value="{{ oldValueOrDefault('tutoriasComision17') }}" oninput="onActv3Comision3_9()">
                        @else
                            <span id="tutoriasComision17" name="tutoriasComision17"></span>
                        @endif
                    </td>
                    <td id="obs3_9_17">
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
                        <th>
                            @if ($userType != '')
                                <button id="btn3_9" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                            @endif    
                        </th>
                    </tr>
                </thead>
            </table>
    <div style="display: flex; justify-content: space-between;padding-top: 200px;">
        <div id="convocatoria2">
            <!-- Mostrar convocatoria -->
            @if(isset($convocatoria))

                <div style="margin-right: -700px;">
                    <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                </div>
            @endif
        </div>


        <div id="piedepagina2"
            class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === '' ? 'secretaria-style' : '') }}">
            Página 15 de 33
        </div>
</div>
        </form>
    </main>

<script>
    window.onload = function () {
        const footerHeight = document.querySelector('footer').offsetHeight;
        const elements = document.querySelectorAll('.prevent-overlap');

        elements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const viewportHeight = window.innerHeight;

            // Verifica si el elemento está demasiado cerca del footer
            if (rect.bottom > viewportHeight - footerHeight) {
                element.style.pageBreakBefore = "always";
            }
        });

        // Múltiples eventos para mayor compatibilidad
        window.addEventListener('beforeprint', updatePageNumberOnPrint);
        window.matchMedia('print').addListener(updatePageNumberOnPrint);

    };
    document.addEventListener('DOMContentLoaded', async () => {
        const userType = @json($userType);  // Inject user type from backend to JS
        const user_identity = @json($user_identity);
        const docenteSelect = document.getElementById('docenteSelect');

        if (docenteSelect) {
            // Cuando el usuario es dictaminador
            if (userType === 'dictaminador') {
                try {
                   const response = await fetch('/formato-evaluacion/get-docentes');
                    const docentes = await response.json();

                    docentes.forEach(docente => {
                        const option = document.createElement('option');
                        option.value = docente.email;
                        option.textContent = docente.email;
                        docenteSelect.appendChild(option);
                    });

                    docenteSelect.addEventListener('change', async (event) => {
                        const email = event.target.value;

                        if (email) {
                            axios.get('/formato-evaluacion/get-docente-data', { params: { email } })
                                .then(response => {
                                    const data = response.data;

                                    // Update all elements with the class 'score3_9'
                                    const scoreElements = document.querySelectorAll('.score3_9');
                                    scoreElements.forEach(element => {
                                        element.textContent = data.form3_9.score3_9 || '0';
                                    });  
                                    
                                                                      //puntaje
                                    for (let i = 1; i <= 17; i++) {
                                        const elementId = `puntaje3_9_${i}`;
                                        const value = data.form3_9[`puntaje3_9_${i}`] || '0';
                                        document.getElementById(elementId).textContent = value;
                                    }

                                    //tutorias
                                    for (let j = 1; j <= 17; j++) {
                                        const elementId = `tutorias${j}`;
                                        const value = data.form3_9[`tutorias${j}`] || '0';
                                        document.getElementById(elementId).textContent = value;
                                    }


                                    // Populate hidden inputs
                                    document.querySelector('input[name="user_id"]').value = data.form3_9.user_id || '';
                                    document.querySelector('input[name="email"]').value = data.form3_9.email || '';
                                    document.querySelector('input[name="user_type"]').value = data.form3_9.user_type || '';

                                    // Actualizar convocatoria
                                    const convocatoriaElement = document.getElementById('convocatoria');
                                    const convocatoriaElement2 = document.getElementById('convocatoria2');

                                        if (convocatoriaElement) {
                                            if (data.form1) {
                                                convocatoriaElement.textContent = data.form1.convocatoria || '';
                                                convocatoriaElement2.textContent = data.form1.convocatoria || '';
                                            } else {
                                                console.error('form1 no está definido en la respuesta.');
                                            }
                                        } else {
                                            console.error('Elemento con ID "convocatoria" no encontrado.');
                                        }
                                })
                                .catch(error => {
                                    console.error('Error fetching docente data:', error);
                                });
                            //await asignarDocentes(user_identity, email);
                        }
                    });
                } catch (error) {
                    console.error('Error fetching docentes:', error);
                    alert('No se pudo cargar la lista de docentes.');
                }
            }
            // Cuando el userType está vacío
            else if (userType === '') {

                try {
                   const response = await fetch('/formato-evaluacion/get-docentes');

                    const docentes = await response.json();

                    docentes.forEach(docente => {
                        const option = document.createElement('option');
                        option.value = docente.email;
                        option.textContent = docente.email;
                        docenteSelect.appendChild(option);
                    });

                    docenteSelect.addEventListener('change', async (event) => {
                        const email = event.target.value;

                        if (email) {
                            axios.get('/formato-evaluacion/get-docente-data', { params: { email } })
                                .then(response => {
                                    const data = response.data;

                                    // Actualizar convocatoria

                                    // Verifica si la respuesta contiene los datos esperados
                                    if (data.docente) {
                                        const convocatoriaElement = document.getElementById('convocatoria');
                                        const convocatoriaElement2 = document.getElementById('convocatoria2');

                                        // Mostrar la convocatoria si existe
                                        if (convocatoriaElement) {
                                            if (data.docente.convocatoria) {
                                                convocatoriaElement.textContent = data.docente.convocatoria;
                                                convocatoriaElement2.textContent = data.docente.convocatoria;
                                            } else {
                                                convocatoriaElement.textContent = 'Convocatoria no disponible';
                                                convocatoriaElement2.textContent = 'Convocatoria no disponible';
                                            }
                                        }
                                    }
                                });
                            // Lógica para obtener datos de DictaminatorsResponseForm2
                            try {
                                const response = await fetch('/formato-evaluacion/get-dictaminators-responses');
                                const dictaminatorResponses = await response.json();
                                // Filtrar la entrada correspondiente al email seleccionado
                                const selectedResponseForm3_9 = dictaminatorResponses.form3_9.find(res => res.email === email);
                                if (selectedResponseForm3_9) {

                                    document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_9.dictaminador_id || '0';
                                    document.querySelector('input[name="user_id"]').value = selectedResponseForm3_9.user_id || '';
                                    document.querySelector('input[name="email"]').value = selectedResponseForm3_9.email || '';
                                    document.querySelector('input[name="user_type"]').value = selectedResponseForm3_9.user_type || '';


                                    for (let i = 1; i <= 17; i++) {
                                        const puntaje3_9 = `puntaje3_9_${i}`;
                                        const puntaje3_9Value = selectedResponseForm3_9[`puntaje3_9_${i}`] || '0';
                                        document.getElementById(puntaje3_9).textContent = puntaje3_9Value;
                                    }

                                    for (let j = 1; j <= 17; j++) {
                                        const tutorias = `tutorias${j}`;
                                        const tutoriasValue = selectedResponseForm3_9[`tutorias${j}`] || '0';
                                        document.getElementById(tutorias).textContent = tutoriasValue;
                                    }

                                    // Para las comisiones
                                    for (let i = 1; i <= 17; i++) {
                                        const tutoriasComision = `tutoriasComision${i}`;
                                        const tutoriasComisionValue = selectedResponseForm3_9[`tutoriasComision${i}`] || '0';
                                        const ComisionesElement = document.querySelector(`span[name="${tutoriasComision}"]`);
                                        if (ComisionesElement) {
                                            ComisionesElement.textContent = tutoriasComisionValue;
                                        }
                                    }

                                    // Para las observaciones
                                    for (let i = 1; i <= 17; i++) {
                                        const obs3_9_ = `obs3_9_${i}`;
                                        const value = selectedResponseForm3_9[`obs3_9_${i}`] || '';
                                        const element = document.querySelector(`span[name="${obs3_9_}"]`);
                                        if (element) {
                                            element.textContent = value;
                                        }
                                    }

                                    // Update all elements with the class 'score3_9'
                                    const scoreElements = document.querySelectorAll('.score3_9');
                                    scoreElements.forEach(element => {
                                        element.textContent = selectedResponseForm3_9.score3_9 || '0';
                                    });

                                    // Update all elements with the class 'comision3_9'
                                    const comisionElements = document.querySelectorAll('.comision3_9');
                                    comisionElements.forEach(element => {
                                        element.textContent = selectedResponseForm3_9.comision3_9 || '0';
                                    });


                                } else {
                                    console.error('No form3_9 data found for the selected dictaminador.');
                                    // Reset input values if no data found
                                    document.querySelector('input[name="dictaminador_id"]').value = '0';
                                    document.querySelector('input[name="user_id"]').value = '0';
                                    document.querySelector('input[name="email"]').value = '';
                                    document.querySelector('input[name="user_type"]').value = '';

                                    document.querySelector('.score3_9').textContent = '0';

                                    for (let i = 1; i <= 17; i++) {
                                        const elementId = `puntaje3_9_${i}`;
                                        const value = '0';
                                        document.getElementById(elementId).textContent = value;
                                    }

                                    for (let j = 1; j <= 17; j++) {
                                        const elementId = `tutorias${j}`;
                                        const value = '0';
                                        document.getElementById(elementId).textContent = value;
                                    }

                                    // Para las comisiones
                                    for (let i = 1; i <= 17; i++) {
                                        const elementName = `tutoriasComision${i}`;
                                        const value = '0';
                                        const element = document.querySelector(`span[name="${elementName}"]`);
                                        if (element) {
                                            element.textContent = value;
                                        }
                                    }

                                    // Para las observaciones
                                    for (let i = 1; i <= 17; i++) {
                                        const elementName = `obs3_9_${i}`;
                                        const value = ''; // Aquí puedes definir el valor según lo que necesites
                                        const element = document.querySelector(`span[name="${elementName}"]`);
                                        if (element) {
                                            element.textContent = value;
                                        }
                                    }

                                    document.querySelector('.comision3_9').textContent = '0';



                                }
                            } catch (error) {
                                console.error('Error fetching dictaminators responses:', error);
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error fetching docentes:', error);
                    alert('No se pudo cargar la lista de docentes.');
                }


            }



        }



    });

    // Function to handle form submission
    async function submitForm(url, formId) {
        const formData = {};
        const form = document.getElementById(formId);

        if (!form) {
            console.error(`Form with id "${formId}" not found.`);
            return;
        }

        formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
        formData['user_id'] = form.querySelector('input[name="user_id"]').value;
        formData['email'] = form.querySelector('input[name="email"]').value;
        formData['user_type'] = form.querySelector('input[name="user_type"]').value;

        //puntajes
        for (let i = 1; i <= 17; i++) {
            formData[`puntaje3_9_${i}`] = document.getElementById(`puntaje3_9_${i}`)?.textContent || '';
        }

        // tutorias
        for (let j = 1; j <= 17; j++) {
            formData[`tutorias${j}`] = document.getElementById(`tutorias${j}`)?.textContent || '';
        }

        // tutoriasComision
        for (let i = 1; i <= 17; i++) {
            formData[`tutoriasComision${i}`] = form.querySelector(`input[id="tutoriasComision${i}"]`)?.value || '';
        }

        // observationes
        for (let i = 1; i <= 17; i++) {
            formData[`obs3_9_${i}`] = form.querySelector(`input[name="obs3_9_${i}"]`)?.value || '';
        }

        formData['score3_9'] = document.querySelector('.score3_9').textContent;
        formData['comision3_9'] = document.querySelector('.comision3_9').textContent;

        // Observations

        console.log('Form data:', formData);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const responseData = await response.json();
            console.log('Response received from server:', responseData);

            //Mensaje al usuario
            if (responseData.success) {
                showMessage('Formulario enviado', 'green');
            } else {
                showMessage('Formulario no enviado', 'red');
            }
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
    }
    function minWithSum(value1, value2) {
        const sum = value1 + value2;
        return Math.min(sum, 200);


    }

    window.addEventListener('afterprint', function () {
        // Opcional: Restaurar estado original
        document.querySelector('footer').style.display = 'none';
    });
    
    document.addEventListener('DOMContentLoaded', function () {

        const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
        if (toggleDarkModeButton) {
            const widthDarkButton = window.outerWidth - 230;
            toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
        }

        toggleDarkMode();
    });    
</script>


</body>

</html>