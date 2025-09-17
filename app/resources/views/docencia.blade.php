@php
$userType = Auth::user()->user_type;
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
@endphp
<!DOCTYPE html>
<!--
 * nombre del programador: Jose Antonio Martínez del Toro
 * objetivo: Vista e implementacion del frontend de los formularios de convocatoria, actividades 1 y 2
 * fecha: 2024-06-10
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ $logo }}" type="image/png">
    <title>Evaluación docente</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <x-head-resources />
<style>
    #comisionIncisoB {
        margin-left: -10px;
    }


</style>
</head>
@if (Route::has('login'))
        @csrf
        @if (Auth::check())

            <nav class="nav flex-column"
                style="padding-top: 50px; height: 900px; background: linear-gradient(90deg, #afc7ce, #4281a4); width:330px;">
            <div class="nav-header" style="display: flex; align-items: center; padding-top: 50px;">                        
                        <li class="nav-item">
                            <a class="nav-link disabled enlaceSN" style="color: white;"href="#">
                                <i class="fa-solid fa-user"></i>{{ Auth::user()->email }}
                            </a>
                        </li>
                        <li style="list-style: none; margin-right: 20px;">
                            <a class="enlaceSN" href="{{ route('login') }}">
                                <i class="fas fa-power-off" style="font-size: 24px;color:white" name="cerrar_sesion"></i>
                            </a>
                        </li>
                    </div>

               </li>
           @endif
            </li>

            <li class="nav-item">
            @if(Auth::user()->user_type === 'dictaminador')
                <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('comision_dictaminadora') }}">Selección de Formatos</a>
            @elseif(Auth::user()->user_type === '')
                <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('secretaria') }}">Selección de Formatos</a>
            @endif
            </li>
            @if($userType !== 'docente')
            <li class="nav-item">
                <a class="nav-link active enlaceSN" href="{{ route('resumen') }}">Resumen</a>  
            </li>
            @else
                <li class=" nav-item">
                <a class="nav-link active enlaceSN" aria-current="page" style="width: 200px;"
                href="{{ route('rules') }}" title="Reglamento deacuerdo al artículo 10 de PEDPD"><i
                class="fas fa-book"></i>&nbspReglamento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active enlaceSN" style="width: 300px;font-size: 20px;" href="{{ route('welcome') }}"
                        title="Formato de Evaluación docente"><i class="fa-solid fa-align-justify"></i>&nbspEvaluación</a>
                </li>   
            @endif
            <ul class="actv3"><i class="fas fa-chalkboard-teacher"></i>&nbspCalidad en la docencia:
                <li><a href="#seccion3_1">3.1 Participación en actividades de diseño curricular</a></li>
                <li><a href="#seccion3_2">3.2 Calidad del desempeño docente evaluada por el alumnado</a></li>
                <li><a href="#seccion3_3">3.3 Publicaciones relacionadas con la docencia</a></li>
                <li><a href="#seccion3_4">3.4 Distinciones académicas recibidas por el docente</a></li>
                <li><a href="#seccion3_5">3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD
                        y
                        por CAAC</a></li>
                <li><a href="#seccion3_6">3.6 Capacitación y actualización pedagógica recibida</a></li>
                <li><a href="#seccion3_7">3.7 Cursos de actualización disciplinaria recibidos dentro de su área de
                        conocimiento</a>
                </li>
                <li><a href="#seccion3_8">3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de
                        educación, continua o de formación y capacitación docente</a></li>
                <li><a href="#seccion3_8_1">3.8.1 RSU </a></li>
                <li><a href="#seccion3_9">3.9 Trabajos dirigidos para la titulación de estudiantes</a></li>
                <li><a href="#seccion3_10">3.10 Tutorías a estudiantes</a></li>
                <li><a href="#seccion3_11">3.11 Asesoría a estudiantes</a></li>
                <li><a href="#seccion3_12">3.12 Publicaciones de investigación relacionadas con el contenido de los PE que
                        imparte el docente</a></li>
                <li><a href="#seccion3_13">3.13 Proyectos académicos de investigación</a></li>
                <li><a href="#seccion3_14">3.14 Participación como ponente en congresos o eventos académicos del
                        Área de Conocimiento o afines del docente</a></li>
                <li><a href="#seccion3_15">3.15 Registro de patentes y productos de investigación tecnológica y educativa</a>
                </li>
                <li><a href="#seccion3_16">3.16 Actividades de arbitraje, revisión, correción y edición </a></li>
                <li><a href="#seccion3_17">3.17 Proyectos académicos de extensión y difusión </a></li>
                <li><a href="#seccion3_18">3.18 Organización de congresos o eventos institucionales del área de conocimiento de
                        la ó el Docente </a></li>
                <li><a href="#seccion3_19">3.19 Participación en cuerpos colegiados</a></li>
            </ul>
        </nav>

        <body class="font-sans antialiased" style="margin-left: 300px;">
            <x-general-header />
            <button id="toggle-dark-mode" class="btn btn-secondary"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

            <div class="bg-gray-50 text-black/50">
                <div class="relative min-h-screen flex flex-col items-center justify-center">
                    <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                            <div class="flex lg:justify-center lg:col-start-2"></div>

                            <nav class="-mx-3 flex flex-1 justify-end"></nav>
                            <form id="form3_1" method="POST"
                                onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store31', 'form3_1');">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                <div>
                                    <!-- Actividad 3.1 Participación en actividades de diseño curricular -->
                                    <h4>Puntaje máximo
                                        <label class="bg-black text-white px-4" id="pMax60" for="">60</label>
                                    </h4>
                                </div>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Actividad</th>
                                            <th class="table-ajust2" scope="col" colspan="4"></th>
                                            <th class="table-ajust2 cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust2 cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                            <th class="table-ajust2" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5"><b>3. Calidad en la docencia</b></td>
                                            <td id="docencia"></td>
                                            <td id="actv3Comision"></td>
                                            <td></td>
                                        </tr>
                                        <!-- Sub-encabezados -->
                                        <tr>
                                            <td id="seccion3_1" class="p2" colspan="5">3.1 Participación en actividades de diseño curricular</td>
                                            <td id="score3_1"></td>
                                            <td colspan="6"></td>
                                        </tr>
                                        <tr>
                                            <th class="actividades">Incisos</th>
                                            <th class="actividades">Documento</th>
                                            <th class="actividades">Actividad</th>
                                            <th class="actividades">Puntaje</th>
                                            <th class="actividades">Cantidad</th>
                                            <th class="actividades">Subtotal</th>

                                        </tr>
                                                        <!-- Contenido -->
                                        <tr>
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

                                            <td class="elabInput"><input id="elaboracion" name="elaboracion" type="number"
                                                    oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion') }}"></td>
                                            <td><label id="elaboracionSubTotal1" for="" type="text"></label></td>
                                            <td class="comision actv"><input id="comisionIncisoA" placeholder="0"
                                                    for="" oninput="onActv3Comision()"></input></td>
                                            <td><input id="obs3_1_1" class="table-header" type="text"></td>
                                          </tr>   
                                            <tr>
                                                <td>b)</td>
                                                <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                                <td><label class="form3_1LabelDoc" for="">Colaboración en la Comisión para la elaboración del documento</label></td>
                                                <td><span id="puntaje40"><b>40</b></span></td>
                                                <td class="elabInput"><input id="elaboracion2" name="elaboracion2" type="number"
                                                        oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion2') }}"></td>
                                                <td><label id="elaboracionSubTotal2" for="" type="text"></label>
                                                </td>
                                                <td class="comision actv"><input id="comisionIncisoB"
                                                        placeholder="0" for=""
                                                        oninput="onActv3Comision()"></input></td>
                                                <td><input id="obs3_1_2" class="table-header" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>c)</td>
                                                <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                                <td><label class="form3_1LabelDoc">Elaboración de contenidos mínimos</label></td>
                                                <td><label id="puntaje10" for=""><b>10</b></label></td>
                                                <td class="elabInput"><input id="elaboracion3" name="elaboracion3" type="number"
                                                        oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion3') }}"></td>
                                                <td><label id="elaboracionSubTotal3" for="" type="text"></label>
                                                </td>
                                                <td class="comision actv"><input id="comisionIncisoC"
                                                        placeholder="0" for=""
                                                        oninput="onActv3Comision()"></input></td>
                                                <td><input id="obs3_1_3" class="table-header" type="text"></td>
                                            </tr>

                                            <tr>
                                            <td>d)</td>
                                            <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                            <td><label class="form3_1LabelDoc">Elaboración de programas de asignatura</label></td>
                                            <td><label id="puntaje20" for=""><b>20</b></label></td>
                                            <td class="elabInput"><input id="elaboracion4" name="elaboracion4" type="number"
                                                    oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion4') }}"></td>
                                            <td><label id="elaboracionSubTotal4" for="" type="text"></label>
                                            </td>
                                            <td class="comision actv"><input id="comisionIncisoD"
                                                    placeholder="0" for=""
                                                    oninput="onActv3Comision()"></input></td>
                                            <td><input id="obs3_1_4" class="table-header" type="text"></td>
                                            </tr>
                                            <tr>
                                            <td>e)</td>
                                            <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                            <td><label class="form3_1LabelDoc">Actualización de programas de asignatura</label></td>
                                            <td><label id="p10" for=""><b>10</b></label></td>
                                            <td class="elabInput"><input id="elaboracion5" name="elaboracion5" type="number"
                                                    oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion5') }}"></td>
                                            <td><label id="elaboracionSubTotal5" for="" type="text"></label>
                                            </td>
                                            <td class="comision actv"><input id="comisionIncisoE"
                                                    placeholder="0" for=""
                                                    oninput="onActv3Comision()"></input></td>
                                            <td><input id="obs3_1_5" class="table-header" type="text"></td>
                                            </tr>
                                    </tbody>
                                </table>

                                <!--Tabla informativa Acreditacion Actividad 3.1-->
                                <table>
                                    <thead>
                                        <tr><br>

                                            <th class="acreditacion" scope="col">Acreditacion: </th>

                                            <th class="descripcion"><b>H.CGU</b> puntos a,b y e; <b>CAAC</b> puntos d y e</th>
                                            <th> <button id="btn3_1" type="submit" class="btn custom-btn printButtonClass">Enviar</th>
                                        </tr>


                                    </thead>
                                </table>
                            </form>

                            <form id="form3_2" method="POST"
                                onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store32', 'form3_2');">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                @csrf
                                <div>
                                    <!-- Actividad 3.2 Calidad del desempeño docente evaluada por el alumnado -->
                                    <h4>Puntaje máximo
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
                                            <th class="table-ajust" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <td id="seccion3_2" style="height: 80px; width: 200px;">3.2 Calidad del desempeño
                                                docente
                                                evaluada por el alumnado
                                            </td>
                                            <td>Puntaje</td>
                                            <td style="text-align:left;">Cantidad</td>
                                            <td id="score3_2" for="">0</td>
                                            <td id="comision3_2">0</td>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <!--prom90-100-->
                                        <tr>
                                            <td class="ranges">
                                                <center>Promedio 90-100</center>
                                            </td>
                                            <td id="ran1"><b>50</b></td>
                                            <td class="elabInput"><input id="r1" type="number" placeholder="0"
                                                    oninput="onActv3Puntaje()" value="{{ oldValueOrDefault('r1') }}"></td>
                                            <td id="cant1">0</td>
                                            <td><input id="prom90_100" type="value" placeholder="0"
                                                    oninput="onActv3_2Comision()"></td>
                                            <td><input id="obs3_2_1" class="table-header" type="text"></td>
                                        </tr>
                                        <!--prom80-90-->
                                        <tr>
                                            <td class="ranges">
                                                <center>Promedio 80-90</center>
                                            </td>
                                            <td id="ran2"><b>40</b></td>
                                            <td class="elabInput"><input id="r2" type="number" placeholder="0"
                                                    oninput="onActv3Puntaje()" value="{{ oldValueOrDefault('r2') }}"></td>
                                            <td id="cant2">0</td>
                                            <td><input id="prom80_90" placeholder="0" type="value"
                                                    oninput="onActv3_2Comision()"></td>
                                            <td><input id="obs3_2_2" class="table-header" type="text"></td>
                                        </tr>
                                        <!--prom70-80-->
                                        <tr>
                                            <td class="ranges">
                                                <center>Promedio 70-80</center>
                                            </td>
                                            <td id="ran3"><b>30</b></td>
                                            <td class="elabInput"><input id="r3" type="number" placeholder="0"
                                                    oninput="onActv3Puntaje()" value="{{ oldValueOrDefault('r3') }}"></td>
                                            <td id="cant3">0</td>
                                            <td><input id="prom70_80" placeholder="0" type="value"
                                                    oninput="onActv3_2Comision()"></td>
                                            <td><input id="obs3_2_3" class="table-header" type="text"></td>
                                        </tr>
                                    </thead>
                                    </table>
                                    <!--Tabla informativa Acreditacion Actividad 3.2-->
                                    <table>
                                        <thead>
                                            <tr><br>
                                                <th class="acreditacion" scope="col">Acreditacion: </th>

                                                <th class="descripcionDDIE"><b>DDIE</b>
                                                <th> <button id="btn3_2" type="submit" class="btn custom-btn printButtonClass">Enviar</th>
                                            </tr>

                                        </thead>
                                    </table>
                            </form>

                            <form id="form3_3" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store33', 'form3_3');">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                @csrf
                                <div>
                                    <!-- Actividad 3.3 Publicaciones relacionadas con la docencia -->
                                    <h4>Puntaje máximo
                                        <label class="bg-black text-white px-4 mt-3" for="">100</label>
                                    </h4>
                                </div>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                            </th>
                                            <th class="table-ajust" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <thead>
                                            <tr>
                                                <td id="seccion3_3">3.3 Publicaciones relacionadas con la docencia</td>
                                                <td>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
                                                <td>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
                                                <td>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
                                                <td>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
                                                <td id="score3_3" for="">0</td>
                                                <td id="comision3_3">0</td>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <td colspan=6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="incisos">Incisos</td>
                                                <td class="obra">Obra</td>
                                                <td>Actividad</td>
                                                <td>Puntaje</td>
                                                <td id="cantidadform3_3">Cantidad</td>
                                                <td>SubTotal</td>

                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <td>a)</td>
                                                <td>Libro de texto con editorial de reconocido prestigio</td>
                                                <td>Autor(a)</td>
                                                <td id="p100">
                                                    <center><b>100</b></center>
                                                </td>
                                                <td class="elabInput"><input id="rc1" type="number" oninput="onActv3SubTotal3()" value="{{ oldValueOrDefault('rc1') }}">
                                                </td>
                                                <td id="stotal1"></td>
                                                <td class="comision actv"><input id="comIncisoA" placeholder="0" for=""
                                                        oninput="onActv3Comision3()"></input></td>
                                                <td><input id="obs3_3_1" class="table-header" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>b)</td>
                                                <td>1. Paquete didáctico, 2. Manual de operaciones</td>
                                                <td>Autor(a)</td>
                                                <td id="p50">
                                                    <center><b>50</b></center>
                                                </td>
                                                <td class="elabInput"><input id="rc2" type="number" 
                                                        oninput="onActv3SubTotal3()" value="{{ oldValueOrDefault('rc2') }}">
                                                </td>
                                                <td id="stotal2"></td>
                                                <td class="comision actv"><input id="comIncisoB" placeholder="0" for=""
                                                        oninput="onActv3Comision3()"></input></td>
                                                <td><input id="obs3_3_2" class="table-header" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>c)</td>
                                                <td>1. Capítulo de libro, 2. Elaboración de Manuales de laboratorio o
                                                    instructivos, 3. Diseño
                                                    y
                                                    construcción de equipo de laboratorio, 4. Elaboración de material
                                                    audiovisual, 5.
                                                    Elaboración
                                                    de
                                                    software educativo, 6. Notas de curso, 7. Antología comentada, 8.
                                                    Monografía.</td>
                                                <td>Autor(a)</td>
                                                <td id="p30">
                                                    <center><b>30</b></center>
                                                </td>
                                                <td class="elabInput"><input id="rc3" type="number"
                                                        oninput="onActv3SubTotal3()" value="{{ oldValueOrDefault('rc3') }}">
                                                </td>
                                                <td id="stotal3"></td>
                                                <td class="comision actv"><input id="comIncisoC" placeholder="0" for=""
                                                        oninput="onActv3Comision3()"></input></td>
                                                <td><input id="obs3_3_3" class="table-header" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>d)</td>
                                                <td>1. Traducción de libro, 2.Traducción de material de apoyo didáctico,
                                                    3. Traducciones
                                                    publicadas de artículos.</td>
                                                <td>Autor(a)</td>
                                                <td id="p25">
                                                    <center><b>25</b></center>
                                                </td>
                                                <td class="elabInput"><input id="rc4" type="number"
                                                        oninput="onActv3SubTotal3()" value="{{ oldValueOrDefault('rc4') }}">
                                                </td>
                                                <td id="stotal4"></td>
                                                <td class="comision actv"><input id="comIncisoD" placeholder="0" for=""
                                                        oninput="onActv3Comision3()"></input></td>
                                                <td><input id="obs3_3_4" class="table-header" type="text"></td>
                                            </tr>
                                        </thead>
                                    </tbody>
                                </table>
                                <!--Tabla informativa Acreditacion Actividad 3.3-->
                                <table>
                                    <thead>
                                        <tr><br>
                                            <th class="acreditacion" scope="col">Acreditacion: </th>

                                            <th class="descripcionCAAC"><b>CAAC, Instancia que la otorga</b></th>
                                            <th><button id="btn3_3" type="submit" class="btn custom-btn printButtonClass">Enviar</th>
                                        </tr>
                                    </thead>
                                </table>
                                </form>
    <form id="form3_4" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store34', 'form3_4');">
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
        <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
        @csrf
        <div>
            <!-- 3.4 Distinciones académicas recibidas por el docente  -->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">60</label>
            </h4>
        </div>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">Actividad</th>
                    <th class="table-ajust" scope="col"></th>
                    <th class="table-ajust" scope="col"></th>
                    <th class="table-ajust" scope="col"></th>
                    <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                    <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                    <th class="table-ajust" scope="col">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th id="seccion3_4" colspan="2" class="punto3_4" scope="col" style="padding:30px;">3.4 Distinciones
                        académicas recibidas por el docente</th>
                    <td class="punto3_4">Puntaje</td>
                    <td class="punto3_4">Cantidad</td>
                    <td id="score3_4">0</td>
                    <td id="comision3_4">0</td>
                </tr>
                <tr>
                    <td class="punto3_4">a)</td>
                    <td>Internacional</td>
                    <td id="p60"><b>60</b></td>
                    <td><input type="number" id="cantInternacional" placeholder="0" oninput="onActv3SubTotal3_4()" value="{{ oldValueOrDefault('cantInternacional') }}"></td>
                    <td id="cantInternacional2"></td>
                    <td><input type="number" id="comInternacional" placeholder="0" oninput="onActv3Comision3_4()"></td>
                    <td><input id="obs3_4_1" class="table-header" type="text"></td>
                </tr>
                <tr>
                    <td class="punto3_4">b)</td>
                    <td>Nacional</td>
                    <td id="p30Nac"><b>30</b></td>
                    <td><input type="number" id="cantNacional" placeholder="0" oninput="onActv3SubTotal3_4()" value="{{ oldValueOrDefault('cantNacional') }}"></td>
                    <td id="cantNacional2"></td>
                    <td><input type="number" id="comNacional" placeholder="0" oninput="onActv3Comision3_4()"></td>
                    <td><input id="obs3_4_2" class="table-header" type="text"></td>
                </tr>
                <tr>
                    <td class="punto3_4">c)</td>
                    <td>Regional o estatal</td>
                    <td id="p20"><b>20</b></td>
                    <td><input type="number" id="cantidadRegional" placeholder="0" oninput="onActv3SubTotal3_4()" value="{{ oldValueOrDefault('cantidadRegional') }}"></td>
                    <td id="cantidadRegional2"></td>
                    <td><input type="number" id="comRegional" placeholder="0" oninput="onActv3Comision3_4()"></td>
                    <td><input id="obs3_4_3" class="table-header" type="text"></td>
                </tr>
                <tr>
                    <td class="punto3_4">d)</td>
                    <td>Preparación de grupos de alumnado para olimpiadas competencias académicas o exámenes generales.</td>
                    <td id="p30Prep"><b>30</b></td>
                    <td><input type="number" id="cantPreparacion" placeholder="0" oninput="onActv3SubTotal3_4()" value="{{ oldValueOrDefault('cantPreparacion') }}"></td>
                    <td id="cantPreparacion2"></td>
                    <td><input type="number" id="comPreparacion" placeholder="0" oninput="onActv3Comision3_4()"></td>
                    <td><input id="obs3_4_4" class="table-header" type="text"></td>
                </tr>
            </tbody>
        </table>
        <!--Tabla informativa Acreditacion Actividad 3.4-->
        <table>
            <thead>
                <tr><br>
                    <th class="acreditacion" scope="col">Acreditacion: </th>
                    <th class="descripcionCAAC"><b>CAAC, Instancia que la otorga</b></th>
                    <th><button id="btn3_4" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                </tr>
            </thead>
        </table>
    </form>

                                <form id="form3_5" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store35', 'form3_5');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                    <div>
                                        <!-- 3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD y por CAAC  -->
                                        <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3" for="">75</label>
                                        </h4>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Actividad</th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                <th class="table-ajust" scope="col">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_5" colspan=2 class="punto3_5" scope=col
                                                        style="padding:30px;">3.5
                                                        Asistencia, puntualidad y
                                                        permanencia en el desempeño docente, evaluada por el JD y por CAAC
                                                    </th>
                                                    <td class="punto3_5">Puntaje</td>
                                                    <td class="punto3_5">Cantidad</td>
                                                    <td id="score3_5" for="">0</td>
                                                    <td id="comision3_5">0</td>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <td class="punto3_5">a)</td>
                                                <td>Evaluado por la persona titular de DA</td>
                                                <td id="p35"><b>35</b></td>
                                                <td><input type="number" id="cantDA"
                                                        oninput="onActv3SubTotal3_5()" value="{{ oldValueOrDefault('cantDA') }}"></td>
                                                <td id="cantDA2"></td>
                                                <td><input type="value" id="comDA" placeholder="0"
                                                        oninput="onActv3Comision3_5()"></td>
                                                <td><input id="obs3_5_1" class="table-header" type="text"></td>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td class="punto3_5">b)</td>
                                                    <td>Evaluado por CAAC</td>
                                                    <td id="pCAAC40"><b>40</b></td>
                                                    <td><input type="number" id="cantCAAC"
                                                            oninput="onActv3SubTotal3_5()" value="{{ oldValueOrDefault('cantCAAC') }}"></td>
                                                    <td id="cantCAAC2""></td>
                                            <td><input type=" value" id="comNCAA" placeholder="0"
                                                        oninput="onActv3Comision3_5()"></td>
                                                    <td><input id="obs3_5_2" class="table-header" type="text"></td>
                                                </tr>
                                            </thead>
                                        </tbody>
                                    </table>
                                    <!--Tabla informativa Acreditacion Actividad 3.5-->
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="acreditacion" scope="col">Acreditacion: </th>

                                                <th class="descripcion"><b>JDA y CAAC</b>
                                                <th><button id="btn3_5" type="submit" class="btn custom-btn printButtonClass">Enviar</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </form>
                                <form id="form3_6" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store36', 'form3_6');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                    <div>
                                        <!-- 3.6 Capacitación y actualización pedagógica recibida  -->
                                        <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                        </h4>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Actividad</th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                <th class="table-ajust" scope="col">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_6" colspan=1 class="punto3_6" scope=col
                                                        style="padding:30px;">3.6
                                                        Capacitación y
                                                        actualización
                                                        pedagógica recibida </th>
                                                    <td class="punto3_6">Factor</td>
                                                    <td class="punto3_6">Horas</td>
                                                    <td id="score3_6" for="">0</td>
                                                    <td id="comision3_6">0</td>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>0.5 por cada hora</td>
                                                    <td id="pMedio">0.5</td>
                                                    <td><input type="number" id="puntaje3_6"
                                                            oninput="onActv3SubTotal3_6()" value="{{ oldValueOrDefault('puntaje3_6') }}"></td>
                                                    <td id="puntajeHoras3_6"></td>
                                                    <td><input type="text" placeholder="0" id="comisionDict3_6"
                                                            oninput="onActv3Comision3_6()">
                                                    </td>
                                                    <td><input id="obs3_6" id="obs3_6" class="table-header" type="text">
                                                    </td>
                                                </tr>
                                            </thead>
                                            <!--Tabla informativa Acreditacion Actividad 3.6-->
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="acreditacion" scope="col">Acreditacion: </th>

                                                        <th class="descripcion"><b>DDIE</b>

                                                        <th><button id="btn3_6" type="submit" class="btn custom-btn printButtonClass">Enviar
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </tbody>
                                    </table>
                                </form>
                                <form id="form3_7" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store37', 'form3_7');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                    <div>
                                        <!-- 3.7 Cursos de actualización disciplinaria recibidos dentro de su área de conocimiento  -->
                                        <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                        </h4>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Actividad</th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                <th class="table-ajust" scope="col">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_7" colspan=1 class="punto3_7" scope=col
                                                        style="padding:30px;">3.7
                                                        Cursos de actualización
                                                        disciplinaria recibidos dentro de su área de conocimiento </th>
                                                    <td class="punto3_7">Factor</td>
                                                    <td class="punto3_7">Horas</td>
                                                    <td id="score3_7" for="">0</td>
                                                    <td id="comision3_7">0</td>

                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>0.5 por cada hora</td>
                                                    <td id="pMedio2">0.5</td>
                                                    <td><input type="number" placeholder="0" id="puntaje3_7"
                                                            oninput="onActv3SubTotal3_7()" value="{{ oldValueOrDefault('puntaje3_7') }}"></td>
                                                    <td id="puntajeHoras3_7"></td>
                                                    <td><input type="text" placeholder="0" id="comisionDict3_7"
                                                            oninput="onActv3Comision3_7()">
                                                    </td>
                                                    <td><input id="obs3_7" class="table-header" type="text"></td>
                                                </tr>
                                            </thead>
                                            <!--Tabla informativa Acreditacion Actividad 3.7-->
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="acreditacion" scope="col">Acreditacion: </th>
                                                        <th class="descripcion"><b>JD,CAAC, instancia que organiza</b></th>
                                                        <th><button id="btn3_7" type="submit" class="btn custom-btn printButtonClass">Enviar
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </tbody>
                                    </table>
                                </form>
                                <form id="form3_8" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store38', 'form3_8');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                    <div>
                                        <!--3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de educación, continua o de formación y capacitación docente-->
                                        <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                        </h4>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Actividad</th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                <th class="table-ajust" scope="col">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_8" colspan=1 class="punto3_8" scope=col
                                                        style="padding:30px;">3.8
                                                        Impartición de cursos,
                                                        diplomados, seminarios, talleres extracurriculares, de educación,
                                                        continua o de formación y
                                                        capacitación docente </th>
                                                    <td class="punto3_8">Factor</td>
                                                    <td class="punto3_8">Horas</td>
                                                    <td id="score3_8" for="">0</td>
                                                    <td id="comision3_8">0</td>

                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>1 por cada hora</td>
                                                    <td id="p3_8">1</td>
                                                    <td><input type="number" placeholder="0" id="puntaje3_8"
                                                            oninput="onActv3SubTotal3_8()" value="{{ oldValueOrDefault('puntaje3_8') }}"></td>
                                                    <td id="puntajeHoras3_8"></td>
                                                    <td><input type="text" placeholder="0" id="comisionDict3_8"
                                                            oninput="onActv3Comision3_8()">
                                                    </td>
                                                    <td><input class="table-header" id="obs3_8" type="text"></td>
                                                </tr>
                                            </thead>
                                            <!--Tabla informativa Acreditacion Actividad 3.8-->
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="acreditacion" scope="col">Acreditacion: </th>

                                                        <th class="descripcion"><b>*JD,CAAC, DDCE, DDIE, SA,DIIP, según
                                                                corresponda. Cuando sea en
                                                                instituciones externas, presentar constancia de la
                                                                institución y el convenio acuerdo con
                                                                la
                                                                UABCS.</b> 
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </tbody>
                                    </table>
                                    <button id="btn3_8" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                                </form>
                                <form id="form3_8_1" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store381', 'form3_8_1');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                    <div>
                                        <!--3.8.1 RSU -->
                                        <h4>Puntaje máximo 
                                        @if($userType != '') <!-- fetch puntajeMaximo form3_8_1 -->
                                            <span class="bg-black text-white px-4 mt-3" id="puntajeMaximo" for="">40</span>
                                        @endif
                                        </h4>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Actividad</th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                <th class="table-ajust" scope="col">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_8_1" colspan=1 class="punto3_8_1" scope=col style="padding:30px;">3.8.1 RSU</th>
                                                    <td class="punto3_8_1">Factor</td>
                                                    <td class="punto3_8_1">Horas</td>
                                                    <td id="score3_8_1" for="">0</td>
                                                    <td id="comision3_8_1">0</td>

                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>1 por cada hora</td>
                                                    <td id="p3_8_1">1</td>
                                                    <td><input type="number" placeholder="0" id="puntaje3_8_1" oninput="onActv3SubTotal3_8_1()"
                                                            value="{{ oldValueOrDefault('puntaje3_8_1') }}"></td>
                                                    <td id="puntajeHoras3_8_1"></td>
                                                    <td><input type="text" placeholder="0" id="comisionDict3_8_1" oninput="onActv3Comision3_8_1()">
                                                    </td>
                                                    <td><input class="table-header" id="obs3_8_1" type="text"></td>
                                                </tr>
                                            </thead>
                                            <!--Tabla informativa Acreditacion Actividad 3.8-->
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="acreditacion" scope="col">Acreditacion: </th>

                                                        <th class="descripcion"><b>*RSU</b> </th>
                                                        <th><button id="btn3_8_1" type="submit" class="btn custom-btn printButtonClass">Enviar
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </tbody>
                                    </table>
                                </form>
                                <form id="form3_9" method="POST"onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store39', 'form3_9');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                    <div>
                                        <!--3.9 Trabajos dirigidos para la titulación de estudiantes-->
                                        <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3" for="">200</label>
                                        </h4>
                                    </div>
                                    <table class="table table-sm tutorias">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h3></h3>
                                                </th>
                                                <th>
                                                    <h3></h3>
                                                </th>
                                                <th>
                                                    <h3></h3>
                                                </th>
                                                <th>
                                                    <h3></h3>
                                                </th>
                                                <th>
                                                    <h3></h3>
                                                </th>
                                                <th>
                                                    <h3>Tutorias</h3>
                                                </th>

                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th scope="col">Actividad</th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th id="seccion3_9" scope="col" class="p3_9" colspan=9>3.9 Trabajos dirigidos
                                                    para la
                                                    titulación de estudiantes
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th id="score3_9">0</th>
                                                <th id="comision3_9">0</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th class="acreditacion">Incisos</th>
                                                <th class="acreditacion">Actividad</th>
                                                <th class="acreditacion">Obra</th>
                                                <th class="acreditacion">Nivel</th>
                                                <th class="acreditacion">Puntaje</th>
                                                <th class="acreditacion">Cantidad</th>
                                                <th class="acreditacion">Subtotal</th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="acreditacion">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>a)</td>
                                                <td>Revisión de</td>
                                                <td>Tesis</td>
                                                <td>Doctorado</td>
                                                <td id="puntajeTutorias20_1">20</td>
                                                <td><input type="number" id="puntaje3_9_1"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_1') }}"></td>
                                                <td id="tutorias1">0</td>
                                                <td><input type="value" id="tutoriasComision1" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_1" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>b)</td>
                                                <td>Proyecto de</td>
                                                <td>Tesis</td>
                                                <td>Maestría</td>
                                                <td id="puntajeTutorias15_1">15</td>
                                                <td><input type="number" id="puntaje3_9_2"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_2') }}"></td>
                                                <td id="tutorias2">0</td>
                                                <td><input type="value" id="tutoriasComision2" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_2" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>c)</td>
                                                <td>Proyecto de</td>
                                                <td>Tesis y otras</td>
                                                <td>TSU, Lic y especialidad</td>
                                                <td id="puntajeTutorias10_1">10</td>
                                                <td><input type="number" id="puntaje3_9_3"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_3') }}"></td>
                                                <td id="tutorias3">0</td>
                                                <td><input type="value" id="tutoriasComision3" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_3" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>d)</td>
                                                <td>Dirección trabajo en realización</td>
                                                <td>Tesis</td>
                                                <td>Doctorado</td>
                                                <td id="puntajeTutorias55">55</td>
                                                <td><input type="number" id="puntaje3_9_4"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_4') }}"></td>
                                                <td id="tutorias4">0</td>
                                                <td><input type="value" id="tutoriasComision4" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_4" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>e)</td>
                                                <td>Dirección trabajo en realización</td>
                                                <td>Tesis</td>
                                                <td>Maestria</td>
                                                <td id="puntajeTutorias45">45</td>
                                                <td><input type="number" id="puntaje3_9_5"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_5') }}"></td>
                                                <td id="tutorias5">0</td>
                                                <td><input type="value" id="tutoriasComision5" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_5" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>f)</td>
                                                <td>Dirección trabajo en realización</td>
                                                <td>Tesis y otras</td>
                                                <td>TSU, Lic y especialidad</td>
                                                <td id="puntajeTutorias35">35</td>
                                                <td><input type="number" id="puntaje3_9_6"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_6') }}"></td>
                                                <td id="tutorias6">0</td>
                                                <td><input type="value" id="tutoriasComision6" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_6" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>g)</td>
                                                <td>Dirección trabajo terminado</td>
                                                <td>Tesis</td>
                                                <td>Doctorado</td>
                                                <td id="puntajeTutorias70">70</td>
                                                <td><input type="number" id="puntaje3_9_7"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_7') }}"></td>
                                                <td id="tutorias7">0</td>
                                                <td><input type="value" id="tutoriasComision7" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_7" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>h)</td>
                                                <td>Dirección trabajo terminado</td>
                                                <td>Tesis</td>
                                                <td>Maestría</td>
                                                <td id="puntajeTutorias60">60</td>
                                                <td><input type="number" id="puntaje3_9_8"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_8') }}"></td>
                                                <td id="tutorias8">0</td>
                                                <td><input type="value" id="tutoriasComision8" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_8" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>i)</td>
                                                <td>Dirección trabajo terminado</td>
                                                <td>Tesis y otras</td>
                                                <td>TSU, Lic y especialidad</td>
                                                <td id="puntajeTutorias50">50</td>
                                                <td><input type="number" id="puntaje3_9_9"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_9') }}"></td>
                                                <td id="tutorias9">0</td>
                                                <td><input type="value" id="tutoriasComision9" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_9" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>j)</td>
                                                <td>Revisión de trabajo terminado</td>
                                                <td>Tesis</td>
                                                <td>Doctorado</td>
                                                <td id="puntajeTutorias30_1">30</td>
                                                <td><input type="number" id="puntaje3_9_10" 
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_10') }}"></td>
                                                <td id="tutorias10">0</td>
                                                <td><input type="value" id="tutoriasComision10" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_10" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>k)</td>
                                                <td>Revisión de trabajo terminado</td>
                                                <td>Tesis</td>
                                                <td>Maestría</td>
                                                <td id="puntajeTutorias20_2">50</td>
                                                <td><input type="number" id="puntaje3_9_11"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_11') }}"></td>
                                                <td id="tutorias11">0</td>
                                                <td><input type="value" id="tutoriasComision11" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_11" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>l)</td>
                                                <td>Revisión de trabajo terminado</td>
                                                <td>Tesis y otras</td>
                                                <td>TSU, Lic y especialidad</td>
                                                <td id="puntajeTutorias15_2">15</td>
                                                <td><input type="number" id="puntaje3_9_12"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_12') }}"></td>
                                                <td id="tutorias12">0</td>
                                                <td><input type="value" id="tutoriasComision12" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_12" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>m)</td>
                                                <td>Sinodalía</td>
                                                <td>Examen</td>
                                                <td>Doctorado</td>
                                                <td id="puntajeTutorias30_2">30</td>
                                                <td><input type="number" id="puntaje3_9_13"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_13') }}"></td>
                                                <td id="tutorias13">0</td>
                                                <td><input type="value" id="tutoriasComision13" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_13" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>n)</td>
                                                <td>Sinodalía</td>
                                                <td>Examen</td>
                                                <td>Maestría</td>
                                                <td id="puntajeTutorias20_3">15</td>
                                                <td><input type="number" id="puntaje3_9_14"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_14') }}"></td>
                                                <td id="tutorias14">0</td>
                                                <td><input type="value" id="tutoriasComision14" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_14" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>o)</td>
                                                <td>Sinodalía</td>
                                                <td>Examen</td>
                                                <td>TSU, Lic y especialidad</td>
                                                <td id="puntajeTutorias15_3">15</td>
                                                <td><input type="number" id="puntaje3_9_15"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_15') }}"></td>
                                                <td id="tutorias15">0</td>
                                                <td><input type="value" id="tutoriasComision15" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_15" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>p)</td>
                                                <td>Distinciones</td>
                                                <td></td>
                                                <td>Doctorado</td>
                                                <td id="puntajeTutorias15_4">15</td>
                                                <td><input type="number" id="puntaje3_9_16"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_16') }}"></td>
                                                <td id="tutorias16">0</td>
                                                <td><input type="value" id="tutoriasComision16" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_16" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td>q)</td>
                                                <td>Distinciones</td>
                                                <td></td>
                                                <td>Maestría</td>
                                                <td id="puntajeTutorias10_2">10</td>
                                                <td><input type="number" id="puntaje3_9_17"
                                                        oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_17') }}"></td>
                                                <td id="tutorias17">0</td>
                                                <td><input type="value" id="tutoriasComision17" placeholder="0"
                                                        oninput="onActv3Comision3_9()">
                                                </td>
                                                <td><input class="table-header" id="obs3_9_17" type="text"></td>
                                            </tr>
                                            <!--Tabla informativa Acreditacion Actividad 3.9-->
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="acreditacion" scope="col">Acreditacion: </th>

                                                        <th class="descripcion"><b>DSE para pregrado, DIIP para posgrado</b>
                                                        </th>
                                                        <th><button id="btn3_9" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </tbody>
                                    </table>
                                </form>
                                <form id="form3_10" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store310', 'form3_10');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                <!--3.10 Trabajos dirigidos para la titulación de estudiantes-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">115</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_10" class="acreditacion" colspan=2>3.10 Tutorías a estudiantes</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">cantidad</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th id="score3_10">0</th>
                                            <th id="comision3_10">0</th>
                                            <th class="acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <thead>
                                            <tr>
                                                <!--Tutorias a estudantes 3_10 individuales, grupales -->
                                                <td>a)</td>
                                                <td>Por alumno(a) por semestre, grupales</td>
                                                <td id="puntajeGrupales">3</td>
                                                <td><input type="number" id="grupalesCant" oninput="onActv3SubTotal3_10()"
                                                        value="{{ oldValueOrDefault('grupalesCant') }}">
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td id="evaluarGrupales"></td>
                                                <td><input type="value" id="comisionGrupal" oninput="onActv3Comision3_10()"
                                                        placeholder="0">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsGrupal"></td>
                                            </tr>
                                            <tr>
                                                <td>b)</td>
                                                <td>Por alumno(a) por semestre, individuales</td>
                                                <td id="puntajeIndividual">6</td>
                                                <td><input type="number" id="individualCant" oninput="onActv3SubTotal3_10()"
                                                        value="{{ oldValueOrDefault('individualCant') }}">
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td id="evaluarIndividual"></td>
                                                <td><input type="value" id="comisionIndividual" oninput="onActv3Comision3_10()"
                                                        placeholder="0">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsIndividual"></td>
                                            </tr>
                                        </thead>
                                        </tbody> 
                                        </table>
                                        <!--Tabla informativa Acreditacion Actividad 3.10-->
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="acreditacion" scope="col">Acreditacion: </th>

                                                    <th class="descripcion"><b>DDIE</b> </th>

                                                    <th><button id="btn3_10" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>

                                                </tr>
                                            </thead>

                                        </table>
                                </form>
                                <form id="form3_11" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store311', 'form3_11');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                <!--3.11 Trabajos dirigidos para la titulación de estudiantes-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">95</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_11" class="acreditacion" colspan=5>3.11 Asesoría a estudiantes</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th id="score3_11">0</th>
                                            <th id="comision3_11">0</th>
                                            <th class="acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion">Incisos</th>
                                            <th class="acreditacion">Documento</th>
                                            <th class="acreditacion">Actividad</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">Cantidad</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="acreditacion">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--3_11 Asesoria a estudiantes incisos-->
                                        <tr>
                                            <td>a)</td>
                                            <td>Asesorías académicas</td>
                                            <td>Por alumno(a), por semestre</td>
                                            <td id="academica">5</td>
                                            <td><input type="number" id="cantAsesoria"
                                                    oninput="onActv3SubTotal3_11()" value="{{ oldValueOrDefault('cantAsesoria') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalAsesoria"></td>
                                            <td><input type="value" id="comisionAsesoria" placeholder="0"
                                                    oninput="onActv3Comision3_11()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsAsesoria"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Servicio social*</td>
                                            <td>Por alumno(a), por semestre</td>
                                            <td id="servicio">20</td>
                                            <td><input type="number" placeholder="0" id="cantServicio"
                                                    oninput="onActv3SubTotal3_11()" value="{{ oldValueOrDefault('cantServicio') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalServicio"></td>
                                            <td><input type="value" id="comisionServicio" placeholder="0"
                                                    oninput="onActv3Comision3_11()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsServicio"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Prácticas profesionales</td>
                                            <td>Por alumno(a), por semestre</td>
                                            <td id="practicas">20</td>
                                            <td><input type="number" placeholder="0" id="cantPracticas"
                                                    oninput="onActv3SubTotal3_11()" value="{{ oldValueOrDefault('cantPracticas') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalPracticas"></td>
                                            <td><input type="value" id="comisionPracticas" placeholder="0"
                                                    oninput="onActv3Comision3_11()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsPracticas"></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!--Tabla informativa Acreditacion Actividad 3.11-->
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" scope="col">Acreditacion: </th>

                                            <th class="descripcion"><b>JD, *DSEs</b> </th>
                                            <th><button id="btn3_11" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                        </tr>
                                    </thead>
                                </table>
                                </form>
                                </tbody>
                                </table>
                        <form id="form3_12" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store312', 'form3_12');">
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                            @csrf
                                <!--3.12 Trabajos dirigidos para la titulación de estudiantes-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">150</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_12" class="acreditacion" colspan=7>3.12 Publicaciones de
                                                investigación
                                                relacionadas con el
                                                contenido
                                                de los PE que imparte el docente</th>
                                            <th></th>
                                            <th id="score3_12">0</th>
                                            <th id="comision3_12">0</th>
                                            <th class="acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion">Incisos</th>
                                            <th class="acreditacion">Actividad</th>
                                            <th class="acreditacion">Obra</th>
                                            <th class="acreditacion">Nivel</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">Cantidad</th>
                                            <th></th>
                                            <th></th>
                                            <th class="acreditacion">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--3_12 Publicaciones de investigación incisos-->
                                        <tr>
                                            <td>a)</td>
                                            <td>Autor(a) o coautor(a) de libros, técnicos, científicos y humanísticos</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td id="puntajeCientificos"><b>100</b> </td>
                                            <td><input type="number" id="cantCientifico"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantCientifico') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalCientificos"></td>
                                            <td><input type="value" id="comisionCientificos" placeholder="0"
                                                    oninput="onActv3Comision3_12()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCientificos"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Autor(a) o coautor(a) de libros de divulgación</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td id="puntajeDivulgacion"><b>50</b></td>
                                            <td><input type="number" id="cantDivulgacion"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantDivulgacion') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalDivulgacion"></td>
                                            <td><input type="value" id="comisionDivulgacion" placeholder="0"
                                                    oninput="onActv3Comision3_12()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsDivulgacion"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Traducción de libros</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td id="puntajeTraduccion"><b>40</b></td>
                                            <td><input type="number" id="cantTraduccion"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantTraduccion') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalTraduccion"></td>
                                            <td><input type="value" id="comisionTraduccion" placeholder="0"
                                                    oninput="onActv3Comision3_12()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsTraduccion"></td>
                                        </tr>
                                        <tr>
                                            <td>d)</td>
                                            <td>Autor(a) o coautor(a) de artículos</td>
                                            <td>Con Arbitraje</td>
                                            <td>Internacional</td>
                                            <td id="puntajeArbitrajeInt">60</td>
                                            <td><input type="number" id="cantArbitrajeInt"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantArbitrajeInt') }}">
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalArbitrajeInt"></td>
                                            <td><input type="value" id="comisionArbitrajeInt" placeholder="0"
                                                    oninput="onActv3Comision3_12()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsArbitrajeInt"></td>
                                        </tr>
                                        <tr>
                                            <td>e)</td>
                                            <td>Autor(a) o coautor(a) de artículos</td>
                                            <td>Con Arbitraje</td>
                                            <td>Nacional</td>
                                            <td id="puntajeArbitrajeNac">30</td>
                                            <td><input type="number" id="cantArbitrajeNac"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantArbitrajeNac') }}">
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalArbitrajeNac"></td>
                                            <td><input type="value" id="comisionArbitrajeNac" placeholder="0"
                                                    oninput="onActv3Comision3_12()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsArbitrajeNac"></td>
                                        </tr>
                                        <tr>
                                            <td>f)</td>
                                            <td>Autor(a) o coautor(a) de artículos</td>
                                            <td>Sin Arbitraje</td>
                                            <td>Internacional</td>
                                            <td id="puntajeSinInt">15</td>
                                            <td><input type="number" id="cantSinInt"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantSinInt') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalSinInt"></td>
                                            <td><input type="value" id="comisionSinInt" placeholder="0"
                                                    oninput="onActv3Comision3_12()"></td>
                                            <td><input class="table-header" type="text" id="obsSinInt"></td>
                                        </tr>
                                        <tr>
                                            <td>g)</td>
                                            <td>Autor(a) o coautor(a) de artículos</td>
                                            <td>Sin Arbitraje</td>
                                            <td>Nacional</td>
                                            <td id="puntajeSinNac">10</td>
                                            <td><input type="number" id="cantSinNac"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantSinNac') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalSinNac"></td>
                                            <td><input type="value" id="comisionSinNac" placeholder="0"
                                                    oninput="onActv3Comision3_12()"></td>
                                            <td><input class="table-header" type="text" id="obsSinNac"></td>
                                        </tr>
                                        <tr>
                                            <td>h)</td>
                                            <td>Capítulo de libro especializado</td>
                                            <td>Autor(a) o coautor (a) de capítulo de libro internacional o nacional</td>
                                            <td>--</td>
                                            <td id="puntajeAutor">25</td>
                                            <td><input type="number" id="cantAutor"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantAutor') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalAutor"></td>
                                            <td><input type="value" id="comisionAutor" placeholder="0"
                                                    oninput="onActv3Comision3_12()"></td>
                                            <td><input class="table-header" type="text" id="obsAutor"></td>
                                        </tr>
                                        <tr>
                                            <td>i)</td>
                                            <td>Capítulo de libro especializado</td>
                                            <td>Editor(a) o coeditor(a) de libro</td>
                                            <td>--</td>
                                            <td id="puntajeEditor">25</td>
                                            <td><input type="number" id="cantEditor"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantEditor') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalEditor"></td>
                                            <td><input type="value" id="comisionEditor" placeholder="0"
                                                    oninput="onActv3Comision3_12()"></td>
                                            <td><input class="table-header" type="text" id="obsEditor"></td>
                                        </tr>
                                        <tr>
                                            <td>j)</td>
                                            <td>Sitio web</td>
                                            <td>Diseño de sitio web</td>
                                            <td>--</td>
                                            <td id="puntajeWeb">20</td>
                                            <td><input type="number" id="cantWeb"
                                                    oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantWeb') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalWeb"></td>
                                            <td><input type="value" id="comisionWeb" placeholder="0"
                                                    oninput="onActv3Comision3_12()"></td>
                                            <td><input class="table-header" type="text" id="obsWeb"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Tabla informativa Acreditacion Actividad 3.12-->
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" scope="col">Acreditacion: </th>

                                            <th class="descripcion"><b>Instancia que la otorga</b> </th>
                                            <th><button id="btn3_12" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                        </tr>
                                    </thead>
                                </table>
                                </form>
                                <form id="form3_13" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store313', 'form3_13');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                <!--3.13 Proyectos académicos de investigación-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">130</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <th id="seccion3_13" class="acreditacion" colspan=7>3.13 Proyectos académicos de
                                            investigación</th>
                                        <th id="score3_13">0</th>
                                        <th id="comision3_13">0</th>
                                        <th class="acreditacion" scope="col">Observaciones</th>
                                    </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion">Incisos</th>
                                            <th class="acreditacion">Documento</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">Cantidad</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="acreditacion">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--Incisos 3.13-->
                                        <tr>
                                            <td>a)</td>
                                            <td>Inicio de proyecto de investigación con financiamiento externo</td>
                                            <td id="puntajeInicioFinanExt">50</td>
                                            <td><input type="number" id="cantInicioFinanExt"
                                                    oninput="onActv3SubTotal3_13()" value="{{ oldValueOrDefault('cantInicioFinanExt') }}">
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalInicioFinanExt"></td>
                                            <td><input type="value" id="comisionInicioFinancimientoExt" placeholder="0"
                                                    oninput="onActv3Comision3_13()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsInicioFinancimientoExt"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Inicio de proyecto de investigación interno, aprobado por CAAC</td>
                                            <td id="puntajeInicioInvInterno">25</td>
                                            <td><input type="number" id="cantInicioInvInterno"
                                                    oninput="onActv3SubTotal3_13()" value="{{ oldValueOrDefault('cantInicioInvInterno') }}">
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalInicioInvInterno"></td>
                                            <td><input type="value" id="comisionInicioInvInterno" placeholder="0"
                                                    oninput="onActv3Comision3_13()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsInicioInvInterno"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Reporte cumplido del periodo anual del proyecto de investigación con
                                                financiamiento externo
                                            </td>
                                            <td id="puntajeReporteFinanciamExt">100</td>
                                            <td><input type="number" id="cantReporteFinanciamExt"
                                                    oninput="onActv3SubTotal3_13()" value="{{ oldValueOrDefault('cantReporteFinanciamExt') }}">
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalReporteFinanciamExt"></td>
                                            <td><input type="value" id="comisionReporteFinanciamExt" placeholder="0"
                                                    oninput="onActv3Comision3_13()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsReporteFinanciamExt"></td>
                                        </tr>
                                        <tr>
                                            <td>d)</td>
                                            <td>Reporte cumplido del periodo anual del proyecto de investigación interno,
                                                aprobado por CAAC
                                            </td>
                                            <td id="puntajeReporteInvInt">50</td>
                                            <td><input type="number" id="cantReporteInvInt"
                                                    oninput="onActv3SubTotal3_13()" value="{{ oldValueOrDefault('cantReporteInvInt') }}">
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalReporteInvInt"></td>
                                            <td><input type="value" id="comisionReporteInvInt" placeholder="0"
                                                    oninput="onActv3Comision3_13()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsReporteInvInt"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Tabla informativa Acreditacion Actividad 3.13-->
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" scope="col">Acreditacion: </th>

                                            <th class="descripcion"><b>CAAC, DIIP</b> </th>

                                            <th><button id="btn3_13" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                        </tr>
                                    </thead>
                                </table>
                                </form>
                                <form id="form3_14" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store314', 'form3_14');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                <!--3.14 Participación como ponente en congresos o eventos académicos del Área de Conocimiento o afines del docente-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_14" class="acreditacion" colspan=7>3.14 Participación como ponente
                                                en congresos
                                                o eventos
                                                académicos
                                                del Área de Conocimiento o afines del docente</th>
                                            <th id="score3_14">0</th>
                                            <th id="comision3_14">0</th>
                                            <th class="acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" colspan=2>Congresos y eventos académicos</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">Cantidad</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="acreditacion">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--Incisos 3.14-->
                                        <tr>
                                            <td>a)</td>
                                            <td>Internacional</td>
                                            <td id="puntajeCongresoInt"><b>25</b></td>
                                            <td><input type="number" id="cantCongresoInt"
                                                    oninput="onActv3SubTotal3_14()" value="{{ oldValueOrDefault('cantCongresoInt') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalCongresoInt">0</td>
                                            <td><input type="value" id="comisionCongresoInt" placeholder="0"
                                                    oninput="onActv3Comision3_14()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCongresoInt"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Nacional</td>
                                            <td id="puntajeCongresoNac"><b>20</b></td>
                                            <td><input type="number" id="cantCongresoNac"
                                                    oninput="onActv3SubTotal3_14()" value="{{ oldValueOrDefault('cantCongresoNac') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalCongresoNac">0</td>
                                            <td><input type="value" id="comisionCongresoNac" placeholder="0"
                                                    oninput="onActv3Comision3_14()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCongresoNac"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Local</td>
                                            <td id="puntajeCongresoLoc"><b>10</b></td>
                                            <td><input type="number" id="cantCongresoLoc"
                                                    oninput="onActv3SubTotal3_14()" value="{{ oldValueOrDefault('cantCongresoLoc') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalCongresoLoc">0</td>
                                            <td><input type="value" id="comisionCongresoLoc" placeholder="0"
                                                    oninput="onActv3Comision3_14()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCongresoLoc"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Tabla informativa Acreditacion Actividad 3.14-->
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" scope="col">Acreditacion: </th>

                                            <th class="descripcion"><b>Instancia que otorga</b> </th>

                                            <th><button id="btn3_14" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                        </tr>
                                    </thead>
                                </table>
                                </form>
                                <form id="form3_15" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store315', 'form3_15');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                @csrf
                                <!--3.15 Registro de patentes y productos de investigación tecnológica y educativa -->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">60</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_15" class="acreditacion" colspan=2>3.15 Registro de patentes y
                                                productos de
                                                investigación
                                                tecnológica
                                                y educativa</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">Cantidad</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th id="score3_15">0</th>
                                            <th id="comision3_15">0</th>
                                            <th class="acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <thead>
                                        <tr>
                                            <td>a)</td>
                                            <td>Registro de patentes</td>
                                            <td id="puntajePatentes"><b>60</b></td>
                                            <td><input type="number" id="cantPatentes" 
                                                    oninput="onActv3SubTotal3_15()" value="{{ oldValueOrDefault('puntajePatentes') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalPatentes">0</td>
                                            <td><input type="value" id="comisionPatententes" placeholder="0"
                                                    oninput="onActv3Comision3_15()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsPatentes"></td>
                                        </tr>
                                        </thead>
                                        <thead>
                                        <tr>
                                            <td>b)</td>
                                            <td>Desarrollo de prototipos</td>
                                            <td id="puntajePrototipos"><b>30</b></td>
                                            <td><input type="number" id="cantPrototipos" 
                                                    oninput="onActv3SubTotal3_15()" value="{{ oldValueOrDefault('cantPrototipos') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalPrototipos">0</td>
                                            <td><input type="value" id="comisionPrototipos" placeholder="0"
                                                    oninput="onActv3Comision3_15()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsPrototipos"></td>
                                        </tr>
                                    </thead>
                                    </tbody>
                                </table>
                                <!--Tabla informativa Acreditacion Actividad 3.15-->
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" scope="col">Acreditacion: </th>

                                            <th class="descripcion"><b>IMPI</b></th>

                                            <th><button id="btn3_15" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                        </tr>
                                    </thead>
                                </table> 
                                </form>
                                <br>
                                <form id="form3_16" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store316', 'form3_16');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                @csrf
                                <!--3.16 Actividades de arbitraje, revisión, correción y edición -->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">30</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h3></h3>
                                            </th>
                                            <th>
                                                <h3></h3>
                                            </th>
                                            <th>
                                                <h3>Investigación</h3>
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_16" class="acreditacion" colspan=7> 3.16 Actividades de arbitraje,
                                                revisión,
                                                correción y edición
                                            </th>
                                            <th id="score3_16">0</th>
                                            <th id="comision3_16">0</th>
                                            <th class="acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion">Incisos</th>
                                            <th class="acreditacion">Actividad</th>
                                            <th class="acreditacion">Nivel</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">Cantidad</th>
                                            <th></th>
                                            <th></th>
                                            <th class="acreditacion">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>a)</td>
                                            <td>Arbitraje a proyectos de investigación</td>
                                            <td>Internacional</td>
                                            <td id="puntajeArbInt"><b>30</b></td>
                                            <td><input type="number" id="cantArbInt" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantArbInt') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalArbInt"></td>
                                            <td><input type="value" id="comisionArbInt" placeholder="0"
                                                    oninput="onActv3Comision3_16()"></td>
                                            <td><input class="table-header" type="text" id="obsArbInt"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Arbitraje a proyectos de investigación</td>
                                            <td>Nacional</td>
                                            <td id="puntajeArbINac"><b>25</b></td>
                                            <td><input type="number" id="cantArbNac" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantArbNac') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalArbNac"></td>
                                            <td><input type="value" id="comisionArbNac" placeholder="0"
                                                    oninput="onActv3Comision3_16()"></td>
                                            <td><input class="table-header" type="text" id="obsArbNac"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Arbitraje de publicaciones</td>
                                            <td>Internacional</td>
                                            <td id="puntajePubInt"><b>20</b></td>
                                            <td><input type="number" id="cantPubInt" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantPubInt') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalPubInt"></td>
                                            <td><input type="value" id="comisionPubInt" placeholder="0"
                                                    oninput="onActv3Comision3_16()"></td>
                                            <td><input class="table-header" type="text" id="obsPubInt"></td>
                                        </tr>
                                        <tr>
                                            <td>d)</td>
                                            <td>Arbitraje de publicaciones</td>
                                            <td>Nacional</td>
                                            <td id="puntajePubINac"><b>10</b></td>
                                            <td><input type="number" id="cantPubNac" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantPubNac') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalPubNac"></td>
                                            <td><input type="value" id="comisionPubNac" placeholder="0"
                                                    oninput="onActv3Comision3_16()"></td>
                                            <td><input class="table-header" type="text" id="obsPubNac"></td>
                                        </tr>
                                        <tr>
                                            <td>e)</td>
                                            <td>Revisor(a) de libros, corrector(a)</td>
                                            <td>Internacional</td>
                                            <td id="puntajeRevInt"><b>30</b></td>
                                            <td><input type="number" id="cantRevInt" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantRevInt') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalRevInt"></td>
                                            <td><input type="value" id="comisionRevInt" placeholder="0"
                                                    oninput="onActv3Comision3_16()"></td>
                                            <td><input class="table-header" type="text" id="obsRevInt"></td>
                                        </tr>
                                        <tr>
                                            <td>f)</td>
                                            <td>Revisor(a) de libros, corrector(a)</td>
                                            <td>Nacional</td>
                                            <td id="puntajeRevINac"><b>25</b></td>
                                            <td><input type="number" id="cantRevNac" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantRevNac') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalRevNac"></td>
                                            <td><input type="value" id="comisionRevNac" placeholder="0"
                                                    oninput="onActv3Comision3_16()"></td>
                                            <td><input class="table-header" type="text" id="obsRevNac"></td>
                                        </tr>
                                        <tr>
                                            <td>g)</td>
                                            <td>Consejo editorial de revista, edición de revista</td>
                                            <td>----</td>
                                            <td id="puntajeRevista"><b>10</b></td>
                                            <td><input type="number" id="cantRevista" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantRevista') }}"></td>
                                            <td></td>
                                            <td></td>
                                            <td id="subtotalRevista"></td>
                                            <td><input type="value" id="comisionRevista" placeholder="0"
                                                    oninput="onActv3Comision3_16()"></td>
                                            <td><input class="table-header" type="text" id="obsRevista"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Tabla informativa Acreditacion Actividad 3.16-->
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" scope="col">Acreditacion: </th>

                                            <th class="descripcion"><b>Institución que lo solicita. En el caso de la UABCS,
                                                    DIIP, SG, CA,
                                                    JD.</b>
                                            </th>
                                            <th><button id="btn3_16" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                        </tr>
                                    </thead>
                                </table> 
                                </form>
                                <br>
                                <form id="form3_17" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store317', 'form3_17');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                @csrf
                                <!--3.17 Proyectos académicos de extensión y difusión-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">50</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h3></h3>
                                            </th>
                                            <th>
                                                <h3></h3>
                                            </th>
                                            <th>
                                                <h3 style="width: 50px;">Cuerpos Colegiados</h3>
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=2>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_17" class="acreditacion" colspan=3> 3.17 Proyectos académicos de
                                                extensión y
                                                difusión</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">Cantidad</th>
                                            <th></th>
                                            <th id="score3_17">0</th>
                                            <th id="comision3_17">0</th>
                                            <th class="acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>a)</td>
                                            <td>Inicio de proyectos de extensión y difusión con financiamiento externo</td>
                                            <td></td>
                                            <td id="puntajeDifusionExt"><b>15</b></td>
                                            <td><input type="number" id="cantDifusionExt" 
                                                    oninput="onActv3SubTotal3_17()" value="{{ oldValueOrDefault('cantDifusionExt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalDifusionExt"></td>
                                            <td><input type="value" id="comisionDifusionExt" placeholder="0"
                                                    oninput="onActv3Comision3_17()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsDifusionExt"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Inicio de proyectos de extensión y difusión internos, aprobados por CAAC
                                            </td>
                                            <td></td>
                                            <td id="puntajeDifusionInt"><b>10</b></td>
                                            <td><input type="number" id="cantDifusionInt" 
                                                    oninput="onActv3SubTotal3_17()" value="{{ oldValueOrDefault('cantDifusionInt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalDifusionInt"></td>
                                            <td><input type="value" id="comisionDifusionInt" placeholder="0"
                                                    oninput="onActv3Comision3_17()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsDifusionInt"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Reporte cumplido del periodo anual de proyecto de extensión y difusión con
                                                financiamiento
                                                externo
                                            </td>
                                            <td></td>
                                            <td id="puntajeRepDifusionExt"><b>35</b></td>
                                            <td><input type="number" id="cantRepDifusionExt" 
                                                    oninput="onActv3SubTotal3_17()" value="{{ oldValueOrDefault('cantRepDifusionExt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalRepDifusionExt"></td>
                                            <td><input type="value" id="comisionRepDifusionExt" placeholder="0"
                                                    oninput="onActv3Comision3_17()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsRepDifusionExt"></td>
                                        </tr>
                                        <tr>
                                            <td>d)</td>
                                            <td>Reporte cumplido del periodo anual de proyecto de extensión y difusión
                                                internos, aprobados por
                                                CAAC</td>
                                            <td></td>
                                            <td id="puntajeRepDifusionInt"><b>20</b></td>
                                            <td><input type="number" id="cantRepDifusionInt" 
                                                    oninput="onActv3SubTotal3_17()" value="{{ oldValueOrDefault('cantRepDifusionInt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalRepDifusionInt"></td>
                                            <td><input type="value" id="comisionRepDifusionInt" placeholder="0"
                                                    oninput="onActv3Comision3_17()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsRepDifusionInt"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Tabla informativa Acreditacion Actividad 3.17-->
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" scope="col">Acreditacion: </th>
                                            <th class="descripcion"><b>CAAC, DDCEU</b></th>
                                            <th><button id="btn3_17" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                        </tr>
                                    </thead>
                                </table>
                                </form>
                                <br>

                                <form id="form3_18" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/formato-evaluacion/store318', 'form3_18');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                @csrf
                                <!--3.18 Organización de congresos o eventos institucionales del área de conocimiento de la o el Docente-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=2>Actividad</th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_18" class="acreditacion" colspan=5> 3.18 Organización de congresos
                                                o eventos
                                                institucionales del
                                                área
                                                de conocimiento de la o el Docente</th>
                                            <th></th>
                                            <th></th>
                                            <th id="score3_18">0</th>
                                            <th id="comision3_18">0</th>
                                            <th class="acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion">Incisos</th>
                                            <th class="acreditacion" colspan=1 style="padding-left: 170px;">Actividad</th>
                                            <th></th>
                                            <th class="acreditacion">Nivel</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="acreditacion">Cantidad</th>
                                            <th></th>
                                            <th class="acreditacion">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>a)</td>
                                            <td>Comité organizador</td>
                                            <td></td>
                                            <td>Internacional**</td>
                                            <td id="puntajeComOrgInt"><b>40</b></td>
                                            <td><input type="number" id="cantComOrgInt" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComOrgInt') }}"></td>
                                            <td></td>
                                            <td id="subtotalComOrgInt"></td>
                                            <td><input type="value" id="comisionComOrgInt" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsComOrgInt"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Comité organizador</td>
                                            <td></td>
                                            <td>Nacional</td>
                                            <td id="puntajeComOrgNac"><b>20</b></td>
                                            <td><input type="number" id="cantComOrgNac"
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComOrgNac') }}"></td>
                                            <td></td>
                                            <td id="subtotalComOrgNac"></td>
                                            <td><input type="value" id="comisionComOrgNac" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsComOrgNac"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Comité organizador</td>
                                            <td></td>
                                            <td>Regional</td>
                                            <td id="puntajeComOrgRegc"><b>10</b></td>
                                            <td><input type="number" id="cantComOrgReg"
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComOrgReg') }}"></td>
                                            <td></td>
                                            <td id="subtotalComOrgReg"></td>
                                            <td><input type="value" id="comisionComOrgReg" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsComOrgReg"></td>
                                        </tr>
                                        <tr>
                                            <td>d)</td>
                                            <td>Comisiones de Apoyo</td>
                                            <td></td>
                                            <td>Internacional</td>
                                            <td id="puntajeComApoyoInt"><b>40</b></td>
                                            <td><input type="number" id="cantComApoyoInt" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComApoyoInt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalComApoyoInt"></td>
                                            <td><input type="value" id="comisionComApoyoInt" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsComApoyoInt"></td>
                                        </tr>
                                        <tr>
                                            <td>e)</td>
                                            <td>Comisiones de Apoyo</td>
                                            <td></td>
                                            <td>Nacional</td>
                                            <td id="puntajeComApoyoNac"><b>20</b></td>
                                            <td><input type="number" id="cantComApoyoNac" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComApoyoNac') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalComApoyoNac"></td>
                                            <td><input type="value" id="comisionComApoyoNac" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsComApoyoNac"></td>
                                        </tr>
                                        <tr>
                                            <td>f)</td>
                                            <td>Comisiones de Apoyo</td>
                                            <td></td>
                                            <td>Regional</td>
                                            <td id="puntajeComApoyoRegc"><b>10</b></td>
                                            <td><input type="number" id="cantComApoyoReg" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComApoyoReg') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalComApoyoReg"></td>
                                            <td><input type="value" id="comisionComApoyoReg" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsComApoyoReg"></td>
                                        </tr>
                                        <tr>
                                            <td>g)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comité organizador</td>
                                            <td>Internacional</td>
                                            <td id="puntajeCicloComOrgInt"><b>20</b></td>
                                            <td><input type="number" id="cantCicloComOrgInt" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComOrgInt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComOrgInt"></td>
                                            <td><input type="value" id="comisionCicloComOrgInt" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCicloComOrgInt"></td>
                                        </tr>
                                        <tr>
                                            <td>h)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comité organizador</td>
                                            <td>Nacional</td>
                                            <td id="puntajeCicloComOrgNac"><b>15</b></td>
                                            <td><input type="number" id="cantCicloComOrgNac" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComOrgNac') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComOrgNac"></td>
                                            <td><input type="value" id="comisionCicloComOrgNac" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCicloComOrgNac"></td>
                                        </tr>
                                        <tr>
                                            <td>i)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comité organizador</td>
                                            <td>Regional/Institucional</td>
                                            <td id="puntajeCicloComOrgReg"><b>10</b></td>
                                            <td><input type="number" id="cantCicloComOrgReg" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComOrgReg') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComOrgReg"></td>
                                            <td><input type="value" id="comisionCicloComOrgReg" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCicloComOrgReg"></td>
                                        </tr>
                                        <tr>
                                            <td>j)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comisiones de apoyo</td>
                                            <td>Internacional</td>
                                            <td id="puntajeCicloComApoyoInt"><b>20</b></td>
                                            <td><input type="number" id="cantCicloComApoyoInt" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComApoyoInt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComApoyoInt"></td>
                                            <td><input type="value" id="comisionCicloComApoyoInt" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCicloComApoyoInt"></td>
                                        </tr>
                                        <tr>
                                            <td>k)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comisiones de apoyo</td>
                                            <td>Nacional</td>
                                            <td id="puntajeCicloComApoyoNac"><b>15</b></td>
                                            <td><input type="number" id="cantCicloComApoyoNac" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComApoyoNac') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComApoyoNac"></td>
                                            <td><input type="value" id="comisionCicloComApoyoNac" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCicloComApoyoNac"></td>
                                        </tr>
                                        <tr>
                                            <td>l)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comisiones de apoyo</td>
                                            <td>Regional/Institucional</td>
                                            <td id="puntajeCicloComApoyoReg"><b>10</b></td>
                                            <td><input type="number" id="cantCicloComApoyoReg" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComApoyoReg') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComApoyoReg"></td>
                                            <td><input type="value" id="comisionCicloComApoyoReg" placeholder="0"
                                                    oninput="onActv3Comision3_18()">
                                            </td>
                                            <td><input class="table-header" type="text" id="obsCicloComApoyoReg"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Tabla informativa Acreditacion Actividad 3.18-->
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" scope="col" colspan=2> **Coparticipación técnica y/o
                                                académica y/o
                                                financiera
                                                de institución extranjera</th>
                                            <th class="acreditacion" style="padding-left: 100px;">Acreditacion:</th>
                                            <th class="descripcion"><b>Instancia que lo otorga</b></th>
                                            <th><button id="btn3_18" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                                        </tr>
                                    </thead>
                                </table>
                                </form>
                                <br>
                                <form id="form3_19" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store319', 'form3_19');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">

                                @csrf
                                <!--3.19 Participación en cuerpos colegiados-->
                                <h4>Puntaje máximo
                                        <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                </h4>
                                    <table class="table table-sm tutorias">
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan=2>Actividad</th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust" scope="col"></th>
                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th id="seccion3_19" class="acreditacion" colspan=5> 3.19 Participación en
                                                    cuerpos colegiados
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th id="score3_19">0</th>
                                                <th id="comision3_19">0</th>
                                                <th class="acreditacion" scope="col">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th class="acreditacion">Incisos</th>
                                                <th class="acreditacion" colspan=1 style="padding-left: 170px;">Actividad
                                                </th>
                                                <th></th>
                                                <th class="acreditacion">Nivel</th>
                                                <th class="acreditacion">Puntaje</th>
                                                <th class="acreditacion">Cantidad</th>
                                                <th></th>
                                                <th class="acreditacion">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>a)</td>
                                                <td>Representante del profesorado ante H. CGU</td>
                                                <td></td>
                                                <td>Titular o suplente</td>
                                                <td id="puntajeCGUtitular"><b>20</b></td>
                                                <td><input type="number" id="cantCGUtitular" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCGUtitular') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalCGUtitular"></td>
                                                <td><input type="value" id="comCGUtitular" placeholder="0"
                                                        oninput="onActv3Comision3_19()"></td>
                                                <td><input class="table-header" type="text" id="obsCGUtitular"></td>
                                            </tr>
                                            <tr>
                                                <td>b)</td>
                                                <td>Representante del profesorado ante H. CGU</td>
                                                <td></td>
                                                <td>Participación como miembro de comisión especial</td>
                                                <td id="puntajeCGUespecial"><b>15</b></td>
                                                <td><input type="number" id="cantCGUespecial" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCGUespecial') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalCGUespecial"></td>
                                                <td><input type="value" id="comCGUespecial" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsCGUespecial"></td>
                                            </tr>
                                            <tr>
                                                <td>c)</td>
                                                <td>Representante del profesorado ante H. CGU</td>
                                                <td></td>
                                                <td>Participación como miembro en comisión permanente</td>
                                                <td id="puntajeCGUpermanente"><b>10</b></td>
                                                <td><input type="number" id="cantCGUpermanente" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCGUpermanente') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalCGUpermanente"></td>
                                                <td><input type="value" id="comCGUpermanente" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsCGUpermanente"></td>
                                            </tr>
                                            <tr>
                                                <td>d)</td>
                                                <td>Representante del profesorado ante CAAC</td>
                                                <td></td>
                                                <td>Titular o suplente</td>
                                                <td id="puntajeCAACtitular"><b>10</b></td>
                                                <td><input type="number" id="cantCAACtitular" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCAACtitular') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalCAACtitular"></td>
                                                <td><input type="value" id="comCAACtitular" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsCAACtitular"></td>
                                            </tr>
                                            <tr>
                                                <td>e)</td>
                                                <td>Representante del profesorado ante CAAC</td>
                                                <td></td>
                                                <td>Participación como integrante de comisión</td>
                                                <td id="puntajeCAACintegCom"><b>5</b></td>
                                                <td><input type="number" id="cantCAACintegCom" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCAACintegCom') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalCAACintegCom"></td>
                                                <td><input type="value" id="comCAACintegCom" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsCAACintegCom"></td>
                                            </tr>
                                            <tr>
                                                <td>f)</td>
                                                <td>Comisiones</td>
                                                <td></td>
                                                <td>Departamentales</td>
                                                <td id="puntajeComDepart"><b>15</b></td>
                                                <td><input type="number" id="cantComDepart" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantComDepart') }}"></td>
                                                <td></td>
                                                <td id="subtotalComDepart"></td>
                                                <td><input type="value" id="comComDepart" placeholder="0"
                                                        oninput="onActv3Comision3_19()"></td>
                                                <td><input class="table-header" type="text" id="obsComDepart"></td>
                                            </tr>
                                            <tr>
                                                <td>g)</td>
                                                <td>Comisiones</td>
                                                <td></td>
                                                <td>Dictaminadora del PEDPD</td>
                                                <td id="puntajeComPEDPD"><b>15</b></td>
                                                <td><input type="number" id="cantComPEDPD"
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantComPEDPD') }}"></td>
                                                <td></td>
                                                <td id="subtotalComPEDPD"></td>
                                                <td><input type="value" id="comComPEDPD" placeholder="0"
                                                        oninput="onActv3Comision3_19()"></td>
                                                <td><input class="table-header" type="text" id="obsComPEDPD"></td>
                                            </tr>
                                            <tr>
                                                <td>h)</td>
                                                <td>Comisiones</td>
                                                <td></td>
                                                <td>Participación como integrante del Comité Académico de Posgrado</td>
                                                <td id="puntajeComPartPos"><b>5</b></td>
                                                <td><input type="number" id="cantComPartPos" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantComPartPos') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalComPartPos"></td>
                                                <td><input type="value" id="comComPartPos" placeholder="0"
                                                        oninput="onActv3Comision3_19()"></td>
                                                <td><input class="table-header" type="text" id="obsComPartPos"></td>
                                            </tr>
                                            <tr>
                                                <td>i)</td>
                                                <td>Responsable</td>
                                                <td></td>
                                                <td>De posgrado</td>
                                                <td id="puntajeRespPos"><b>25</b></td>
                                                <td><input type="number" id="cantRespPos" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantRespPos') }}"></td>
                                                <td></td>
                                                <td id="subtotalRespPos"></td>
                                                <td><input type="value" id="comRespPos" placeholder="0"
                                                        oninput="onActv3Comision3_19()"></td>
                                                <td><input class="table-header" type="text" id="obsRespPos"></td>
                                            </tr>
                                            <tr>
                                                <td>j)</td>
                                                <td>Responsable</td>
                                                <td></td>
                                                <td>De carrera</td>
                                                <td id="puntajeRespCarrera"><b>15</b></td>
                                                <td><input type="number" id="cantRespCarrera" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantRespCarrera') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalRespCarrera"></td>
                                                <td><input type="value" id="comRespCarrera" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsRespCarrera"></td>
                                            </tr>
                                            <tr>
                                                <td>k)</td>
                                                <td>Responsable</td>
                                                <td></td>
                                                <td>De unidad de producción</td>
                                                <td id="puntajeRespProd"><b>20</b></td>
                                                <td><input type="number" id="cantRespProd" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantRespProd') }}"></td>
                                                <td></td>
                                                <td id="subtotalRespProd"></td>
                                                <td><input type="value" id="comRespProd" placeholder="0"
                                                        oninput="onActv3Comision3_19()"></td>
                                                <td><input class="table-header" type="text" id="obsRespProd"></td>
                                            </tr>
                                            <tr>
                                                <td>l)</td>
                                                <td>Responsable</td>
                                                <td></td>
                                                <td>De laboratorio de docencia e investigación</td>
                                                <td id="puntajeRespLab"><b>15</b></td>
                                                <td><input type="number" id="cantRespLab" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantRespLab') }}"></td>
                                                <td></td>
                                                <td id="subtotalRespLab"></td>
                                                <td><input type="value" id="comRespLab" placeholder="0"
                                                        oninput="onActv3Comision3_19()"></td>
                                                <td><input class="table-header" type="text" id="obsRespLab"></td>
                                            </tr>
                                            <tr>
                                                <td>m)</td>
                                                <td>Sinodalías de examen de oposición</td>
                                                <td></td>
                                                <td>Profesorado</td>
                                                <td id="puntajeExamProf"><b>15</b></td>
                                                <td><input type="number" id="cantExamProf" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantExamProf') }}"></td>
                                                <td></td>
                                                <td id="subtotalExamProf"></td>
                                                <td><input type="value" id="comExamProf" placeholder="0"
                                                        oninput="onActv3Comision3_19()"></td>
                                                <td><input class="table-header" type="text" id="obsExamProf"></td>
                                            </tr>
                                            <tr>
                                                <td>n)</td>
                                                <td>Sinodalías de examen de oposición</td>
                                                <td></td>
                                                <td>Ayudantes académicos</td>
                                                <td id="puntajeExamAcademicos"><b>5</b></td>
                                                <td><input type="number" id="cantExamAcademicos" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantExamAcademicos') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalExamAcademicos"></td>
                                                <td><input type="value" id="comExamAcademicos" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsExamAcademicos"></td>
                                            </tr>
                                            <tr>
                                                <td>o1)</td>
                                                <td>Cuerpo académico registrado ante PRODEP</td>
                                                <td>En formación</td>
                                                <td>Responsable</td>
                                                <td id="puntajePRODEPformResp"><b>15</b></td>
                                                <td><input type="number" id="cantPRODEPformResp" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPformResp') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalPRODEPformResp"></td>
                                                <td><input type="value" id="comPRODEPformResp" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsPRODEPformResp"></td>
                                            </tr>
                                            <tr>
                                                <td>o2)</td>
                                                <td>Cuerpo académico registrado ante PRODEP</td>
                                                <td>En formación</td>
                                                <td>Integrante</td>
                                                <td id="puntajePRODEPformInteg"><b>10</b></td>
                                                <td><input type="number" id="cantPRODEPformInteg" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPformInteg') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalPRODEPformInteg"></td>
                                                <td><input type="value" id="comPRODEPformInteg" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsPRODEPformInteg"></td>
                                            </tr>
                                            <tr>
                                                <td>p1)</td>
                                                <td>Cuerpo académico registrado ante PRODEP</td>
                                                <td>En consolidación</td>
                                                <td>Responsable</td>
                                                <td id="puntajePRODEPenconsResp"><b>25</b></td>
                                                <td><input type="number" id="cantPRODEPenconsResp"
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPenconsResp') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalPRODEPenconsResp"></td>
                                                <td><input type="value" id="comPRODEPenconsResp" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsPRODEPenconsResp"></td>
                                            </tr>
                                            <tr>
                                                <td>p2)</td>
                                                <td>Cuerpo académico registrado ante PRODEP</td>
                                                <td>En consolidación</td>
                                                <td>Integrante</td>
                                                <td id="puntajePRODEPenconsInteg"><b>15</b></td>
                                                <td><input type="number" id="cantPRODEPenconsInteg" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPenconsInteg') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalPRODEPenconsInteg"></td>
                                                <td><input type="value" id="comPRODEPenconsInteg" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsPRODEPenconsInteg"></td>
                                            </tr>
                                            <tr>
                                                <td>q1)</td>
                                                <td>Cuerpo académico registrado ante PRODEP</td>
                                                <td>Consolidado</td>
                                                <td>Responsable</td>
                                                <td id="puntajePRODEPconsResp"><b>35</b></td>
                                                <td><input type="number" id="cantPRODEPconsResp" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPconsResp') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalPRODEPconsResp"></td>
                                                <td><input type="value" id="comPRODEPconsResp" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsPRODEPconsResp"></td>
                                            </tr>
                                            <tr>
                                                <td>q2)</td>
                                                <td>Cuerpo académico registrado ante PRODEP</td>
                                                <td>Consolidado</td>
                                                <td>Integrante</td>
                                                <td id="puntajePRODEPconsInteg"><b>25</b></td>
                                                <td><input type="number" id="cantPRODEPconsInteg" 
                                                        oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPconsInteg') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalPRODEPconsInteg"></td>
                                                <td><input type="value" id="comPRODEPconsInteg" placeholder="0"
                                                        oninput="onActv3Comision3_19()">
                                                </td>
                                                <td><input class="table-header" type="text" id="obsPRODEPconsInteg"></td>
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
                                    <button id="btn3_19" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                                    </form>
                                    <br>

                                    </main>

                                    <footer>
                                        @component('components.pie-pag', ['number' => '2'])@endcomponent
                                    </footer>
                    </div>
                </div>
            </div>

            <script>

                const A40 = 6.25;
                const B56 = 17;
                const B57 = 50;
                const variables = {};
                const variablesMultiplicadas = {};

                for (let i = 40; i <= 55; i++) {
                    variables[`B${i}`] = i - 39;
                    variablesMultiplicadas[`C${i}`] = A40 * variables[`B${i}`]; // Calculate and store in variablesMultiplicated object
                }

                const C56 = B56 * A40;
                const C57 = B57 * A40;
                console.log(variablesMultiplicadas);
                const elaboracion = document.getElementById('elaboracion').value;

                const rc1 = document.getElementById('rc1').value;
                const rc2 = document.getElementById('rc2').value;
                const rc3 = document.getElementById('rc3').value;
                const rc4 = document.getElementById('rc4').value;
                const comisionIncisoA = document.getElementById('comisionIncisoA').value;
                const comisionIncisoB = document.getElementById('comisionIncisoB').value;
                const comisionIncisoC = document.getElementById('comisionIncisoC').value;
                const comisionIncisoD = document.getElementById('comisionIncisoD').value;
                const obs3_10 = ['obsGrupal', 'obsIndividual'];
                const obs3_11 = ['obsAsesoria', 'obsServicio', 'obsPracticas'];
                const obs3_12 = ['obsCientificos', 'obsDivulgacion', 'obsTraduccion', 'obsArbitrajeInt', 'obsArbitrajeNac', 'obsSinInt', 'obsSinNac', 'obsAutor', 'obsEditor', 'obsWeb'];
                const obs3_13 = ['obsInicioFinancimientoExt', 'obsInicioInvInterno', 'obsReporteFinanciamExt', 'obsReporteInvInt'];
                const obs3_14 = ['obsCongresoInt', 'obsCongresoNac', 'obsCongresoLoc'];
                const obs3_15 = ['obsPatentes', 'obsPrototipos'];
                const obs3_16 = ['obsArbInt', 'obsArbNac', 'obsPubInt', 'obsPubNac', 'obsRevInt', 'obsRevNac', 'obsRevista'];
                const obs3_17 = ['obsDifusionExt', 'obsDifusionInt', 'obsRepDifusionExt', 'obsRepDifusionInt'];
                const obs3_18 = ['obsComOrgInt', 'obsComOrgNac', 'obsComOrgReg',
                    'obsComApoyoInt', 'obsComApoyoNac', 'obsComApoyoReg', 'obsCicloComOrgInt', 'obsCicloComOrgNac', 'obsCicloComOrgReg', 'obsCicloComApoyoInt', 'obsCicloComApoyoNac', 'obsCicloComApoyoReg'];
                const obs3_19 = ['obsCGUtitular', 'obsCGUespecial',
                    'obsCGUpermanente', 'obsCAACtitular', 'obsCAACintegCom', 'obsComDepart', 'obsComPEDPD',
                    'obsComPartPos', 'obsRespPos', 'obsRespCarrera', 'obsRespProd', 'obsRespLab', 'obsExamProf',
                    'obsExamAcademicos', 'obsPRODEPformResp', 'obsPRODEPformInteg', 'obsPRODEPenconsResp',
                    'obsPRODEPenconsInteg', 'obsPRODEPconsResp', 'obsPRODEPconsInteg'
                ];


                let data = {

                    elaboracion: elaboracion,
                    cant1: cant1,
                    cant2: cant2,
                    cant3: cant3,
                    rc1: rc1,
                    rc2: rc2,
                    rc3: rc3,
                    rc4: rc4,
                    comisionIncisoA: comisionIncisoA,
                    comisionIncisoB: comisionIncisoB,
                    comisionIncisoC: comisionIncisoC,
                    comisionIncisoD: comisionIncisoD,
                    score3_1: score3_1,
                    score3_2: score3_2,
                    comision3_2: comision3_2,
                    score3_3: score3_3,
                    comision3_3: comision3_3,
                    score3_4: score3_4,
                    comision3_4: comision3_4,
                    score3_5: score3_5,
                    comision3_5: comision3_5,
                    score3_6: score3_6,
                    comision3_6: comision3_6,
                    score3_8: score3_8,
                    comision3_8: comision3_8,
                    score3_8_1: score3_8_1,
                    comision3_8_1: comision3_8_1,
                    score3_9: score3_9,
                    comision3_9: comision3_9,
                    score3_10: score3_10,
                    comision3_10: comision3_10,
                    score3_11: score3_11,
                    comision3_11: comision3_11,
                    score3_12: score3_12,
                    comision3_12: comision3_12,
                    score3_13: score3_13,
                    comision3_13: comision3_13,
                    score3_14: score3_14,
                    comision3_14: comision3_14,
                    score3_15: score3_15,
                    comision3_15: comision3_15,
                    score3_16: score3_16,
                    comision3_16: comision3_16,
                    score3_17: score3_17,
                    comision3_17: comision3_17,
                    score3_18: score3_18,
                    comision3_18: comision3_18,
                    score3_19: score3_19,
                    comision3_19: comision3_19,
                    docencia: docencia,
                    obs3_1_1: obs3_1_1,
                    obs3_1_2: obs3_1_2,
                    obs3_1_3: obs3_1_3,
                    obs3_1_4: obs3_1_4,
                    obs3_1_5: obs3_1_5,
                    obs3_2_1: obs3_2_1,
                    obs3_2_2: obs3_2_2,
                    obs3_2_3: obs3_2_3,


                };

                const dse = document.querySelector("#DSE");
                const puntajeAEvaluarPosgrado = document.querySelector("#horasPosgrado");

                const dse2 = document.querySelector("#DSE2");
                const puntajeAEvaluarSemestre = document.querySelector("#horasSemestre");
                const puntajePosgrado = 0, puntajeSemestre = 0, dsePosgrado = "", dseSemestre = "";
                function onload() {
                    // Setup some event handlers. 
                    var buttons = document.getElementsByClassName('button');
                    for (var i = 0; i < buttons.length; i++) { buttons[i].addEventListener('click', handleClick); }

                }

                function handleClick(event) {
                    var currentTarget = event.currentTarget;
                    // Use the event data here. 
                    console.log('Button clicked: ' + currentTarget.getAttribute('data-id'));
                } document.addEventListener('DOMContentLoaded', onload);


                function hayObservacion(indiceActividad) {
                    var selectEscala = document.getElementById('selectEscala' + indiceActividad);
                    var selectActividad = document.getElementById('selectActividad' + indiceActividad);
                    var inputObservacion = document.getElementById('observacion' + indiceActividad);
                    var mensajeObservacion = document.getElementById('mensajeObservacion' + indiceActividad);

                    if (selectActividad.value != 0 && selectEscala.value != 0) {
                        mensajeObservacion.textContent = 'Observación: ' + inputObservacion.value;
                        mensajeObservacion.style.display = 'block';
                        return true;
                    } else {
                        mensajeObservacion.style.display = 'none';
                        return false;
                    }
                }


                const nav = document.querySelector('nav');
                let lastScrollLeft = 0;
                let lastScrollTop = 0;

                window.addEventListener('scroll', () => {
                    let currentScrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
                    let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

                    // Solo ocultar la navegación si el desplazamiento es horizontal hacia la derecha
                    if (currentScrollLeft > lastScrollLeft) {
                        nav.style.display = 'none';

                    } else if (currentScrollLeft < lastScrollLeft) {
                        nav.style.display = 'block';
                    }

                    // Actualizar las posiciones de desplazamiento
                    lastScrollLeft = currentScrollLeft <= 0 ? 0 : currentScrollLeft;
                    lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop;
                });


                // Function to check if there is an observation for a specific activity
                function hayObservacion(actividad) {
                    const obs = document.querySelector(`#obs${actividad}`).value;
                    return obs.trim() !== '';
                }

                function minWithSum(value1, value2) {
                    const sum = value1 + value2;
                    return Math.min(sum, 200);


                }

                function min40(...values) {
                    const sum40 = values.reduce((acc, val) => acc + val, 0);
                    return Math.min(sum40, 40);
                }

                function min30(...values) {
                    const sum30 = values.reduce((acc, val) => acc + val, 0);
                    return Math.min(sum30, 30);
                }


                function min60(...values) {
                    const sum60 = values.reduce((acc, val) => acc + val, 0);
                    return Math.min(sum60, 60);
                }

                function minWithSumThree(value1, value2, value3, value4) {
                    const ms = value1 + value2 + value3 + value4;
                    return Math.min(ms, 100);
                }

                function min50(...values) {
                    const ms = values.reduce((acc, val) => acc + val, 0);
                    return Math.min(ms, 50);
                }

                function minWithSumThreeFive(value1, value2) {
                    const ms = value1 + value2;
                    return Math.min(ms, 75);
                }

                function minTutorias() {
                    // convert the arguments object to an array
                    const values = Array.from(arguments);

                    // use reduce to sum the values
                    const ms = values.reduce((acc, current) => {
                        return acc + current;
                    }, 0);

                    // return the minimum of ms and 200
                    return Math.min(ms, 200);
                }

                function min700(...values) {
                    const ms = values.reduce((acc, val) => acc + val, 0);
                    return Math.min(ms, 700);
                }

                // Función para actualizar el objeto data con los valores de los campos del formulario
                function actualizarData() {
                    data[this.id] = this.value;
                }

                    async function submitForm(url, formId) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        // Get form data
                        let formData = {};
                        let gridOptions = {};
                        let form = document.getElementById(formId);
                        // Ensure the form element exists
                        if (!form) {
                            console.error(`Form with id "${formId}" not found.`);
                            return;
                        }
                        //Recoge los datos dependiendo del formulario actual

                        //score3_1 a score3_19
                        for (let i = 1; i <= 19; i++) {
                            let tdElement = form.querySelector(`td[id="score3_${i}"]`);
                            if (tdElement) {
                                window[`score3_${i}`] = tdElement.textContent || tdElement.innerText || tdElement.innerHTML;
                            } else {
                                console.warn(`Elemento con id "score3_${i}" no encontrado.`);
                            }
                        }


                        //comision3_1 a comision3_19
                        for (let i = 3; i <= 19; i++) {
                            let tdElement2 = form.querySelector(`td[id="comision3_${i}"]`);
                            if (tdElement2) {
                                window[`comision3_${i}`] = tdElement2.textContent || tdElement2.innerText;
                            } else {
                                console.warn(`Elemento con id "comision3_${i}" no encontrado.`);
                            }

                        }


                        // Collect common form data (if any)
                        formData['user_id'] = form.querySelector('input[name="user_id"]').value;
                        formData['email'] = form.querySelector('input[name="email"]').value;
                        formData['user_type'] = form.querySelector('input[name="user_type"]').value;
                        switch (formId) {

                            case 'form3_1':

                                let score3_1Label = form.querySelector('td[id="score3_1"]');
                                //let actv3ComisionLabel = form.querySelector('td[id="actv3Comision"]');
                               formData['elaboracion'] =  form.querySelector('input[name="elaboracion"]').value; 
                               formData['elaboracion2'] = form.querySelector('input[name="elaboracion2"]').value; 
                               formData['elaboracion3'] = form.querySelector('input[name="elaboracion3"]').value;
                                formData['elaboracion4'] = form.querySelector('input[name="elaboracion4"]').value;
                                formData['elaboracion5'] = form.querySelector('input[name="elaboracion5"]').value;   
                                formData['elaboracionSubTotal1'] = document.getElementById('elaboracionSubTotal1').textContent;
                                formData['elaboracionSubTotal2'] = document.getElementById('elaboracionSubTotal2').textContent;
                                formData['elaboracionSubTotal3'] = document.getElementById('elaboracionSubTotal3').textContent;
                                formData['elaboracionSubTotal4'] = document.getElementById('elaboracionSubTotal4').textContent;
                                formData['elaboracionSubTotal5'] = document.getElementById('elaboracionSubTotal5').textContent;


                                formData['score3_1'] = score3_1Label.innerText;
                                //formData['actv3Comision'] = actv3ComisionLabel.innerText;
                                for (let i = 1; i <= 5; i++) {
                                    formData[`obs3_1_${i}`] = form.querySelector(`input[id="obs3_1_${i}"]`).value;
                                }

                                        /*formData['docencia'] = form.querySelector('input[id="docencia"]').value;
                                        */break;

                            case 'form3_2':
                                let score3_2Label = form.querySelector('td[id="score3_2"]').innerText;
                                //let comision3_2Label = form.querySelector('td[id="comision3_2"]').innerText;
                                formData['score3_2'] = score3_2Label;
                                formData['r1'] = document.getElementById('r1').value;
                                formData['r2'] = document.getElementById('r2').value;
                                formData['r3'] = document.getElementById('r3').value;
                                formData['cant1'] = document.getElementById('cant1').textContent;
                                formData['cant2'] = document.getElementById('cant2').textContent;
                                formData['cant3'] = document.getElementById('cant3').textContent;

                                //formData['comision3_2'] = comision3_2Label;


                                //observaciones3_2_1
                                for (let i = 1; i <= 3; i++) {
                                    window[`obs3_2_${i}`] = form.querySelector(`input[id="obs3_2_${i}"]`).value;
                                }

                                for (let i = 1; i <= 3; i++) {
                                    formData[`obs3_2_${i}`] = form.querySelector(`input[id="obs3_2_${i}"]`).value;
                                }

                                break;

                            case 'form3_3':
                                let score3_3Label = form.querySelector('td[id="score3_3"]');
                                let comision3_3Label = form.querySelector('td[id="comision3_3"]');
                                formData['score3_3'] = score3_3Label ? score3_3Label.innerText : '0';
                                //formData['comision3_3'] = comision3_3Label ? comision3_3Label.innerText : '0';
                                formData['rc1'] = document.getElementById('rc1').value;
                                formData['rc2'] = document.getElementById('rc2').value;
                                formData['rc3'] = document.getElementById('rc3').value;
                                formData['rc4'] = document.getElementById('rc4').value;
                                formData['stotal1'] = document.getElementById('stotal1').textContent;
                                formData['stotal2'] = document.getElementById('stotal2').textContent;
                                formData['stotal3'] = document.getElementById('stotal3').textContent;
                                formData['stotal4'] = document.getElementById('stotal4').textContent;
                                // Retrieve observations for form 3.3
                                for (let i = 1; i <= 4; i++) {
                                    formData[`obs3_3_${i}`] = form.querySelector(`input[id="obs3_3_${i}"]`).value || '';
                                }

                                break;

                            case 'form3_4':
                                let score3_4Label = form.querySelector('td[id="score3_4"]');
                                formData['cantInternacional'] = document.getElementById('cantInternacional').value;
                                formData['cantNacional'] = document.getElementById('cantNacional').value;
                                formData['cantidadRegional'] = document.getElementById('cantidadRegional').value;
                                formData['cantPreparacion'] = document.getElementById('cantPreparacion').value;
                                formData['cantInternacional2'] = document.getElementById('cantInternacional2').textContent;
                                formData['cantNacional2'] = document.getElementById('cantNacional2').textContent;
                                formData['cantidadRegional2'] = document.getElementById('cantidadRegional2').textContent;
                                formData['cantPreparacion2'] = document.getElementById('cantPreparacion2').textContent;

                                formData['score3_4'] = parseInt(score3_4Label.innerText, 10) || 0; 
                                formData  

                                //observaciones3_4_1 a observaciones3_4_4


                                for (let i = 1; i <= 4; i++) {
                                    formData[`obs3_4_${i}`] = form.querySelector(`input[id="obs3_4_${i}"]`).value;
                                }
                                console.log(formData);
                                break;

                            case 'form3_5':
                                let score3_5Label = form.querySelector('td[id="score3_5"]');
                                formData['cantDA'] = document.getElementById('cantDA').value;
                                formData['cantCAAC'] = document.getElementById('cantCAAC').value;
                                formData['cantDA2'] = document.getElementById('cantDA2').textContent;
                                formData['cantCAAC2'] = document.getElementById('cantCAAC2').textContent;
                                formData['obs3_5_1'] = form.querySelector('input[id="obs3_5_1"]').value;
                                formData['obs3_5_2'] = form.querySelector('input[id="obs3_5_2"]').value;
                                formData['score3_5'] = parseInt(score3_5Label.innerText, 10) || 0;

                                break;

                            case 'form3_6':
                                let score3_6Label = form.querySelector('td[id="score3_6"]');
                                formData['score3_6'] = parseFloat(score3_6Label.innerText, 10) || 0;
                                formData['puntaje3_6'] = document.getElementById('puntaje3_6').value;
                                formData['puntajeHoras3_6'] = document.getElementById('puntajeHoras3_6').textContent;                                
                                formData['obs3_6'] = form.querySelector('input[id="obs3_6"]').value;

                                break;

                            case 'form3_7':
                                let score3_7Label = form.querySelector('td[id="score3_7"]');
                                formData['score3_7'] = parseFloat(score3_7Label.innerText, 10) || 0;
                                formData['puntaje3_7'] = document.getElementById('puntaje3_7').value;
                                formData['puntajeHoras3_7'] = document.getElementById('puntajeHoras3_7').textContent;     
                                formData['obs3_7'] = form.querySelector('input[id="obs3_7"]').value;

                                break;

                            case 'form3_8':
                                let score3_8Label = form.querySelector('td[id="score3_8"]');
                                formData['score3_8'] = parseInt(score3_8Label.innerText, 10) || 0;
                                formData['puntaje3_8'] = document.getElementById('puntaje3_8').value;
                                formData['puntajeHoras3_8'] = document.getElementById('puntajeHoras3_8').textContent;   
                                formData['obs3_8'] = form.querySelector('input[id="obs3_8"]').value;

                                break;

                            case 'form3_8_1':
                            fetch('/get-puntaje-maximo')
                            .then(response => response.json())
                            .then(data => {
                                let puntajeMaximo = document.getElementById('puntajeMaximo').textContent || '40'; // Valor por defecto
                                document.getElementById('PuntajeMaximo').textContent = puntajeMaximo;
                            })
                            .catch(error => {
                                console.error('Error al obtener el puntaje máximo:', error);
                            });
                                let score3_8_1Label = form.querySelector('td[id="score3_8_1"]');
                                formData['score3_8_1'] = parseInt(score3_8_1Label.innerText, 10) || 0;
                                formData['puntaje3_8_1'] = document.getElementById('puntaje3_8_1').value;
                                formData['puntajeHoras3_8_1'] = document.getElementById('puntajeHoras3_8_1').textContent;
                                formData['obs3_8_1'] = form.querySelector('input[id="obs3_8_1"]').value;

                                break;                                

                            case 'form3_9':
                                let score3_9Label = form.querySelector('th[id="score3_9"]');
                                formData['score3_9'] = parseInt(score3_9Label.innerText, 10) || 0;
                                formData['puntaje3_9_1'] = document.getElementById('puntaje3_9_1').value;
                                formData['puntaje3_9_2'] = document.getElementById('puntaje3_9_2').value;
                                formData['puntaje3_9_3'] = document.getElementById('puntaje3_9_3').value;
                                formData['puntaje3_9_4'] = document.getElementById('puntaje3_9_4').value;
                                formData['puntaje3_9_5'] = document.getElementById('puntaje3_9_5').value;
                                formData['puntaje3_9_6'] = document.getElementById('puntaje3_9_6').value;
                                formData['puntaje3_9_7'] = document.getElementById('puntaje3_9_7').value;
                                formData['puntaje3_9_8'] = document.getElementById('puntaje3_9_8').value;
                                formData['puntaje3_9_9'] = document.getElementById('puntaje3_9_9').value;
                                formData['puntaje3_9_10'] = document.getElementById('puntaje3_9_10').value;
                                formData['puntaje3_9_11'] = document.getElementById('puntaje3_9_11').value;
                                formData['puntaje3_9_12'] = document.getElementById('puntaje3_9_12').value;
                                formData['puntaje3_9_13'] = document.getElementById('puntaje3_9_13').value;
                                formData['puntaje3_9_14'] = document.getElementById('puntaje3_9_14').value;
                                formData['puntaje3_9_15'] = document.getElementById('puntaje3_9_15').value;
                                formData['puntaje3_9_16'] = document.getElementById('puntaje3_9_16').value;
                                formData['puntaje3_9_17'] = document.getElementById('puntaje3_9_17').value;
                                formData['tutorias1'] = document.getElementById('tutorias1').textContent;
                                formData['tutorias2'] = document.getElementById('tutorias2').textContent;
                                formData['tutorias3'] = document.getElementById('tutorias3').textContent;
                                formData['tutorias4'] = document.getElementById('tutorias4').textContent;
                                formData['tutorias5'] = document.getElementById('tutorias5').textContent;
                                formData['tutorias6'] = document.getElementById('tutorias6').textContent;
                                formData['tutorias7'] = document.getElementById('tutorias7').textContent;
                                formData['tutorias8'] = document.getElementById('tutorias8').textContent;
                                formData['tutorias9'] = document.getElementById('tutorias9').textContent;
                                formData['tutorias10'] = document.getElementById('tutorias10').textContent;
                                formData['tutorias11'] = document.getElementById('tutorias11').textContent;
                                formData['tutorias12'] = document.getElementById('tutorias12').textContent;
                                formData['tutorias13'] = document.getElementById('tutorias13').textContent;
                                formData['tutorias14'] = document.getElementById('tutorias14').textContent;
                                formData['tutorias15'] = document.getElementById('tutorias15').textContent;
                                formData['tutorias16'] = document.getElementById('tutorias16').textContent;
                                formData['tutorias17'] = document.getElementById('tutorias17').textContent;

                                for (let i = 1; i <= 17; i++) {
                                    formData[`obs3_9_${i}`] = form.querySelector(`input[id="obs3_9_${i}"]`).value;
                                }

                                break;

                            case 'form3_10':
                                let score3_10Label = form.querySelector('th[id="score3_10"]');
                                formData['score3_10'] = parseInt(score3_10Label.innerText, 10) || 0;
                                formData['grupalesCant'] = document.getElementById('grupalesCant').value;
                                formData['evaluarGrupales'] = document.getElementById('evaluarGrupales').textContent;
                                formData['evaluarIndividual'] = document.getElementById('evaluarIndividual').textContent;
                                formData['individualCant'] = document.getElementById('individualCant').value;

                                obs3_10.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                            case 'form3_11':
                                let score3_11Label = form.querySelector('th[id="score3_11"]');
                                formData['cantAsesoria'] = document.getElementById('cantAsesoria').value;
                                formData['cantServicio'] = document.getElementById('cantServicio').value;
                                formData['cantPracticas'] = document.getElementById('cantPracticas').value;
                                formData['subtotalAsesoria'] = document.getElementById('subtotalAsesoria').textContent;
                                formData['subtotalServicio'] = document.getElementById('subtotalServicio').textContent;
                                formData['subtotalPracticas'] = document.getElementById('subtotalPracticas').textContent;

                                formData['score3_11'] = parseInt(score3_11Label.innerText, 10) || 0;

                                obs3_11.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                            case 'form3_12':
                                let score3_12Label = form.querySelector('th[id="score3_12"]');
                                formData['score3_12'] = parseInt(score3_12Label.innerText, 10) || 0;
                                formData['cantCientifico'] = document.getElementById('cantCientifico').value;
                                formData['cantDivulgacion'] = document.getElementById('cantDivulgacion').value;
                                formData['cantTraduccion'] = document.getElementById('cantTraduccion').value;
                                formData['cantArbitrajeInt'] = document.getElementById('cantArbitrajeInt').value;
                                formData['cantArbitrajeNac'] = document.getElementById('cantArbitrajeNac').value;
                                formData['cantSinInt'] = document.getElementById('cantSinInt').value;
                                formData['cantSinNac'] = document.getElementById('cantSinNac').value;
                                formData['cantAutor'] = document.getElementById('cantAutor').value;
                                formData['cantEditor'] = document.getElementById('cantEditor').value;
                                formData['cantWeb'] = document.getElementById('cantWeb').value;
                                formData['subtotalCientificos'] = document.getElementById('subtotalCientificos').textContent;
                                formData['subtotalDivulgacion'] = document.getElementById('subtotalDivulgacion').textContent;
                                formData['subtotalTraduccion'] = document.getElementById('subtotalTraduccion').textContent;
                                formData['subtotalArbitrajeInt'] = document.getElementById('subtotalArbitrajeInt').textContent;
                                formData['subtotalArbitrajeNac'] = document.getElementById('subtotalArbitrajeNac').textContent;
                                formData['subtotalSinInt'] = document.getElementById('subtotalSinInt').textContent;
                                formData['subtotalSinNac'] = document.getElementById('subtotalSinNac').textContent;
                                formData['subtotalAutor'] = document.getElementById('subtotalAutor').textContent;
                                formData['subtotalEditor'] = document.getElementById('subtotalEditor').textContent;
                                formData['subtotalWeb'] = document.getElementById('subtotalWeb').textContent;                               

                                obs3_12.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });



                                break;

                            case 'form3_13':
                                let score3_13Label = form.querySelector('th[id="score3_13"]');
                                formData['score3_13'] = parseInt(score3_13Label.innerText, 10) || 0;
                                formData['cantInicioFinanExt'] = document.getElementById('cantInicioFinanExt').value;
                                formData['cantInicioInvInterno'] = document.getElementById('cantInicioInvInterno').value;
                                formData['cantReporteFinanciamExt'] = document.getElementById('cantReporteFinanciamExt').value;
                                formData['cantReporteInvInt'] = document.getElementById('cantReporteInvInt').value;
                                formData['subtotalInicioFinanExt'] = document.getElementById('subtotalInicioFinanExt').textContent;
                                formData['subtotalReporteFinanciamExt'] = document.getElementById('subtotalReporteFinanciamExt').textContent;
                                formData['subtotalReporteInvInt'] = document.getElementById('subtotalReporteInvInt').textContent;
                                formData['subtotalInicioInvInterno'] = document.getElementById('subtotalInicioInvInterno').textContent;

                                obs3_13.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                            case 'form3_14':
                                let score3_14Label = form.querySelector('th[id="score3_14"]');
                                formData['score3_14'] = parseInt(score3_14Label.innerText, 10) || 0;
                                formData['cantCongresoInt'] = document.getElementById('cantCongresoInt').value;
                                formData['cantCongresoNac'] = document.getElementById('cantCongresoNac').value;
                                formData['cantCongresoLoc'] = document.getElementById('cantCongresoLoc').value;
                                formData['subtotalCongresoInt'] = document.getElementById('subtotalCongresoInt').textContent;
                                formData['subtotalCongresoNac'] = document.getElementById('subtotalCongresoNac').textContent;
                                formData['subtotalCongresoLoc'] = document.getElementById('subtotalCongresoLoc').textContent;
                                obs3_14.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                            case 'form3_15':
                                let score3_15Label = form.querySelector('th[id="score3_15"]');
                                formData['score3_15'] = parseInt(score3_15Label.innerText, 10) || 0;
                                formData['cantPatentes'] = document.getElementById('cantPatentes').value;
                                formData['cantPrototipos'] = document.getElementById('cantPrototipos').value;
                                formData['subtotalPatentes'] = document.getElementById('subtotalPatentes').textContent;
                                formData['subtotalPrototipos'] = document.getElementById('subtotalPrototipos').textContent;

                                obs3_15.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                            case 'form3_16':
                                let score3_16Label = form.querySelector('th[id="score3_16"]');
                                formData['score3_16'] = parseInt(score3_16Label.innerText, 10) || 0;
                                formData['cantArbInt'] = document.getElementById('cantArbInt').value;
                                formData['cantArbNac'] = document.getElementById('cantArbNac').value;
                                formData['cantPubInt'] = document.getElementById('cantPubInt').value;
                                formData['cantPubNac'] = document.getElementById('cantPubNac').value;
                                formData['cantRevInt'] = document.getElementById('cantRevInt').value;
                                formData['cantRevNac'] = document.getElementById('cantRevNac').value;
                                formData['cantRevista'] = document.getElementById('cantRevista').value;
                                formData['subtotalArbInt'] = document.getElementById('subtotalArbInt').textContent;
                                formData['subtotalArbNac'] = document.getElementById('subtotalArbNac').textContent
                                formData['subtotalPubInt'] = document.getElementById('subtotalPubInt').textContent;
                                formData['subtotalPubNac'] = document.getElementById('subtotalPubNac').textContent;
                                formData['subtotalRevInt'] = document.getElementById('subtotalRevInt').textContent;
                                formData['subtotalRevNac'] = document.getElementById('subtotalRevNac').textContent;
                                formData['subtotalRevista'] = document.getElementById('subtotalRevista').textContent;


                                obs3_16.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                            case 'form3_17':
                                let score3_17Label = form.querySelector('th[id="score3_17"]');
                                formData['score3_17'] = parseInt(score3_17Label.innerText, 10) || 0;
                                formData['cantDifusionExt'] = document.getElementById('cantDifusionExt').value;
                                formData['cantDifusionInt'] = document.getElementById('cantDifusionInt').value;
                                formData['cantRepDifusionExt'] = document.getElementById('cantRepDifusionExt').value;
                                formData['cantRepDifusionInt'] = document.getElementById('cantRepDifusionInt').value;
                                formData['subtotalDifusionExt'] = document.getElementById('subtotalDifusionExt').textContent;
                                formData['subtotalDifusionInt'] = document.getElementById('subtotalDifusionInt').textContent;
                                formData['subtotalRepDifusionExt'] = document.getElementById('subtotalRepDifusionExt').textContent;
                                formData['subtotalRepDifusionInt'] = document.getElementById('subtotalRepDifusionInt').textContent;

                                obs3_17.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                            case 'form3_18':
                                let score3_18Label = form.querySelector('th[id="score3_18"]');
                                formData['score3_18'] = parseInt(score3_18Label.innerText, 10) || 0;
                                formData['cantComOrgInt'] = document.getElementById('cantComOrgInt').value;
                                formData['cantComOrgNac'] = document.getElementById('cantComOrgNac').value;
                                formData['cantComOrgReg'] = document.getElementById('cantComOrgReg').value;
                                formData['cantComApoyoInt'] = document.getElementById('cantComApoyoInt').value;
                                formData['cantComApoyoNac'] = document.getElementById('cantComApoyoNac').value;
                                formData['cantComApoyoReg'] = document.getElementById('cantComApoyoReg').value;
                                formData['cantCicloComOrgInt'] = document.getElementById('cantCicloComOrgInt').value;
                                formData['cantCicloComOrgNac'] = document.getElementById('cantCicloComOrgNac').value;                                                                             
                                formData['cantCicloComOrgReg'] = document.getElementById('cantCicloComOrgReg').value;
                                formData['cantCicloComApoyoInt'] = document.getElementById('cantCicloComApoyoInt').value;
                                formData['cantCicloComApoyoNac'] = document.getElementById('cantCicloComApoyoNac').value;
                                formData['cantCicloComApoyoReg'] = document.getElementById('cantCicloComApoyoReg').value; 
                                formData['subtotalComOrgInt'] = document.getElementById('subtotalComOrgInt').textContent;
                                formData['subtotalComOrgNac'] = document.getElementById('subtotalComOrgNac').textContent;
                                formData['subtotalComOrgReg'] = document.getElementById('subtotalComOrgReg').textContent;
                                formData['subtotalComApoyoInt'] = document.getElementById('subtotalComApoyoInt').textContent;
                                formData['subtotalComApoyoNac'] = document.getElementById('subtotalComApoyoNac').textContent;
                                formData['subtotalComApoyoReg'] = document.getElementById('subtotalComApoyoReg').textContent;
                                formData['subtotalCicloComOrgInt'] = document.getElementById('subtotalCicloComOrgInt').textContent;
                                formData['subtotalCicloComOrgNac'] = document.getElementById('subtotalCicloComOrgNac').textContent;
                                formData['subtotalCicloComOrgReg'] = document.getElementById('subtotalCicloComOrgReg').textContent;
                                formData['subtotalCicloComApoyoInt'] = document.getElementById('subtotalCicloComApoyoInt').textContent;
                                formData['subtotalCicloComApoyoNac'] = document.getElementById('subtotalCicloComApoyoNac').textContent;
                                formData['subtotalCicloComApoyoReg'] = document.getElementById('subtotalCicloComApoyoReg').textContent;


                                obs3_18.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                            case 'form3_19':
                                let score3_19Label = form.querySelector('th[id="score3_19"]');
                                formData['score3_19'] = parseInt(score3_19Label.innerText, 10) || 0;
                                formData['cantCGUtitular'] = document.getElementById('cantCGUtitular').value;
                                formData['subtotalCGUtitular'] = document.getElementById('subtotalCGUtitular').textContent;  
                                formData['cantCGUespecial'] = document.getElementById('cantCGUespecial').value;
                                formData['subtotalCGUespecial'] = document.getElementById('subtotalCGUespecial').textContent;
                                formData['cantCGUpermanente'] = document.getElementById('cantCGUpermanente').value;
                                formData['subtotalCGUpermanente'] = document.getElementById('subtotalCGUpermanente').textContent;
                                formData['cantCAACtitular'] = document.getElementById('cantCAACtitular').value;
                                formData['subtotalCAACtitular'] = document.getElementById('subtotalCAACtitular').textContent;
                                formData['cantCAACintegCom'] = document.getElementById('cantCAACintegCom').value;
                                formData['subtotalCAACintegCom'] = document.getElementById('subtotalCAACintegCom').textContent;
                                formData['cantComDepart'] = document.getElementById('cantComDepart').value;
                                formData['subtotalComDepart'] = document.getElementById('subtotalComDepart').textContent;
                                formData['cantComPEDPD'] = document.getElementById('cantComPEDPD').value;
                                formData['subtotalComPEDPD'] = document.getElementById('subtotalComPEDPD').textContent;
                                formData['cantComPartPos'] = document.getElementById('cantComPartPos').value;
                                formData['subtotalComPartPos'] = document.getElementById('subtotalComPartPos').textContent;
                                formData['cantRespPos'] = document.getElementById('cantRespPos').value;
                                formData['subtotalRespPos'] = document.getElementById('subtotalRespPos').textContent;
                                formData['cantRespCarrera'] = document.getElementById('cantRespCarrera').value;
                                formData['subtotalRespCarrera'] = document.getElementById('subtotalRespCarrera').textContent;
                                formData['cantRespProd'] = document.getElementById('cantRespProd').value;
                                formData['subtotalRespProd'] = document.getElementById('subtotalRespProd').textContent;
                                formData['cantRespLab'] = document.getElementById('cantRespLab').value;
                                formData['subtotalRespLab'] = document.getElementById('subtotalRespLab').textContent;
                                formData['cantExamProf'] = document.getElementById('cantExamProf').value;
                                formData['subtotalExamProf'] = document.getElementById('subtotalExamProf').textContent;
                                formData['cantExamAcademicos'] = document.getElementById('cantExamAcademicos').value;
                                formData['subtotalExamAcademicos'] = document.getElementById('subtotalExamAcademicos').textContent;
                                formData['cantPRODEPformResp'] = document.getElementById('cantPRODEPformResp').value;
                                formData['subtotalPRODEPformResp'] = document.getElementById('subtotalPRODEPformResp').textContent;
                                formData['cantPRODEPformInteg'] = document.getElementById('cantPRODEPformInteg').value;
                                formData['subtotalPRODEPformInteg'] = document.getElementById('subtotalPRODEPformInteg').textContent;
                                formData['cantPRODEPenconsResp'] = document.getElementById('cantPRODEPenconsResp').value;
                                formData['subtotalPRODEPenconsResp'] = document.getElementById('subtotalPRODEPenconsResp').textContent;
                                formData['cantPRODEPenconsInteg'] = document.getElementById('cantPRODEPenconsInteg').value;
                                formData['subtotalPRODEPenconsInteg'] = document.getElementById('subtotalPRODEPenconsInteg').textContent;
                                formData['cantPRODEPconsResp'] = document.getElementById('cantPRODEPconsResp').value;
                                formData['subtotalPRODEPconsResp'] = document.getElementById('subtotalPRODEPconsResp').textContent;
                                formData['cantPRODEPconsInteg'] = document.getElementById('cantPRODEPconsInteg').value;
                                formData['subtotalPRODEPconsInteg'] = document.getElementById('subtotalPRODEPconsInteg').textContent;


                                    obs3_19.forEach(field => {
                                    formData[field] = form.querySelector(`input[id="${field}"]`).value;
                                });

                                break;

                        }
                        //formData['docencia'] = form.querySelector('input[id="docencia"]').value;
                        console.log(docencia);
                        console.log('Form data:', formData); // Log form data to check values

                        // Enviar datos al servidor
                        try {
                            let response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(formData),
                            });

                            const responseText = await response.text(); // Obtener la respuesta como texto
                            console.log('Raw response from server:', responseText); // Ver qué se devuelve

                            if (responseText.success) {
                                showMessage('Formulario no enviado', 'red');
                            } else {
                                showMessage('Formulario enviado', 'green'); 
                            }

                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }

                            let data = await response.json(); // si la respuesta es JSON
                            console.log('Response received from server:', data);

                        } catch (error) {
                            console.error('There was a problem with the fetch operation:', error);
                        }
                    }


        // Cuando el DOM se ha cargado completamente, puedes agregar los controladores de eventos
        document.addEventListener('DOMContentLoaded', function () {
            // Asociar la función a los formularios
            const form3_1 = document.getElementById('form3_1');
            if (form3_1) {
                form3_1.onsubmit = function (event) {
                    event.preventDefault(); // Previene el envío por defecto
                    submitForm('/formato-evaluacion/store31', 'form3_1'); // Llama a la función submitForm
                };
            }

        });



        // Función para actualizar el label en el footer con la convocatoria y periodo de evaluación
        function actualizarLabelConvocatoriaPeriodo(convocatoria, periodo) {
            const label = document.getElementById('convocatoriaPeriodoLabel');
            label.textContent = `Convocatoria: ${convocatoria}, Período: ${periodo}`;
        }

        // Captura la convocatoria y periodo de evaluación al enviar el formulario form1
        document.addEventListener('DOMContentLoaded', function () {
            const form1 = document.getElementById('form1');
            form1.addEventListener('submit', function (event) {
                event.preventDefault(); // Evita el envío del formulario para manejarlo con JavaScript

                // Captura los valores del formulario form1
                const convocatoria = document.getElementById('convocatoria').value;
                const periodo = document.getElementById('periodo').value;

                // Actualiza el label en el footer con los valores capturados
                actualizarLabelConvocatoriaPeriodo(convocatoria, periodo);
                console.log(label);
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Get the canvas element
            var canvas = document.getElementById('convocatoriaCanvas');
            var context = canvas.getContext('2d');

            // Function to update the canvas with 'Convocatoria' value
            function updateCanvas(text) {
                // Clear the canvas
                context.clearRect(200, 100, canvas.width, canvas.height);

                // Set text properties
                context.font = '20px Arial';
                context.fillStyle = 'black';
                context.textAlign = 'right';
                context.textBaseline = 'middle';

                // Draw the text
                context.fillText(text, canvas.width / 2, canvas.height / 2);
            }

            // Get the input element with id 'convocatoria'
            var convocatoriaInput = document.getElementById('convocatoria');
            if (convocatoriaInput) {
                // Update the canvas initially with the placeholder value or empty
                updateCanvas(convocatoriaInput.placeholder);

                // Listen for input events to dynamically update the canvas
                convocatoriaInput.addEventListener('input', function () {
                    var newValue = convocatoriaInput.value;
                    updateCanvas(newValue);
                });
            }
        });


        document.addEventListener('DOMContentLoaded', function () {
            const userEmail = "{{ Auth::user()->email }}"; // Obtén el email del usuario desde Blade

            const allowedEmails = [
                'joma_18@alu.uabcs.mx',
                'oa.campillo@uabcs.mx',
                'rluna@uabcs.mx',
                'v.andrade@uabcs.mx'
            ];

            // Verifica si el email está en la lista de correos permitidos
            if (allowedEmails.includes(userEmail)) {
                // Muestra el enlace
                document.getElementById('jsonDataLink').classList.remove('d-none');
            }
        });

    document.addEventListener('DOMContentLoaded', function () {
        // Define buttons and their additional margins
        const buttons = {
            'btn3_8': 190,
            'btn3_8_1': 380,
            'btn3_9': 70,
            'btn3_1': 70,         
            'btn3_2': 300,
            'btn3_3': 160,
            'btn3_4': 160,
            'btn3_5': 330,
            'btn3_6': 390,
            'btn3_7': 130,
            'btn3_10': -500,
            'btn3_11': 350,
            'btn3_12': 220,
            'btn3_13': 330,
            'btn3_14': 240,
            'btn3_15': -560,
            'btn3_16': -190,
            'btn3_17': 300,
            'btn3_18': -230,
            'btn3_19': 240

        };

        // Function to adjust margin for a single button
        function adjustMargin(buttonId, additionalMargin) {
            const button = document.getElementById(buttonId);
            if (button) {
                const currentStyle = window.getComputedStyle(button);
                const currentMargin = parseInt(currentStyle.marginLeft);
                button.style.marginLeft = (currentMargin + additionalMargin) + 'px';
            }
        }

        // Apply margins to all buttons
        Object.entries(buttons).forEach(([buttonId, margin]) => {
            adjustMargin(buttonId, margin);
        });

        const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
        if (toggleDarkModeButton) {
            const widthDarkButton = window.outerWidth - 230;
            toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
        }

        toggleDarkMode();
    });




            </script>
        </body>
@endif

</html>