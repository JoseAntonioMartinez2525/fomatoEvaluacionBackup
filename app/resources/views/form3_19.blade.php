@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
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
    body.chrome @media print {
        #convocatoria {
            font-size: 1.2rem;
            color: blue;
            /* Ejemplo de estilo específico para Chrome */
        }
    }

    #convocatoria,
    #piedepagina {
        display: none;
    }

    @media print {
        body {
            margin-left: 200px;
            margin-top: -10px;
            padding: 0;
            font-size: .9rem;

        }

        footer {
            position: fixed;
            font-size: .9rem;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
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


        .prevent-overlap {
            page-break-before: always;
        }

        #convocatoria {
            margin: 0;
            display: block;
            margin-top: -80px;
        }

        #piedepagina {
            margin: 0;
            display: block;
        }

        @page {
            size: landscape;
            margin: 20mm;
            /* Ajusta según sea necesario */

        }

        @page: right {
            content: "Página " counter(page);
        }

        .footer-text {
            display: none;
        }

        /* Show the appropriate footer based on the page number */
        @page {
            margin: 0;
            /* Adjust margins as needed */
        }

        /* Use page breaks to control footer visibility */
        .page1 .footer#footer1 {
            display: block;
            /* Show footer for page 1 */
        }

        .page2 .footer#footer2 {
            display: block;
            /* Show footer for page 2 */
        }

        .page3 .footer#footer3 {
            display: block;
            /* Show footer for page 3 */
        }

        page-break-after: auto;
        /* La última página no necesita salto extra */

        #convocatoria,
        #convocatoria2,
        #piedepagina1,
        #piedepagina2 {
            margin: 0;
            font-size: 1rem;
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
    .page-break[data-page="28"] .first-page-footer {
        display: table-footer-group !important;
    }

    .page-break[data-page="29"] .second-page-footer {
        display: table-footer-group !important;
    }

    .page-break[data-page="30"] .third-page-footer {
        display: table-footer-group !important;
    }

    .page-number:before {
        content: "Página " counter(page) " de 33";
    }

    }


</style>

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
$page_counter = 28;
    @endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo
        Obscuro</button>

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
        <!-- Form for Part 3_19 -->
        <form id="form3_19" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-form319', 'form3_19');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <!--3.19 Participación en cuerpos colegiados-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">40</label>
            </h4>
            <table class="table table-sm tutorias">
                <x-sub-headers-form3_19 :componentIndex="0" />
                <tbody data-page="28">
                    <tr>
                        <td>a)</td>
                        <td>Representante del profesorado ante H. CGU</td>
                        <td></td>
                        <td>Titular o suplente</td>
                        <td id="puntajeCGUtitular"><b>20</b></td>
                        <td id="cantCGUtitular" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalCGUtitular"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comCGUtitular" name="comCGUtitular"
                                    value="{{ oldValueOrDefault('comCGUtitular') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comCGUtitular" name="comCGUtitular" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsCGUtitular" name="obsCGUtitular">
                            @else
                                <span id="obsCGUtitular" name="obsCGUtitular" class="form3_19_dark"></span>
                            @endif 
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>Representante del profesorado ante H. CGU</td>
                        <td></td>
                        <td>Participación como miembro de comisión especial</td>
                        <td id="puntajeCGUespecial"><b>15</b></td>
                        <td id="cantCGUespecial" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalCGUespecial"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comCGUespecial" name="comCGUespecial"
                                    value="{{ oldValueOrDefault('comCGUespecial') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comCGUespecial" name="comCGUespecial" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsCGUespecial" name="obsCGUespecial">
                            @else
                                <span id="obsCGUespecial" name="obsCGUespecial" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td>Representante del profesorado ante H. CGU</td>
                        <td></td>
                        <td>Participación como miembro en comisión permanente</td>
                        <td id="puntajeCGUpermanente"><b>10</b></td>
                        <td id="cantCGUpermanente" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalCGUpermanente"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comCGUpermanente" name="comCGUpermanente"
                                    value="{{ oldValueOrDefault('comCGUpermanente') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comCGUpermanente" name="comCGUpermanente" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsCGUpermanente" name="obsCGUpermanente">
                            @else
                                <span id="obsCGUpermanente" name="obsCGUpermanente" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>d)</td>
                        <td>Representante del profesorado ante CAAC</td>
                        <td></td>
                        <td>Titular o suplente</td>
                        <td id="puntajeCAACtitular"><b>10</b></td>
                        <td id="cantCAACtitular" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalCAACtitular"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comCAACtitular" name="comCAACtitular"
                                    value="{{ oldValueOrDefault('comCAACtitular') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comCAACtitular" name="comCAACtitular" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsCAACtitular" name="obsCAACtitular">
                            @else
                                <span id="obsCAACtitular" name="obsCAACtitular" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>e)</td>
                        <td>Representante del profesorado ante CAAC</td>
                        <td></td>
                        <td>Participación como integrante de comisión</td>
                        <td id="puntajeCAACintegCom"><b>5</b></td>
                        <td id="cantCAACintegCom" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalCAACintegCom"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comCAACintegCom" name="comCAACintegCom"
                                    value="{{ oldValueOrDefault('comCAACintegCom') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comCAACintegCom" name="comCAACintegCom" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsCAACintegCom" name="obsCAACintegCom">
                            @else
                                <span id="obsCAACintegCom" name="obsCAACintegCom" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; justify-content: space-between;padding-top: 80px;">
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
                    Página 28 de 33
                </div>
            </div><br><br>
            <!--Siguiente tabla-->
            <table class="table table-sm tutorias">
                <x-sub-headers-form3_19 :componentIndex="1" />
                <tbody data-page="29">
                    <tr>
                        <td>f)</td>
                        <td>Comisiones</td>
                        <td></td>
                        <td>Departamentales</td>
                        <td id="puntajeComDepart"><b>15</b></td>
                        <td id="cantComDepart" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalComDepart"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comComDepart" name="comComDepart"
                                    value="{{ oldValueOrDefault('comComDepart') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comComDepart" name="comComDepart" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsComDepart" name="obsComDepart">
                            @else
                                <span id="obsComDepart" name="obsComDepart" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>g)</td>
                        <td>Comisiones</td>
                        <td></td>
                        <td>Dictaminadora del PEDPD</td>
                        <td id="puntajeComPEDPD"><b>15</b></td>
                        <td id="cantComPEDPD" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalComPEDPD"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comComPEDPD" name="comComPEDPD"
                                    value="{{ oldValueOrDefault('comComPEDPD') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comComPEDPD" name="comComPEDPD" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsComPEDPD" name="obsComPEDPD">
                            @else
                                <span id="obsComPEDPD" name="obsComPEDPD" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>h)</td>
                        <td>Comisiones</td>
                        <td></td>
                        <td>Participación como integrante del Comité Académico de Posgrado</td>
                        <td id="puntajeComPartPos"><b>5</b></td>
                        <td id="cantComPartPos" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalComPartPos"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comComPartPos" name="comComPartPos"
                                    value="{{ oldValueOrDefault('comComPartPos') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comComPartPos" name="comComPartPos" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsComPartPos" name="obsComPartPos">
                            @else
                                <span id="obsComPartPos" name="obsComPartPos" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>i)</td>
                        <td>Responsable</td>
                        <td></td>
                        <td>De posgrado</td>
                        <td id="puntajeRespPos"><b>25</b></td>
                        <td id="cantRespPos" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalRespPos"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comRespPos" name="comRespPos"
                                    value="{{ oldValueOrDefault('comRespPos') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comRespPos" name="comRespPos" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsRespPos" name="obsRespPos">
                            @else
                                <span id="obsRespPos" name="obsRespPos" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>j)</td>
                        <td>Responsable</td>
                        <td></td>
                        <td>De carrera</td>
                        <td id="puntajeRespCarrera"><b>15</b></td>
                        <td id="cantRespCarrera" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalRespCarrera"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comRespCarrera" name="comRespCarrera"
                                    value="{{ oldValueOrDefault('comRespCarrera') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comRespCarrera" name="comRespCarrera" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsRespCarrera" name="obsRespCarrera">
                            @else
                                <span id="obsRespCarrera" name="obsRespCarrera" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>k)</td>
                        <td>Responsable</td>
                        <td></td>
                        <td>De unidad de producción</td>
                        <td id="puntajeRespProd"><b>20</b></td>
                        <td id="cantRespProd" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalRespProd"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comRespProd" name="comRespProd"
                                    value="{{ oldValueOrDefault('comRespProd') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comRespProd" name="comRespProd" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsRespProd" name="obsRespProd">
                            @else
                                <span id="obsRespProd" name="obsRespProd" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>l)</td>
                        <td>Responsable</td>
                        <td></td>
                        <td>De laboratorio de docencia e investigación</td>
                        <td id="puntajeRespLab"><b>15</b></td>
                        <td id="cantRespLab" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalRespLab"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comRespLab" name="comRespLab"
                                    value="{{ oldValueOrDefault('comRespLab') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comRespLab" name="comRespLab" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsRespLab" name="obsRespLab">
                            @else
                                <span id="obsRespLab" name="obsRespLab" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>m)</td>
                        <td>Sinodalías de examen de oposición</td>
                        <td></td>
                        <td>Profesorado</td>
                        <td id="puntajeExamProf"><b>15</b></td>
                        <td id="cantExamProf" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalExamProf"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comExamProf" name="comExamProf"
                                    value="{{ oldValueOrDefault('comExamProf') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comExamProf" name="comExamProf" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsExamProf" name="obsExamProf">
                            @else
                                <span id="obsExamProf" name="obsExamProf" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>n)</td>
                        <td>Sinodalías de examen de oposición</td>
                        <td></td>
                        <td>Ayudantes académicos</td>
                        <td id="puntajeExamAcademicos"><b>5</b></td>
                        <td id="cantExamAcademicos" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalExamAcademicos"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comExamAcademicos" name="comExamAcademicos"
                                    value="{{ oldValueOrDefault('comExamAcademicos') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comExamAcademicos" name="comExamAcademicos" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsExamAcademicos" name="obsExamAcademicos">
                            @else
                                <span id="obsExamAcademicos" name="obsExamAcademicos" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="display: flex; justify-content: space-between;padding-top: 150px;">
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
                    Página 29 de 33
                </div>
            </div><br><br>

            <!--Tabla 3-->
            <table>
                <x-sub-headers-form3_19 :componentIndex="2" />
                <tbody data-page="30">
                    <tr>
                        <td>o1)</td>
                        <td>Cuerpo académico registrado ante PRODEP</td>
                        <td>En formación</td>
                        <td>Responsable</td>
                        <td id="puntajePRODEPformResp"><b>15</b></td>
                        <td id="cantPRODEPformResp" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalPRODEPformResp"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comPRODEPformResp" name="comPRODEPformResp"
                                    value="{{ oldValueOrDefault('comPRODEPformResp') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comPRODEPformResp" name="comPRODEPformResp" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsPRODEPformResp" name="obsPRODEPformResp">
                            @else
                                <span id="obsPRODEPformResp" name="obsPRODEPformResp" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>o2)</td>
                        <td>Cuerpo académico registrado ante PRODEP</td>
                        <td>En formación</td>
                        <td>Integrante</td>
                        <td id="puntajePRODEPformInteg"><b>10</b></td>
                        <td id="cantPRODEPformInteg" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalPRODEPformInteg"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comPRODEPformInteg" name="comPRODEPformInteg"
                                    value="{{ oldValueOrDefault('comPRODEPformInteg') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comPRODEPformInteg" name="comPRODEPformInteg" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsPRODEPformInteg" name="obsPRODEPformInteg">
                            @else
                                <span id="obsPRODEPformInteg" name="obsPRODEPformInteg" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>p1)</td>
                        <td>Cuerpo académico registrado ante PRODEP</td>
                        <td>En consolidación</td>
                        <td>Responsable</td>
                        <td id="puntajePRODEPenconsResp"><b>25</b></td>
                        <td id="cantPRODEPenconsResp" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalPRODEPenconsResp"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comPRODEPenconsResp" name="comPRODEPenconsResp"
                                    value="{{ oldValueOrDefault('comPRODEPenconsResp') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comPRODEPenconsResp" name="comPRODEPenconsResp" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsPRODEPenconsResp" name="obsPRODEPenconsResp">
                            @else
                                <span id="obsPRODEPenconsResp" name="obsPRODEPenconsResp" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>p2)</td>
                        <td>Cuerpo académico registrado ante PRODEP</td>
                        <td>En consolidación</td>
                        <td>Integrante</td>
                        <td id="puntajePRODEPenconsInteg"><b>15</b></td>
                        <td id="cantPRODEPenconsInteg" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalPRODEPenconsInteg"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comPRODEPenconsInteg" name="comPRODEPenconsInteg"
                                    value="{{ oldValueOrDefault('comPRODEPenconsInteg') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comPRODEPenconsInteg" name="comPRODEPenconsInteg" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsPRODEPenconsInteg"
                                    name="obsPRODEPenconsInteg">
                            @else
                                <span id="obsPRODEPenconsInteg" name="obsPRODEPenconsInteg" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>q1)</td>
                        <td>Cuerpo académico registrado ante PRODEP</td>
                        <td>Consolidado</td>
                        <td>Responsable</td>
                        <td id="puntajePRODEPconsResp"><b>35</b></td>
                        <td id="cantPRODEPconsResp" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalPRODEPconsResp"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comPRODEPconsResp" name="comPRODEPconsResp"
                                    value="{{ oldValueOrDefault('comPRODEPconsResp') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comPRODEPconsResp" name="comPRODEPconsResp" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsPRODEPconsResp" name="obsPRODEPconsResp">
                            @else
                                <span id="obsPRODEPconsResp" name="obsPRODEPconsResp" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>q2)</td>
                        <td>Cuerpo académico registrado ante PRODEP</td>
                        <td>Consolidado</td>
                        <td>Integrante</td>
                        <td id="puntajePRODEPconsInteg"><b>25</b></td>
                        <td id="cantPRODEPconsInteg" class="form3_19_dark"></td>
                        <td></td>
                        <td id="subtotalPRODEPconsInteg"></td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comPRODEPconsInteg" name="comPRODEPconsInteg"
                                    value="{{ oldValueOrDefault('comPRODEPconsInteg') }}" oninput="onActv3Comision3_19()">
                            @else
                                <span id="comPRODEPconsInteg" name="comPRODEPconsInteg" class="form3_19_dark"></span>
                            @endif
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsPRODEPconsInteg" name="obsPRODEPconsInteg">
                            @else
                                <span id="obsPRODEPconsInteg" name="obsPRODEPconsInteg" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>

            <!--Tabla informativa Acreditacion Actividad 3.19-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col" colspan=2> **Coparticipación técnica
                            y/o académica y/o
                            financiera de institución extranjera</th>
                        <th class="acreditacion" style="padding-left: 100px;">Acreditacion:</th>
                        <th class="descripcion"><b>Institución que lo solicite, SG, CA, JD, DGAA</b></th>
                    </tr>
                </thead>
            </table>
            @if ($userType != '')
                <button id="btn3_19" type="submit" class="btn custom-btn printButtonClass">Enviar</button>

            @endif
            <div style="display: flex; justify-content: space-between;padding-top: 200px;">
                <div id="convocatoria3">
                    <!-- Mostrar convocatoria -->
                    @if(isset($convocatoria))

                        <div style="margin-right: -700px;">
                            <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                        </div>
                    @endif
                </div>


                <div id="piedepagina3"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === '' ? 'secretaria-style' : '') }}">
                    Página 30 de 33
                </div>
            </div>
        </form>
        <!--
                @if ($userType == '')
                    <form action="{{ route('generate.pdf') }}" id="button3_19" method="POST" onsubmit="generatePdf('button3_19');">
                        @csrf
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="user_type" value="{{ Auth::user()->user_type }}">
                        <button id="btn3_19" type="submit" class="btn custom-btn printButtonClass">Generar PDF</button>
                    </form>
                @endif
                -->
    </main>


    <script>

        window.onload = function () {
            const pageCount = 3; // Total number of pages
            const currentPage = Math.ceil(window.printPageNumber || 1); // Assuming you have a way to track the current page
            let footerText = '';
            // Hide all footers
            document.querySelectorAll('.footer').forEach(footer => footer.style.display = 'none');
            sendCurrentPageToServer(currentPage);
            setTimeout(updateFooter, 100);
        };

        let cant3_19 = [
            'cantCGUtitular', 'cantCGUespecial', 'cantCGUpermanente',
            'cantCAACtitular', 'cantCAACintegCom', 'cantComDepart',
            'cantComPEDPD', 'cantComPartPos', 'cantRespPos',
            'cantRespCarrera', 'cantRespProd', 'cantRespLab',
            'cantExamProf', 'cantExamAcademicos', 'cantPRODEPformResp',
            'cantPRODEPformInteg', 'cantPRODEPenconsResp', 'cantPRODEPenconsInteg',
            'cantPRODEPconsResp', 'cantPRODEPconsInteg'
        ];

        let subtotal3_19 = [
            'subtotalCGUtitular', 'subtotalCGUespecial', 'subtotalCGUpermanente',
            'subtotalCAACtitular', 'subtotalCAACintegCom', 'subtotalComDepart',
            'subtotalComPEDPD', 'subtotalComPartPos', 'subtotalRespPos',
            'subtotalRespCarrera', 'subtotalRespProd', 'subtotalRespLab',
            'subtotalExamProf', 'subtotalExamAcademicos', 'subtotalPRODEPformResp',
            'subtotalPRODEPformInteg', 'subtotalPRODEPenconsResp', 'subtotalPRODEPenconsInteg',
            'subtotalPRODEPconsResp', 'subtotalPRODEPconsInteg'
        ];

        let comision3_19 = [
            'comCGUtitular', 'comCGUespecial', 'comCGUpermanente',
            'comCAACtitular', 'comCAACintegCom', 'comComDepart',
            'comComPEDPD', 'comComPartPos', 'comRespPos',
            'comRespCarrera', 'comRespProd', 'comRespLab',
            'comExamProf', 'comExamAcademicos', 'comPRODEPformResp',
            'comPRODEPformInteg', 'comPRODEPenconsResp', 'comPRODEPenconsInteg',
            'comPRODEPconsResp', 'comPRODEPconsInteg'
        ];

        let obs3_19 = [
            'obsCGUtitular', 'obsCGUespecial', 'obsCGUpermanente',
            'obsCAACtitular', 'obsCAACintegCom', 'obsComDepart',
            'obsComPEDPD', 'obsComPartPos', 'obsRespPos',
            'obsRespCarrera', 'obsRespProd', 'obsRespLab',
            'obsExamProf', 'obsExamAcademicos', 'obsPRODEPformResp',
            'obsPRODEPformInteg', 'obsPRODEPenconsResp', 'obsPRODEPenconsInteg',
            'obsPRODEPconsResp', 'obsPRODEPconsInteg'
        ];


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

                                        // Populate fields with fetched data
                                        // Update all elements with the class 'score3_19'
                                        const scoreElements = document.querySelectorAll('.score3_19');
                                        scoreElements.forEach(element => {
                                            element.textContent = data.form3_19.score3_19 || '0';
                                        });

                                        // Cantidades
                                        document.getElementById('cantCGUtitular').textContent = data.form3_19.cantCGUtitular || '0';
                                        document.getElementById('cantCGUespecial').textContent = data.form3_19.cantCGUespecial || '0';
                                        document.getElementById('cantCGUpermanente').textContent = data.form3_19.cantCGUpermanente || '0';
                                        document.getElementById('cantCAACtitular').textContent = data.form3_19.cantCAACtitular || '0';
                                        document.getElementById('cantCAACintegCom').textContent = data.form3_19.cantCAACintegCom || '0';
                                        document.getElementById('cantComDepart').textContent = data.form3_19.cantComDepart || '0';
                                        document.getElementById('cantComPEDPD').textContent = data.form3_19.cantComPEDPD || '0';
                                        document.getElementById('cantComPartPos').textContent = data.form3_19.cantComPartPos || '0';
                                        document.getElementById('cantRespPos').textContent = data.form3_19.cantRespPos || '0';
                                        document.getElementById('cantRespCarrera').textContent = data.form3_19.cantRespCarrera || '0';
                                        document.getElementById('cantRespProd').textContent = data.form3_19.cantRespProd || '0';
                                        document.getElementById('cantRespLab').textContent = data.form3_19.cantRespLab || '0';
                                        document.getElementById('cantExamProf').textContent = data.form3_19.cantExamProf || '0';
                                        document.getElementById('cantExamAcademicos').textContent = data.form3_19.cantExamAcademicos || '0';
                                        document.getElementById('cantPRODEPformResp').textContent = data.form3_19.cantPRODEPformResp || '0';
                                        document.getElementById('cantPRODEPformInteg').textContent = data.form3_19.cantPRODEPformInteg || '0';
                                        document.getElementById('cantPRODEPenconsResp').textContent = data.form3_19.cantPRODEPenconsResp || '0';
                                        document.getElementById('cantPRODEPenconsInteg').textContent = data.form3_19.cantPRODEPenconsInteg || '0';
                                        document.getElementById('cantPRODEPconsResp').textContent = data.form3_19.cantPRODEPconsResp || '0';
                                        document.getElementById('cantPRODEPconsInteg').textContent = data.form3_19.cantPRODEPconsInteg || '0';



                                        // Subtotales
                                        document.getElementById('subtotalCGUtitular').textContent = data.form3_19.subtotalCGUtitular || '0';
                                        document.getElementById('subtotalCGUespecial').textContent = data.form3_19.subtotalCGUespecial || '0';
                                        document.getElementById('subtotalCGUpermanente').textContent = data.form3_19.subtotalCGUpermanente || '0';
                                        document.getElementById('subtotalCAACtitular').textContent = data.form3_19.subtotalCAACtitular || '0';
                                        document.getElementById('subtotalCAACintegCom').textContent = data.form3_19.subtotalCAACintegCom || '0';
                                        document.getElementById('subtotalComDepart').textContent = data.form3_19.subtotalComDepart || '0';
                                        document.getElementById('subtotalComPEDPD').textContent = data.form3_19.subtotalComPEDPD || '0';
                                        document.getElementById('subtotalComPartPos').textContent = data.form3_19.subtotalComPartPos || '0';
                                        document.getElementById('subtotalRespPos').textContent = data.form3_19.subtotalRespPos || '0';
                                        document.getElementById('subtotalRespCarrera').textContent = data.form3_19.subtotalRespCarrera || '0';
                                        document.getElementById('subtotalRespProd').textContent = data.form3_19.subtotalRespProd || '0';
                                        document.getElementById('subtotalRespLab').textContent = data.form3_19.subtotalRespLab || '0';
                                        document.getElementById('subtotalExamProf').textContent = data.form3_19.subtotalExamProf || '0';
                                        document.getElementById('subtotalExamAcademicos').textContent = data.form3_19.subtotalExamAcademicos || '0';
                                        document.getElementById('subtotalPRODEPformResp').textContent = data.form3_19.subtotalPRODEPformResp || '0';
                                        document.getElementById('subtotalPRODEPformInteg').textContent = data.form3_19.subtotalPRODEPformInteg || '0';
                                        document.getElementById('subtotalPRODEPenconsResp').textContent = data.form3_19.subtotalPRODEPenconsResp || '0';
                                        document.getElementById('subtotalPRODEPenconsInteg').textContent = data.form3_19.subtotalPRODEPenconsInteg || '0';
                                        document.getElementById('subtotalPRODEPconsResp').textContent = data.form3_19.subtotalPRODEPconsResp || '0';
                                        document.getElementById('subtotalPRODEPconsInteg').textContent = data.form3_19.subtotalPRODEPconsInteg || '0';


                                        // Populate hidden inputs
                                        document.querySelector('input[name="user_id"]').value = data.form3_19.user_id || '';
                                        document.querySelector('input[name="email"]').value = data.form3_19.email || '';
                                        document.querySelector('input[name="user_type"]').value = data.form3_19.user_type || '';

                                        // Actualizar convocatoria
                                        const convocatoriaElement = document.getElementById('convocatoria');
                                        const convocatoriaElement2 = document.getElementById('convocatoria2');
                                        const convocatoriaElement3 = document.getElementById('convocatoria3');
                                        if (convocatoriaElement) {
                                            if (data.form1) {
                                                convocatoriaElement.textContent = data.form1.convocatoria || '';
                                                convocatoriaElement2.textContent = data.form1.convocatoria || '';
                                                convocatoriaElement3.textContent = data.form1.convocatoria || '';


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
                                            const convocatoriaElement3 = document.getElementById('convocatoria3');

                                            // Mostrar la convocatoria si existe
                                            if (convocatoriaElement) {
                                                if (data.docente.convocatoria) {
                                                    convocatoriaElement.textContent = data.docente.convocatoria;
                                                    convocatoriaElement2.textContent = data.docente.convocatoria;
                                                    convocatoriaElement3.textContent = data.docente.convocatoria;

                                                } else {
                                                    convocatoriaElement.textContent = 'Convocatoria no disponible';
                                                    convocatoriaElement2.textContent = 'Convocatoria no disponible';
                                                    convocatoriaElement3.textContent = 'Convocatoria no disponible';

                                                }
                                            }
                                        }
                                    });
                                // Lógica para obtener datos de DictaminatorsResponseForm2
                                try {
                                    const response = await fetch('/formato-evaluacion/get-dictaminators-responses');
                                    const dictaminatorResponses = await response.json();
                                    // Filtrar la entrada correspondiente al email seleccionado
                                    const selectedResponseForm3_19 = dictaminatorResponses.form3_19.find(res => res.email === email);
                                    if (selectedResponseForm3_19) {
                                        document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_19.dictaminador_id || '0';
                                        document.querySelector('input[name="user_id"]').value = selectedResponseForm3_19.user_id || '';
                                        document.querySelector('input[name="email"]').value = selectedResponseForm3_19.email || '';
                                        document.querySelector('input[name="user_type"]').value = selectedResponseForm3_19.user_type || '';


                                        // Cantidades
                                        document.getElementById('cantCGUtitular').textContent = selectedResponseForm3_19.cantCGUtitular || '0';
                                        document.getElementById('cantCGUespecial').textContent = selectedResponseForm3_19.cantCGUespecial || '0';
                                        document.getElementById('cantRespLab').textContent = selectedResponseForm3_19.cantRespLab || '0';
                                        document.getElementById('cantCGUpermanente').textContent = selectedResponseForm3_19.cantCGUpermanente || '0';
                                        document.getElementById('cantCAACtitular').textContent = selectedResponseForm3_19.cantCAACtitular || '0';
                                        document.getElementById('cantCAACintegCom').textContent = selectedResponseForm3_19.cantCAACintegCom || '0';
                                        document.getElementById('cantComDepart').textContent = selectedResponseForm3_19.cantComDepart || '0';
                                        document.getElementById('cantComPEDPD').textContent = selectedResponseForm3_19.cantComPEDPD || '0';
                                        document.getElementById('cantComPartPos').textContent = selectedResponseForm3_19.cantComPartPos || '0';
                                        document.getElementById('cantRespPos').textContent = selectedResponseForm3_19.cantRespPos || '0';
                                        document.getElementById('cantRespCarrera').textContent = selectedResponseForm3_19.cantRespCarrera || '0';
                                        document.getElementById('cantRespProd').textContent = selectedResponseForm3_19.cantRespProd || '0';
                                        document.getElementById('cantExamProf').textContent = selectedResponseForm3_19.cantExamProf || '0';
                                        document.getElementById('cantExamAcademicos').textContent = selectedResponseForm3_19.cantExamAcademicos || '0';
                                        document.getElementById('cantPRODEPformResp').textContent = selectedResponseForm3_19.cantPRODEPformResp || '0';
                                        document.getElementById('cantPRODEPformInteg').textContent = selectedResponseForm3_19.cantPRODEPformInteg || '0';
                                        document.getElementById('cantPRODEPenconsResp').textContent = selectedResponseForm3_19.cantPRODEPenconsResp || '0';
                                        document.getElementById('cantPRODEPenconsInteg').textContent = selectedResponseForm3_19.cantPRODEPenconsInteg || '0';
                                        document.getElementById('cantPRODEPconsResp').textContent = selectedResponseForm3_19.cantPRODEPconsResp || '0';
                                        document.getElementById('cantPRODEPconsInteg').textContent = selectedResponseForm3_19.cantPRODEPconsInteg || '0';


                                        // Subtotales
                                        document.getElementById('subtotalCGUtitular').textContent = selectedResponseForm3_19.subtotalCGUtitular || '0';
                                        document.getElementById('subtotalCGUespecial').textContent = selectedResponseForm3_19.subtotalCGUespecial || '0';
                                        document.getElementById('subtotalCGUpermanente').textContent = selectedResponseForm3_19.subtotalCGUpermanente || '0';
                                        document.getElementById('subtotalCAACtitular').textContent = selectedResponseForm3_19.subtotalCAACtitular || '0';
                                        document.getElementById('subtotalCAACintegCom').textContent = selectedResponseForm3_19.subtotalCAACintegCom || '0';
                                        document.getElementById('subtotalComDepart').textContent = selectedResponseForm3_19.subtotalComDepart || '0';
                                        document.getElementById('subtotalComPEDPD').textContent = selectedResponseForm3_19.subtotalComPEDPD || '0';
                                        document.getElementById('subtotalComPartPos').textContent = selectedResponseForm3_19.subtotalComPartPos || '0';
                                        document.getElementById('subtotalRespPos').textContent = selectedResponseForm3_19.subtotalRespPos || '0';
                                        document.getElementById('subtotalRespCarrera').textContent = selectedResponseForm3_19.subtotalRespCarrera || '0';
                                        document.getElementById('subtotalRespProd').textContent = selectedResponseForm3_19.subtotalRespProd || '0';
                                        document.getElementById('subtotalRespLab').textContent = selectedResponseForm3_19.subtotalRespLab || '0';
                                        document.getElementById('subtotalExamProf').textContent = selectedResponseForm3_19.subtotalExamProf || '0';
                                        document.getElementById('subtotalExamAcademicos').textContent = selectedResponseForm3_19.subtotalExamAcademicos || '0';
                                        document.getElementById('subtotalPRODEPformResp').textContent = selectedResponseForm3_19.subtotalPRODEPformResp || '0';
                                        document.getElementById('subtotalPRODEPformInteg').textContent = selectedResponseForm3_19.subtotalPRODEPformInteg || '0';
                                        document.getElementById('subtotalPRODEPenconsResp').textContent = selectedResponseForm3_19.subtotalPRODEPenconsResp || '0';
                                        document.getElementById('subtotalPRODEPenconsInteg').textContent = selectedResponseForm3_19.subtotalPRODEPenconsInteg || '0';
                                        document.getElementById('subtotalPRODEPconsResp').textContent = selectedResponseForm3_19.subtotalPRODEPconsResp || '0';
                                        document.getElementById('subtotalPRODEPconsInteg').textContent = selectedResponseForm3_19.subtotalPRODEPconsInteg || '0';

                                        // Comisiones
                                        document.querySelector('#comCGUtitular').textContent = selectedResponseForm3_19.comCGUtitular || '0';
                                        document.querySelector('#comCGUespecial').textContent = selectedResponseForm3_19.comCGUespecial || '0';
                                        document.querySelector('#comCGUpermanente').textContent = selectedResponseForm3_19.comCGUpermanente || '0';
                                        document.querySelector('#comCAACtitular').textContent = selectedResponseForm3_19.comCAACtitular || '0';
                                        document.querySelector('#comCAACintegCom').textContent = selectedResponseForm3_19.comCAACintegCom || '0';
                                        document.querySelector('#comComDepart').textContent = selectedResponseForm3_19.comComDepart || '0';
                                        document.querySelector('#comComPEDPD').textContent = selectedResponseForm3_19.comComPEDPD || '0';
                                        document.querySelector('#comComPartPos').textContent = selectedResponseForm3_19.comComPartPos || '0';
                                        document.querySelector('#comRespPos').textContent = selectedResponseForm3_19.comRespPos || '0';
                                        document.querySelector('#comRespCarrera').textContent = selectedResponseForm3_19.comRespCarrera || '0';
                                        document.querySelector('#comRespProd').textContent = selectedResponseForm3_19.comRespProd || '0';
                                        document.querySelector('#comRespLab').textContent = selectedResponseForm3_19.comRespLab || '0';
                                        document.querySelector('#comExamProf').textContent = selectedResponseForm3_19.comExamProf || '0';
                                        document.querySelector('#comExamAcademicos').textContent = selectedResponseForm3_19.comExamAcademicos || '0';
                                        document.querySelector('#comPRODEPformResp').textContent = selectedResponseForm3_19.comPRODEPformResp || '0';
                                        document.querySelector('#comPRODEPformInteg').textContent = selectedResponseForm3_19.comPRODEPformInteg || '0';
                                        document.querySelector('#comPRODEPenconsResp').textContent = selectedResponseForm3_19.comPRODEPenconsResp || '0';
                                        document.querySelector('#comPRODEPenconsInteg').textContent = selectedResponseForm3_19.comPRODEPenconsInteg || '0';
                                        document.querySelector('#comPRODEPconsResp').textContent = selectedResponseForm3_19.comPRODEPconsResp || '0';
                                        document.querySelector('#comPRODEPconsInteg').textContent = selectedResponseForm3_19.comPRODEPconsInteg || '0';

                                        // Observaciones
                                        document.querySelector('#obsCGUtitular').textContent = selectedResponseForm3_19.obsCGUtitular || '';
                                        document.querySelector('#obsCGUespecial').textContent = selectedResponseForm3_19.obsCGUespecial || '';
                                        document.querySelector('#obsCGUpermanente').textContent = selectedResponseForm3_19.obsCGUpermanente || '';
                                        document.querySelector('#obsCAACtitular').textContent = selectedResponseForm3_19.obsCAACtitular || '';
                                        document.querySelector('#obsCAACintegCom').textContent = selectedResponseForm3_19.obsCAACintegCom || '';
                                        document.querySelector('#obsComDepart').textContent = selectedResponseForm3_19.obsComDepart || '';
                                        document.querySelector('#obsComPEDPD').textContent = selectedResponseForm3_19.obsComPEDPD || '';
                                        document.querySelector('#obsComPartPos').textContent = selectedResponseForm3_19.obsComPartPos || '';
                                        document.querySelector('#obsRespPos').textContent = selectedResponseForm3_19.obsRespPos || '';
                                        document.querySelector('#obsRespCarrera').textContent = selectedResponseForm3_19.obsRespCarrera || '';
                                        document.querySelector('#obsRespProd').textContent = selectedResponseForm3_19.obsRespProd || '';
                                        document.querySelector('#obsRespLab').textContent = selectedResponseForm3_19.obsRespLab || '';
                                        document.querySelector('#obsExamProf').textContent = selectedResponseForm3_19.obsExamProf || '';
                                        document.querySelector('#obsExamAcademicos').textContent = selectedResponseForm3_19.obsExamAcademicos || '';
                                        document.querySelector('#obsPRODEPformResp').textContent = selectedResponseForm3_19.obsPRODEPformResp || '';
                                        document.querySelector('#obsPRODEPformInteg').textContent = selectedResponseForm3_19.obsPRODEPformInteg || '';
                                        document.querySelector('#obsPRODEPenconsResp').textContent = selectedResponseForm3_19.obsPRODEPenconsResp || '';
                                        document.querySelector('#obsPRODEPenconsInteg').textContent = selectedResponseForm3_19.obsPRODEPenconsInteg || '';
                                        document.querySelector('#obsPRODEPconsResp').textContent = selectedResponseForm3_19.obsPRODEPconsResp || '';
                                        document.querySelector('#obsPRODEPconsInteg').textContent = selectedResponseForm3_19.obsPRODEPconsInteg || '';

                                        // Update all elements with the class 'score3_19'
                                        const scoreElements = document.querySelectorAll('.score3_19');
                                        scoreElements.forEach(element => {
                                            element.textContent = selectedResponseForm3_19.score3_19 || '0';
                                        });

                                        // Update all elements with the class 'comision3_19'
                                        const comisionElements = document.querySelectorAll('.comision3_19');
                                        comisionElements.forEach(element => {
                                            element.textContent = selectedResponseForm3_19.comision3_19 || '0';
                                        });


                                    } else {
                                        console.error('No form3_19 data found for the selected dictaminador.');

                                        // Reset input values
                                        setValue('input[name="dictaminador_id"]', '0');
                                        setValue('input[name="user_id"]', '0');
                                        setValue('input[name="email"]', '');
                                        setValue('input[name="user_type"]', '');

                                        setValue('.score3_19', '0');
                                        setValue('.comision3_19', '0');

                                        // Reset cantidad values
                                        cant3_19.forEach(cantidad => setValue(`#${cantidad}`, '0'));

                                        // Reset subtotal values
                                        subtotal3_19.forEach(subtotal => setValue(`#${subtotal}`, '0'));

                                        // Reset comision values
                                        comision3_19.forEach(comision => setValue(`#${comision}`, '0'));

                                        // Reset observation values
                                        obs3_19.forEach(obs => setValue(`#${obs}`, ''));
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

            // Puntajes
            for (let i = 0; i < cant3_19.length; i++) {
                formData[cant3_19[i]] = document.getElementById(cant3_19[i])?.textContent || '';
            }

            // Subtotales
            for (let j = 0; j < subtotal3_19.length; j++) {
                formData[subtotal3_19[j]] = document.getElementById(subtotal3_19[j])?.textContent || '';
            }

            // Comisiones
            for (let k = 0; k < comision3_19.length; k++) {
                formData[comision3_19[k]] = form.querySelector(`input[id="${comision3_19[k]}"]`)?.value || '';
            }

            // Observaciones
            for (let l = 0; l < obs3_19.length; l++) {
                formData[obs3_19[l]] = form.querySelector(`input[id="${obs3_19[l]}"]`)?.value || '';
            }

            formData['score3_19'] = document.querySelector('.score3_19').textContent;
            
            // Comision
            const comisionElement = document.querySelector('.comision3_19');
            formData['comision3_19'] = comisionElement ? comisionElement.textContent : '0';


            // Observations

            console.log('Form data:', formData);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', 

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
        async function generatePdf() {
            const userType = @json($userType);  // Inject user type from backend to JS
            const user_identity = @json($user_identity);
            const docenteSelect = document.getElementById('docenteSelect');

            const email = docenteSelect.value; // Get selected docente email

            if (email) {
                try {
                    const response = await axios.get('/get-docente-data', { params: { email } });
                    const data = response.data;

                    if (!data || !data.form3_19) {
                        console.error('User data not found for the selected docente.');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('email', email); // Use the email from docenteSelect
                    formData.append('user_id', data.form3_19.user_id);
                    formData.append('user_type', data.form3_19.user_type);

                    const responsePdf = await fetch('{{ route('generate.pdf') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (!responsePdf.ok) {
                        throw new Error('Network response was not ok.');
                    }

                    const result = await responsePdf.json();
                    const docDefinition = {
                        content: [
                            { text: 'User Data', fontSize: 15 },
                            { text: `ID: ${data.form3_19.id}`, fontSize: 12 },
                            { text: `Dictaminador ID: ${data.form3_19.dictaminador_id}`, fontSize: 12 },
                            { text: `User ID: ${data.form3_19.user_id}`, fontSize: 12 },
                            { text: `Email: ${email}`, fontSize: 12 }, // Match email from docenteSelect
                            { text: `Score: ${data.form3_19.score3_19}`, fontSize: 12 },
                            { text: `Cantidad CGU Titular: ${data.form3_19.cantCGUtitular}`, fontSize: 12 },
                            { text: `Subtotal CGU Titular: ${data.form3_19.subtotalCGUtitular}`, fontSize: 12 },
                            { text: `Comisión CGU Titular: ${data.form3_19.comCGUtitular}`, fontSize: 12 },
                            { text: `Observaciones CGU Titular: ${data.form3_19.obsCGUtitular}`, fontSize: 12 },
                            { text: `Cantidad CGU Especial: ${data.form3_19.cantCGUespecial}`, fontSize: 12 },
                            { text: `Subtotal CGU Especial: ${data.form3_19.subtotalCGUespecial}`, fontSize: 12 },
                            { text: `Comisión CGU Especial: ${data.form3_19.comCGUespecial}`, fontSize: 12 },
                            { text: `Observaciones CGU Especial: ${data.form3_19.obsCGUespecial}`, fontSize: 12 },
                            { text: `Cantidad CGU Permanente: ${data.form3_19.cantCGUpermanente}`, fontSize: 12 },
                            { text: `Subtotal CGU Permanente: ${data.form3_19.subtotalCGUpermanente}`, fontSize: 12 },
                            { text: `Comisión CGU Permanente: ${data.form3_19.comCGUpermanente}`, fontSize: 12 },
                            { text: `Observaciones CGU Permanente: ${data.form3_19.obsCGUpermanente}`, fontSize: 12 },
                            { text: `Cantidad CAAC Titular: ${data.form3_19.cantCAACtitular}`, fontSize: 12 },
                            { text: `Subtotal CAAC Titular: ${data.form3_19.subtotalCAACtitular}`, fontSize: 12 },
                            { text: `Comisión CAAC Titular: ${data.form3_19.comCAACtitular}`, fontSize: 12 },
                            { text: `Observaciones CAAC Titular: ${data.form3_19.obsCAACtitular}`, fontSize: 12 },
                            { text: `Cantidad CAAC Integrante: ${data.form3_19.cantCAACintegCom}`, fontSize: 12 },
                            { text: `Subtotal CAAC Integrante: ${data.form3_19.subtotalCAACintegCom}`, fontSize: 12 },
                            { text: `Comisión CAAC Integrante: ${data.form3_19.comCAACintegCom}`, fontSize: 12 },
                            { text: `Observaciones CAAC Integrante: ${data.form3_19.obsCAACintegCom}`, fontSize: 12 },
                            { text: `Cantidad Comisiones Departamentales: ${data.form3_19.cantComDepart}`, fontSize: 12 },
                            { text: `Subtotal Comisiones Departamentales: ${data.form3_19.subtotalComDepart}`, fontSize: 12 },
                            { text: `Comisión Comisiones Departamentales: ${data.form3_19.comComDepart}`, fontSize: 12 },
                            { text: `Observaciones Comisiones Departamentales: ${data.form3_19.obsComDepart}`, fontSize: 12 },
                            { text: `Cantidad Comisiones PEDPD: ${data.form3_19.cantComPEDPD}`, fontSize: 12 },
                            { text: `Subtotal Comisiones PEDPD: ${data.form3_19.subtotalComPEDPD}`, fontSize: 12 },
                            { text: `Comisión Comisiones PEDPD: ${data.form3_19.comComPEDPD}`, fontSize: 12 },
                            { text: `Observaciones Comisiones PEDPD: ${data.form3_19.obsComPEDPD}`, fontSize: 12 },
                            { text: `Cantidad Comisiones Parte Pos: ${data.form3_19.cantComPartPos}`, fontSize: 12 },
                            { text: `Subtotal Comisiones Parte Pos: ${data.form3_19.subtotalComPartPos}`, fontSize: 12 },
                            { text: `Comisión Comisiones Parte Pos: ${data.form3_19.comComPartPos}`, fontSize: 12 },
                            { text: `Observaciones Comisiones Parte Pos: ${data.form3_19.obsComPartPos}`, fontSize: 12 },
                            { text: `Cantidad Responsable Pos: ${data.form3_19.cantRespPos}`, fontSize: 12 },
                            { text: `Subtotal Responsable Pos: ${data.form3_19.subtotalRespPos}`, fontSize: 12 },
                            { text: `Comisión Responsable Pos: ${data.form3_19.comRespPos}`, fontSize: 12 },
                            { text: `Observaciones Responsable Pos: ${data.form3_19.obsRespPos}`, fontSize: 12 },
                            { text: `Cantidad Responsable Carrera: ${data.form3_19.cantRespCarrera}`, fontSize: 12 },
                            { text: `Subtotal Responsable Carrera: ${data.form3_19.subtotalRespCarrera}`, fontSize: 12 },
                            { text: `Comisión Responsable Carrera: ${data.form3_19.comRespCarrera}`, fontSize: 12 },
                            { text: `Observaciones Responsable Carrera: ${data.form3_19.obsRespCarrera}`, fontSize: 12 },
                            { text: `Cantidad Responsable Prod: ${data.form3_19.cantRespProd}`, fontSize: 12 },
                            { text: `Subtotal Responsable Prod: ${data.form3_19.subtotalRespProd}`, fontSize: 12 },
                            { text: `Comisión Responsable Prod: ${data.form3_19.comRespProd}`, fontSize: 12 },
                            { text: `Observaciones Responsable Prod: ${data.form3_19.obsRespProd}`, fontSize: 12 },
                            { text: `Cantidad Responsable Lab: ${data.form3_19.cantRespLab}`, fontSize: 12 },
                            { text: `Subtotal Responsable Lab: ${data.form3_19.subtotalRespLab}`, fontSize: 12 },
                            { text: `Comisión Responsable Lab: ${data.form3_19.comRespLab}`, fontSize: 12 },
                            { text: `Observaciones Responsable Lab: ${data.form3_19.obsRespLab}`, fontSize: 12 },
                            { text: `Cantidad Exam Prof: ${data.form3_19.cantExamProf}`, fontSize: 12 },
                            { text: `Subtotal Exam Prof: ${data.form3_19.subtotalExamProf}`, fontSize: 12 },
                            { text: `Comisión Exam Prof: ${data.form3_19.comExamProf}`, fontSize: 12 },
                            { text: `Observaciones Exam Prof: ${data.form3_19.obsExamProf}`, fontSize: 12 },
                            { text: `Cantidad Exam Académicos: ${data.form3_19.cantExamAcademicos}`, fontSize: 12 },
                            { text: `Subtotal Exam Académicos: ${data.form3_19.subtotalExamAcademicos}`, fontSize: 12 },
                            { text: `Comisión Exam Académicos: ${data.form3_19.comExamAcademicos}`, fontSize: 12 },
                            { text: `Observaciones Exam Académicos: ${data.form3_19.obsExamAcademicos}`, fontSize: 12 },
                            { text: `Cantidad PRODEP Form Resp: ${data.form3_19.cantPRODEPformResp}`, fontSize: 12 },
                            { text: `Subtotal PRODEP Form Resp: ${data.form3_19.subtotalPRODEPformResp}`, fontSize: 12 },
                            { text: `Comisión PRODEP Form Resp: ${data.form3_19.comPRODEPformResp}`, fontSize: 12 },
                            { text: `Observaciones PRODEP Form Resp: ${data.form3_19.obsPRODEPformResp}`, fontSize: 12 },
                            { text: `Cantidad PRODEP Form Integ: ${data.form3_19.cantPRODEPformInteg}`, fontSize: 12 },
                            { text: `Subtotal PRODEP Form Integ: ${data.form3_19.subtotalPRODEPformInteg}`, fontSize: 12 },
                            { text: `Comisión PRODEP Form Integ: ${data.form3_19.comPRODEPformInteg}`, fontSize: 12 },
                            { text: `Observaciones PRODEP Form Integ: ${data.form3_19.obsPRODEPformInteg}`, fontSize: 12 },
                            { text: `Cantidad PRODEP Encons Resp: ${data.form3_19.cantPRODEPenconsResp}`, fontSize: 12 },
                            { text: `Subtotal PRODEP Encons Resp: ${data.form3_19.subtotalPRODEPenconsResp}`, fontSize: 12 },
                            { text: `Comisión PRODEP Encons Resp: ${data.form3_19.comPRODEPenconsResp}`, fontSize: 12 },
                            { text: `Observaciones PRODEP Encons Resp: ${data.form3_19.obsPRODEPenconsResp}`, fontSize: 12 },
                            { text: `Cantidad PRODEP Encons Integ: ${data.form3_19.cantPRODEPenconsInteg}`, fontSize: 12 },
                            { text: `Subtotal PRODEP Encons Integ: ${data.form3_19.subtotalPRODEPenconsInteg}`, fontSize: 12 },
                            { text: `Comisión PRODEP Encons Integ: ${data.form3_19.comPRODEPenconsInteg}`, fontSize: 12 },
                            { text: `Observaciones PRODEP Encons Integ: ${data.form3_19.obsPRODEPenconsInteg}`, fontSize: 12 },
                            { text: `Cantidad PRODEP Cons Resp: ${data.form3_19.cantPRODEPconsResp}`, fontSize: 12 },
                            { text: `Subtotal PRODEP Cons Resp: ${data.form3_19.subtotalPRODEPconsResp}`, fontSize: 12 },
                            { text: `Comisión PRODEP Cons Resp: ${data.form3_19.comPRODEPconsResp}`, fontSize: 12 },
                            { text: `Observaciones PRODEP Cons Resp: ${data.form3_19.obsPRODEPconsResp}`, fontSize: 12 },
                            { text: `Cantidad PRODEP Cons Integ: ${data.form3_19.cantPRODEPconsInteg}`, fontSize: 12 },
                            { text: `Subtotal PRODEP Cons Integ: ${data.form3_19.subtotalPRODEPconsInteg}`, fontSize: 12 },
                            { text: `Comisión PRODEP Cons Integ: ${data.form3_19.comPRODEPconsInteg}`, fontSize: 12 },
                            { text: `Observaciones PRODEP Cons Integ: ${data.form3_19.obsPRODEPconsInteg}`, fontSize: 12 },
                            { text: `Comisión 3.19: ${data.form3_19.comision3_19}`, fontSize: 12 }
                        ]
                    };

                    pdfMake.createPdf(docDefinition).download('form3_19.pdf');
                } catch (error) {
                    console.error('Error fetching docente data:', error);
                }
            } else {
                console.error('No docente selected.');
            }
        }

        function sendCurrentPageToServer(currentPage) {
            fetch('/update-page-counter', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ page: currentPage }),
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Page counter updated:', data);
                })
                .catch(error => {
                    console.log('Error updating page counter:', error);
                });
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

</body>

</html>