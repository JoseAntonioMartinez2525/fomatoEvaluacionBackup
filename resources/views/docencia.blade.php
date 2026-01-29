@php
$userType = Auth::user()->user_type;
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
use App\Models\DynamicForm;
use Illuminate\Support\Str;

$staticStepCount = 20; // La última actividad estática (3.19) corresponde al paso 20
$dynamicFormIndex = 0;

$staticFormTypes = [
    '3.1', '3.2', '3.3', '3.4', '3.5', '3.6', '3.7', '3.8', '3.8.1', '3.9', 
    '3.10', '3.11', '3.12', '3.13', '3.14', '3.15', '3.16', '3.17', '3.18', '3.19'
];

  $renderDataByForm = $renderDataByForm ?? [];

  // Si la variable no viene del controlador, la obtenemos aquí para evitar "Undefined variable"
  if (!isset($dynamicForms)) {
      $dynamicForms = DynamicForm::where('form_type', 'like', '3.%')->get();
  }

  // Aseguramos que los campos JSON sean arrays (decodificando si son strings)
  foreach ($dynamicForms as $df) {
      if (is_string($df->form_structure)) $df->form_structure = json_decode($df->form_structure, true) ?? [];
      if (is_string($df->form_data)) $df->form_data = json_decode($df->form_data, true) ?? [];
  }

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

    <x-head-resources />
{{-- @include('partials.timer') --}}

    <script>
        window.isDarkModeGlobal = {{ $darkMode ?? false ? 'true' : 'false' }};
    </script>
    <style>
        div#step11, div#step12, div#step13, div#step14, div#step15, div#step16, div#step17, div#step18, div#step19, div#step20{
            margin-inline-start: 10rem;
        }

        button#edit-form-btn{
         margin-inline-start: 10rem;
         background-color: #82bdb2;
         border-color: transparent;
         color: white;
        }

         div#step9, div#step8{
            margin-bottom: 3rem;
         }

    </style>
</head>
@if (Route::has('login'))
        @csrf
        @if (Auth::check())

            <nav class="nav flex-column"
                style="padding-top: 0.125rem; height: 100.25rem; background: linear-gradient(90deg, #afc7ce, #4281a4); width:330px;">
                <div class="nav-header" style="display: flex;padding-top: 2rem;justify-content: flex-start;align-content: flex-start;flex-direction: row-reverse;align-items: baseline;">
                    <li style="list-style: none; margin-right: 20px;">
                        <a href="{{ route('login') }}" style="display:inline;padding-left:1rem;" title="cerrar_sesion">
                            <i class="fas fa-power-off" style="font-size: 20px; color:white;" name="cerrar_sesion"></i>
                        </a>
                    </li>  
                    <li class="nav-item">
                        <a class="nav-link disabled enlaceSN" style="font-size: large; color: white;padding-left: 1rem;" href="#">
                            <i class="fa-solid fa-user"></i>&nbsp&nbsp{{ Auth::user()->email }}
                        </a>
                    </li>
                </div>

                <br>
           @endif
            

</head> 
<body class="font-sans antialiased">
    <x-general-header />
    @if (Auth::check())
        <x-nav-docentes :user="Auth::user()">
            <li class="nav-item">
            @if(Auth::user()->user_type === 'dictaminador')
                <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('comision_dictaminadora') }}">Selección de Formatos</a>
            @elseif(Auth::user()->user_type === 'secretaria')
                <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('secretaria') }}">Selección de Formatos</a>
            @endif
                <a class="nav-link active enlaceSN" style="width: 300px;font-size: 20px;" href="{{ route('welcome') }}" title="Formato de Evaluación docente"><i class="fa-solid fa-align-justify"></i>&nbsp;Evaluación</a>
            </li>
            @if($userType !== 'docente')
            <li class="nav-item">
                <a class="nav-link active enlaceSN" href="{{ route('resumen') }}">Resumen</a>  
                <a class="nav-link active enlaceSN" style="width: 300px;font-size: 20px;" href="javascript:void(0);" onclick="showStep(1)" title="Formato de Evaluación docente"><i class="fas fa-chalkboard-teacher"></i>&nbsp;Calidad en la docencia</a>
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
                <li><a href="javascript:void(0);" onclick="showStep(1)">3.1 Participación en actividades de diseño curricular</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(2)">3.2 Calidad del desempeño docente evaluada por el alumnado</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(3)">3.3 Publicaciones relacionadas con la docencia</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(4)">3.4 Distinciones académicas recibidas por el docente</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(5)">3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD
                        y
                        por CAAC</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(6)">3.6 Capacitación y actualización pedagógica recibida</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(7)">3.7 Cursos de actualización disciplinaria recibidos dentro de su área de
                        conocimiento</a>
                </li>
                <li><a href="javascript:void(0);" onclick="showStep(8)">3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de
                        educación, continua o de formación y capacitación docente</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(9)">3.8.1 RSU </a></li>
                <li><a href="javascript:void(0);" onclick="showStep(10)">3.9 Trabajos dirigidos para la titulación de estudiantes</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(11)">3.10 Tutorías a estudiantes</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(12)">3.11 Asesoría a estudiantes</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(13)">3.12 Publicaciones de investigación relacionadas con el contenido de los PE que
                        imparte el docente</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(14)">3.13 Proyectos académicos de investigación</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(15)">3.14 Participación como ponente en congresos o eventos académicos del
                        Área de Conocimiento o afines del docente</a></li>
                <li><a href="javascript:void(0);" onclick="showStep(16)">3.15 Registro de patentes y productos de investigación tecnológica y educativa</a>
                </li>
                <li><a href="javascript:void(0);" onclick="showStep(17)">3.16 Actividades de arbitraje, revisión, correción y edición </a></li>
                <li><a href="javascript:void(0);" onclick="showStep(18)">3.17 Proyectos académicos de extensión y difusión </a></li>
                <li><a href="javascript:void(0);" onclick="showStep(19)">3.18 Organización de congresos o eventos institucionales del área de conocimiento de
                        la ó el Docente </a></li>
                <li><a href="javascript:void(0);" onclick="showStep(20)">3.19 Participación en cuerpos colegiados</a></li>
                {{-- Bucle para formularios dinámicos de la sección 3 --}}
                @foreach($dynamicForms as $form)
                    @if(Str::startsWith($form->form_type, '3.') && !in_array($form->form_type, $staticFormTypes))
                        @php $dynamicFormIndex++; 
                        $renderData = $renderDataByForm[$form->id] ?? $form->form_data;@endphp
                        <li><a href="javascript:void(0);" onclick="showStep({{ $staticStepCount + $dynamicFormIndex }})">{{ $form->form_name }}</a></li>
                    @endif
                @endforeach
            </ul>
        </nav>

        <body class="font-sans antialiased" style="margin-left: 300px;">
            <x-general-header />
             <ul class="actv3"><i class="fas fa-chalkboard-teacher"></i>&nbsp;Calidad en la docencia:
                 <li><a href="javascript:void(0);" onclick="showStep(1)">3.1 Participación en actividades de diseño curricular</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(2)">3.2 Calidad del desempeño docente evaluada por el alumnado</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(3)">3.3 Publicaciones relacionadas con la docencia</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(4)">3.4 Distinciones académicas recibidas por el docente</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(5)">3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD y por CAAC</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(6)">3.6 Capacitación y actualización pedagógica recibida</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(7)">3.7 Cursos de actualización disciplinaria recibidos dentro de su área de conocimiento</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(8)">3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de educación, continua o de formación y capacitación docente</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(9)">3.8.1 RSU </a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(10)">3.9 Trabajos dirigidos para la titulación de estudiantes</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(11)">3.10 Tutorías a estudiantes</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(12)">3.11 Asesoría a estudiantes</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(13)">3.12 Publicaciones de investigación relacionadas con el contenido de los PE que imparte el docente</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(14)">3.13 Proyectos académicos de investigación</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(15)">3.14 Participación como ponente en congresos o eventos académicos del Área de Conocimiento o afines del docente</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(16)">3.15 Registro de patentes y productos de investigación tecnológica y educativa</a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(17)">3.16 Actividades de arbitraje, revisión, correción y edición </a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(18)">3.17 Proyectos académicos de extensión y difusión </a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(19)">3.18 Organización de congresos o eventos institucionales del área de conocimiento de la ó el Docente </a></li>
                 <li><a href="javascript:void(0);" onclick="showStep(20)">3.19 Participación en cuerpos colegiados</a></li>
                 
                 {{-- Bucle para formularios dinámicos de la sección 3 (segundo menú) --}}
                 @php $dynamicFormIndex = 0; @endphp
                 @foreach($dynamicForms as $form)
                    @if(Str::startsWith($form->form_type, '3.') && !in_array($form->form_type, $staticFormTypes))
                        @php $dynamicFormIndex++; 
                        $renderData = $renderDataByForm[$form->id] ?? $form->form_data;
                        @endphp
                        <li><a href="javascript:void(0);" onclick="showStep({{ $staticStepCount + $dynamicFormIndex }})">{{ $form->form_name }}</a></li>
                    @endif
                @endforeach
             </ul>
        </x-nav-docentes>
    @endif
    <div class="bg-gray-50 text-black/50" style="margin-left: 330px;">
            <div id="instrucionEdit">
            <p>*Nota: Para editar una de las tablas de los formularios, haga clic en el botón ✎ Editar Formulario. <br> También podrá dirigirse a este elemento haciendo clic en cualquiera de los formularios deseados, ubicados en la barra de menú al lado izquierdo.</p>
            </div>
            <button id="toggle-dark-mode" class="btn btn-secondary"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>
            <button id="edit-form-btn" class="btn btn-info"><i class="fa-solid fa-pencil"></i>&nbsp;Editar Formulario</button>

            <div class="bg-gray-50 text-black/50">
                <div class="relative min-h-screen flex flex-col items-center justify-center">
                    <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                            <div class="flex lg:justify-center lg:col-start-2"></div>

                            <nav class="-mx-3 flex flex-1 justify-end"></nav>

                            <div id="step1" style="display: block">
                                <form id="form3_1" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store31', 'form3_1');">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="docente">
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
                                                <th class="text-center actividades">Cantidad</th>
                                                <th class="actividades">Subtotal</th>
                                                <th colspan="1"></th>
                                                <th class="text-center fw-normal table-ajust2" scope="col">Observaciones</th>

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
                                                <td><label id="elaboracionSubTotal1" for="" type="text" placeholder="Comenta aqui"></label></td>
                                                <td class="comision actv"><span id="comisionIncisoA"></span></td>
                                                <td class="td_obs"><input id="obs3_1_1" class="table-header" type="text" placeholder="Comenta aqui" placeholder="Escribe tu comentario aquí"></td>
                                            </tr>   
                                                <tr>
                                                    <td>b)</td>
                                                    <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                                    <td><label class="form3_1LabelDoc" for="">Colaboración en la Comisión para la elaboración del documento</label></td>
                                                    <td><span id="puntaje40"><b>40</b></span></td>
                                                    <td class="elabInput"><input id="elaboracion2" name="elaboracion2" type="number"
                                                            oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion2') }}"></td>
                                                    <td><label id="elaboracionSubTotal2" for="" type="text" placeholder="Comenta aqui"></label>
                                                    </td>
                                                    <td class="comision actv"><span id="comisionIncisoB"></span></td>
                                                    <td class="td_obs"><input id="obs3_1_2" class="table-header" type="text" placeholder="Comenta aqui" placeholder="Escribe tu comentario aquí"></td>
                                                </tr>
                                                <tr>
                                                    <td>c)</td>
                                                    <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                                    <td><label class="form3_1LabelDoc">Elaboración de contenidos mínimos</label></td>
                                                    <td><label id="puntaje10" for=""><b>10</b></label></td>
                                                    <td class="elabInput"><input id="elaboracion3" name="elaboracion3" type="number"
                                                            oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion3') }}"></td>
                                                    <td><label id="elaboracionSubTotal3" for="" type="text" placeholder="Comenta aqui"></label>
                                                    </td>
                                                    <td class="comision actv"><span id="comisionIncisoC"></span></td>
                                                    <td class="td_obs"><input id="obs3_1_3" class="table-header" type="text" placeholder="Comenta aqui" placeholder="Escribe tu comentario aquí"></td>
                                                </tr>

                                                <tr>
                                                <td>d)</td>
                                                <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                                <td><label class="form3_1LabelDoc">Elaboración de programas de asignatura</label></td>
                                                <td><label id="puntaje20" for=""><b>20</b></label></td>
                                                <td class="elabInput"><input id="elaboracion4" name="elaboracion4" type="number"
                                                        oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion4') }}"></td>
                                                <td><label id="elaboracionSubTotal4" for="" type="text" placeholder="Comenta aqui"></label>
                                                </td>
                                                <td class="comision actv"><span id="comisionIncisoD"></span></td>
                                                <td class="td_obs"><input id="obs3_1_4" class="table-header" type="text" placeholder="Comenta aqui" placeholder="Escribe tu comentario aquí"></td>
                                                </tr>
                                                <tr>
                                                <td>e)</td>
                                                <td><label class="form3_1LabelActv" for="">Plan de estudios de una carrera o posgrado nuevo o actualización</label></td>
                                                <td><label class="form3_1LabelDoc">Actualización de programas de asignatura</label></td>
                                                <td><label id="p10" for=""><b>10</b></label></td>
                                                <td class="elabInput"><input id="elaboracion5" name="elaboracion5" type="number"
                                                        oninput="onActv3Subtotal()" value="{{ oldValueOrDefault('elaboracion5') }}"></td>
                                                <td><label id="elaboracionSubTotal5" for="" type="text" placeholder="Comenta aqui"></label>
                                                </td>
                                                <td class="comision actv"><span id="comisionIncisoE"></span></td>
                                                <td class="td_obs"><input id="obs3_1_5" class="table-header" type="text" placeholder="Comenta aqui"></td>
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
                            </div>

                            <div id="step2" style="display: none">
                                <form id="form3_2" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store32', 'form3_2');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="docente">
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
                                                <th class="table-ajust" scope="col" colspan="2"></th>
                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                                
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <td colspan="3" id="seccion3_2" style="height: 80px; width: 300px;">3.2 Calidad del desempeño docente evaluada por el alumnado</td>
                                                <td id="score3_2" for="">0</td>
                                                <td id="comision3_2">0</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1"></td>
                                                <td>Puntaje</td>
                                                <td class="text-center punto3_2">Cantidad</td>
                                                <td colspan="2"></td>
                                                <td class="text-center table-ajust" scope="col">Observaciones</td>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <!--prom90-100-->
                                            <tr>
                                                <td class="ranges text-center">
                                                    Promedio 90-100
                                                </td>
                                                <td id="ran1"><b>50</b></td>
                                                <td class="cantidad-cell"><input id="r1" type="number" placeholder="0"
                                                        oninput="onActv3Puntaje()" value="{{ oldValueOrDefault('r1') }}"></td>
                                                <td id="cant1">0</td>
                                                <td class="td_obs"><span id="prom90_100"></td>
                                                <td class="td_obs"><input id="obs3_2_1" class="table-header" type="text" placeholder="Comenta aqui"></td>
                                            </tr>
                                            <!--prom80-90-->
                                            <tr>
                                                <td class="ranges text-center">
                                                    Promedio 80-90
                                                </td>
                                                <td id="ran2"><b>40</b></td>
                                                <td class="cantidad-cell"><input id="r2" type="number" placeholder="0"
                                                        oninput="onActv3Puntaje()" value="{{ oldValueOrDefault('r2') }}"></td>
                                                <td id="cant2">0</td>
                                                <td class="td_obs"><span id="prom80_90"></td>
                                                <td class="td_obs"><input id="obs3_2_2" class="table-header" type="text" placeholder="Comenta aqui"></td>
                                            </tr>
                                            <!--prom70-80-->
                                            <tr>
                                                <td class="ranges text-center">
                                                    Promedio 70-80
                                                </td>
                                                <td id="ran3"><b>30</b></td>
                                                <td class="cantidad-cell"><input id="r3" type="number" placeholder="0"
                                                        oninput="onActv3Puntaje()" value="{{ oldValueOrDefault('r3') }}"></td>
                                                <td id="cant3">0</td>
                                                <td class="td_obs"><span id="prom70_80"></td>
                                                <td class="td_obs"><input id="obs3_2_3" class="table-header" type="text" placeholder="Comenta aqui"></td>
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
                            </div>
                            <div id="step3" style="display: none">
                                <form id="form3_3" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store33', 'form3_3');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}}
                                    <input type="hidden" name="user_type" value="docente">
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
                                                <th colspan="4" class="table-ajust" scope="col"></th>
                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <td  colspan="5"id="seccion3_3">3.3 Publicaciones relacionadas con la docencia</td>
                                                    
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
                                                    <td class="text-center">Puntaje</td>
                                                    <td id="cantidadform3_3">Cantidad</td>
                                                    <td>SubTotal</td>
                                                    <td colspan="1"></td>
                                                    <td class="text-center table-ajust" scope="col">Observaciones</td>

                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>a)</td>
                                                    <td>Libro de texto con editorial de reconocido prestigio</td>
                                                    <td>Autor(a)</td>
                                                    <td id="p100" class="text-center">
                                                        <b>100</b>
                                                    </td>
                                                    <td class="cantidad-cell"><input id="rc1" type="number" oninput="onActv3SubTotal3()" value="{{ oldValueOrDefault('rc1') }}">
                                                    </td>
                                                    <td id="stotal1"></td>
                                                    <td class="td_obs comision actv"> <span id="comIncisoA"></span></td>
                                                    <td class="td_obs"><input id="obs3_3_1" class="table-header" type="text" placeholder="Comenta aqui"></td>
                                                </tr>
                                                <tr>
                                                    <td>b)</td>
                                                    <td>1. Paquete didáctico, 2. Manual de operaciones</td>
                                                    <td>Autor(a)</td>
                                                    <td id="p50" class="text-center">
                                                        <b>50</b>
                                                    </td>
                                                    <td class="cantidad-cell"><input id="rc2" type="number" 
                                                            oninput="onActv3SubTotal3()" value="{{ oldValueOrDefault('rc2') }}">
                                                    </td>
                                                    <td id="stotal2"></td>
                                                    <td class="td_obs comision actv"> <span id="comIncisoB"></span></td>
                                                    <td class="td_obs"><input id="obs3_3_2" class="table-header" type="text" placeholder="Comenta aqui"></td>
                                                </tr>
                                                <tr>
                                                    <td>c)</td>
                                                    <td>
                                                        <textarea name="" class="textAreaForms" cols="30" rows="10">1. Capítulo de libro, 2. Elaboración de Manuales de laboratorio o instructivos, 3. Diseño y construcción de equipo de laboratorio, 4. Elaboración de material audiovisual, 5. Elaboración de software educativo, 6. Notas de curso, 7. Antología comentada, 8.Monografía.</textarea>
                                                    </td>
                                                    <td>Autor(a)</td>
                                                    <td id="p30" class="text-center">
                                                        <b>30</b>
                                                    </td>
                                                    <td class="cantidad-cell"><input id="rc3" type="number"
                                                            oninput="onActv3SubTotal3()" value="{{ oldValueOrDefault('rc3') }}">
                                                    </td>
                                                    <td id="stotal3"></td>
                                                    <td class="td_obs comision actv"> <span id="comIncisoC"></span></td>
                                                    <td class="td_obs"><input id="obs3_3_3" class="table-header" type="text" placeholder="Comenta aqui"></td>
                                                </tr>
                                                <tr>
                                                    <td>d)</td>
                                                    <td>1. Traducción de libro, 2.Traducción de material de apoyo didáctico,
                                                        3. Traducciones
                                                        publicadas de artículos.</td>
                                                    <td>Autor(a)</td>
                                                    <td id="p25" class="text-center">
                                                        <b>25</b>
                                                    </td>
                                                    <td class="cantidad-cell"><input id="rc4" type="number"
                                                            oninput="onActv3SubTotal3()" value="{{ oldValueOrDefault('rc4') }}">
                                                    </td>
                                                    <td id="stotal4"></td>
                                                    <td class="td_obs comision actv"> <span id="comIncisoD"></span></td>
                                                    <td class="td_obs"><input id="obs3_3_4" class="table-header" type="text" placeholder="Comenta aqui"></td>
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
                            </div>

                            <div id="step4" style="display: none">
                                <form id="form3_4" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store34', 'form3_4');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="docente">
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
                                                <th colspan="3" class="table-ajust" scope="col"></th>
                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th id="seccion3_4" colspan="2" class="punto3_4" scope="col" style="padding:30px;">3.4 Distinciones
                                                    académicas recibidas por el docente</th>
                                                <th colspan="2"></th>
                                                <th id="score3_4">0</th>
                                                <th id="comision3_4">0</th>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td class="punto3_4">Puntaje</td>
                                                <td class="text-center punto3_4">Cantidad</td>
                                                <td colspan="2"></td>
                                                <td class="text-center table-ajust" scope="col">Observaciones</td>
                                            </tr>
                                            <tr>
                                                <td class="punto3_4">a)</td>
                                                <td>Internacional</td>
                                                <td id="p60"><b>60</b></td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantInternacional" placeholder="0" oninput="onActv3SubTotal3_4()" value="{{ oldValueOrDefault('cantInternacional') }}"></td>
                                                <td id="cantInternacional2"></td>
                                                <td class="td_obs"><span id="comInternacional"></span></td>
                                                <td class="td_obs"><input id="obs3_4_1" class="table-header" type="text" placeholder="Comenta aqui"></td>
                                            </tr>
                                            <tr>
                                                <td class="punto3_4">b)</td>
                                                <td>Nacional</td>
                                                <td id="p30Nac"><b>30</b></td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantNacional" placeholder="0" oninput="onActv3SubTotal3_4()" value="{{ oldValueOrDefault('cantNacional') }}"></td>
                                                <td id="cantNacional2"></td>
                                                <td class="td_obs"><span id="comNacional"></span></td>
                                                <td class="td_obs"><input id="obs3_4_2" class="table-header" type="text" placeholder="Comenta aqui"></td>
                                            </tr>
                                            <tr>
                                                <td class="punto3_4">c)</td>
                                                <td>Regional o estatal</td>
                                                <td id="p20"><b>20</b></td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantidadRegional" placeholder="0" oninput="onActv3SubTotal3_4()" value="{{ oldValueOrDefault('cantidadRegional') }}"></td>
                                                <td id="cantidadRegional2"></td>
                                                <td class="td_obs"><span id="comRegional"></span></td>
                                                <td class="td_obs"><input id="obs3_4_3" class="table-header" type="text" placeholder="Comenta aqui"></td>
                                            </tr>
                                            <tr>
                                                <td class="punto3_4">d)</td>
                                                <td>Preparación de grupos de alumnado para olimpiadas competencias académicas o exámenes generales.</td>
                                                <td id="p30Prep"><b>30</b></td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantPreparacion" placeholder="0" oninput="onActv3SubTotal3_4()" value="{{ oldValueOrDefault('cantPreparacion') }}"></td>
                                                <td id="cantPreparacion2"></td>
                                                <td class="td_obs"><span id="comPreparacion"></span></td>
                                                <td class="td_obs"><input id="obs3_4_4" class="table-header" type="text" placeholder="Comenta aqui"></td>
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
                            </div>
                            
                            <div id="step5" style="display: none">
                                <form id="form3_5" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store35', 'form3_5');">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                 
                                <input type="hidden" name="user_type" value="docente">
                                <input type="hidden" name="user_type" value="docente">
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
                                            <th colspan="3" class="table-ajust" scope="col"></th>
                        
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                            </th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <thead>
                                            <tr>
                                                <th id="seccion3_5" colspan=2 class="punto3_5" scope=col
                                                    style="padding:30px;">3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD y por CAAC
                                                </th>
                                                <td colspan="2"></td>
                                                <td id="score3_5" for="">0</td>
                                                <td id="comision3_5">0</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td class="punto3_5">Puntaje</td>
                                                <td class="punto3_5">Cantidad</td>
                                                <td colspan="2"></td>
                                                <td class="text-center table-ajust" scope="col">Observaciones</td>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <td class="punto3_5">a)</td>
                                            <td>Evaluado por la persona titular de DA</td>
                                            <td id="p35"><b>35</b></td>
                                            <td><input type="number" id="cantDA"
                                                    oninput="onActv3SubTotal3_5()" value="{{ oldValueOrDefault('cantDA') }}"></td>
                                            <td id="cantDA2"></td>
                                            <td class="td_obs"><span id="comDA"></span></td>
                                            <td class="td_obs"><input id="obs3_5_1" class="table-header" type="text" placeholder="Comenta aqui"></td>
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
                                                <td class="td_obs"><input type=" value" id="comNCAA"></span></td>
                                                <td class="td_obs"><input id="obs3_5_2" class="table-header" type="text" placeholder="Comenta aqui"></td>
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
                            </div>

                            <div id="step6" style="display: none">
                                <form id="form3_6" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store36', 'form3_6');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                                                       
                                    <input type="hidden" name="user_type" value="docente">
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
                                                <th colspan="2" class="table-ajust" scope="col"></th>
                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_6" colspan="3" class="punto3_6" scope=col>3.6 Capacitación y actualización pedagógica recibida </th>
                                                    <td id="score3_6" for="">0</td>
                                                    <td id="comision3_6">0</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td class="punto3_6">Factor</td>
                                                    <td class="punto3_6">Horas</td>
                                                    <td colspan="1"></td>
                                                    <td class="text-center table-ajust" scope="col">Observaciones</td>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>0.5 por cada hora</td>
                                                    <td id="pMedio">0.5</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_6"
                                                            oninput="onActv3SubTotal3_6()" value="{{ oldValueOrDefault('puntaje3_6') }}"></td>
                                                    <td id="puntajeHoras3_6"></td>
                                                    <td class="td_obs"><span id="comisionDict3_6"></span>
                                                    </td>
                                                    <td class="td_obs"><input id="obs3_6" id="obs3_6" class="table-header" type="text" placeholder="Comenta aqui">
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
                            </div>
                            <div id="step7" style="display: none">
                                <form id="form3_7" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store37', 'form3_7');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}} --}}
                                    <input type="hidden" name="user_type" value="docente">
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
                                                <th colspan="2" class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_7" colspan="3" class="punto3_7" scope=col>3.7 Cursos de actualización disciplinaria recibidos dentro de su área de conocimiento </th>

                                                    <td id="score3_7" for="">0</td>
                                                    <td id="comision3_7">0</td>

                                                </tr>
                                                <tr>
                                                    <td colspan="1"></td>
                                                    <td class="punto3_7">Factor</td>
                                                    <td class="punto3_7">Horas</td>
                                                    <td colspan="2"></td>
                                                    <td class="text-center table-ajust" scope="col">Observaciones</td>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>0.5 por cada hora</td>
                                                    <td id="pMedio2">0.5</td>
                                                    <td class="td_docente_cantidad"><input type="number" placeholder="0" id="puntaje3_7"
                                                            oninput="onActv3SubTotal3_7()" value="{{ oldValueOrDefault('puntaje3_7') }}"></td>
                                                    <td id="puntajeHoras3_7"></td>
                                                    <td class="td_obs"><span id="comisionDict3_7"></span>
                                                    </td>
                                                    <td class="td_obs"><input id="obs3_7" class="table-header" type="text" placeholder="Comenta aqui"></td>
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
                            </div>
                            
                            <div id="step8" style="display: none">
                                <form id="form3_8" method="POST"
                                    onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store38', 'form3_8');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}} --}}
                                    <input type="hidden" name="user_type" value="docente">
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
                                                <th colspan="2" class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_8" colspan="3" class="punto3_8" scope=col>3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de educación, continua ó de formación y capacitación docente </th>

                                                    <td id="score3_8" for="">0</td>
                                                    <td id="comision3_8">0</td>

                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td class="text-center punto3_8">Factor</td>
                                                    <td class="text-center punto3_8">Horas</td>
                                                    <td colspan="1"></td>
                                                    <td class="text-center table-ajust" scope="col">Observaciones</td>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>1 por cada hora</td>
                                                    <td id="p3_8">1</td>
                                                    <td class="td_docente_cantidad"><input type="number" placeholder="0" id="puntaje3_8"
                                                            oninput="onActv3SubTotal3_8()" value="{{ oldValueOrDefault('puntaje3_8') }}"></td>
                                                    <td id="puntajeHoras3_8"></td>
                                                    <td class="td_obs"><span id="comisionDict3_8"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_8" type="text" placeholder="Comenta aqui"></td>
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
                            </div>
                            <div id="step9" style="display: none">
                                <form id="form3_8_1" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store381', 'form3_8_1');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}} --}}
                                    <input type="hidden" name="user_type" value="docente">
                                    @csrf
                                    <div>
                                        <!--3.8.1 RSU -->
                                        <h4>Puntaje máximo 
                                        @if($userType != 'secretaria') <!-- fetch puntajeMaximo form3_8_1 -->
                                            <span class="bg-black text-white px-4 mt-3" id="puntajeMaximo" for="">40</span>
                                        @endif
                                        </h4>
                                    </div>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Actividad</th>
                                                <th colspan="2" class="table-ajust" scope="col"></th>
                                                
                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_8_1" colspan="3" class="punto3_8_1" scope=col title="Responsabilidad Social Universitaria">3.8.1 RSU</th>

                                                    <td id="score3_8_1" for="">0</td>
                                                    <td id="comision3_8_1">0</td>

                                                </tr>
                                                <tr>
                                                    <td colspan="1"></td>
                                                    <td class="text-left punto3_8_1">Factor</td>
                                                    <td class="text-right punto3_8_1 centerSelect">Horas</td>
                                                    <td colspan="2"></td>
                                                    <td class="text-center table-ajust" scope="col">Observaciones</td>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <td>1 por cada hora</td>
                                                    <td id="p3_8_1">1</td>
                                                    <td class="td_docente_cantidad"><input type="number" placeholder="0" id="puntaje3_8_1" oninput="onActv3SubTotal3_8_1()"
                                                            value="{{ oldValueOrDefault('puntaje3_8_1') }}"></td>
                                                    <td id="puntajeHoras3_8_1"></td>
                                                    <td class="td_obs"><span id="comisionDict3_8_1"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_8_1" type="text" placeholder="Comenta aqui"></td>
                                                </tr>
                                            </thead>
                                            <!--Tabla informativa Acreditacion Actividad 3.8.1-->
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
                            </div>
                            <div id="step10" style="display: none">    
                                <div>
                                    <h4>Puntaje máximo
                                        <label class="bg-black text-white px-4 mt-3">200</label>
                                    </h4>
                                </div>    
                                    <form id="form3_9" method="POST"onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store39', 'form3_9');">
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                         
                                        {{-- {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}} --}}
                                        <input type="hidden" name="user_type" value="docente">
                                        @csrf
                                        <table class="table table-sm tutorias">

                                            <thead>
                                                <tr>
                                                    <th colspan="7" class="text-center">
                                                        <h3>Tutorias</h3>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <thead>
                                                <tr>
                                                    <th scope="col">Actividad</th>
                                                    <th colspan="5" class="table-ajust" scope="col"></th>
                                                    <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                    <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                                </tr>
                                            </thead>

                                            <thead>
                                                <tr>
                                                    <th id="seccion3_9" scope="col" class="p3_9" colspan="6">3.9 Trabajos dirigidos para la titulación de estudiantes</th>
                                                    <th id="score3_9">0</th>
                                                    <th id="comision3_9">0</th>
                                                </tr>
                                                <tr>
                                                    <th class="acreditacion">Incisos</th>
                                                    <th class="acreditacion">Actividad</th>
                                                    <th class="acreditacion">Obra</th>
                                                    <th class="acreditacion">Nivel</th>
                                                    <th class="acreditacion">Puntaje</th>
                                                    <th class="text-center acreditacion">Cantidad</th>
                                                    <th class="acreditacion">Subtotal</th>
                                                    <th class="table-ajust" scope="col"></th>
                                                    <th class="text-center acreditacion">Observaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr>
                                                    <td>a)</td>
                                                    <td>Revisión de</td>
                                                    <td>Tesis</td>
                                                    <td>Doctorado</td>
                                                    <td id="puntajeTutorias20_1">20</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_1" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_1') }}"></td>
                                                    <td id="tutorias1">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision1"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_1" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>b)</td>
                                                    <td>Proyecto de</td>
                                                    <td>Tesis</td>
                                                    <td>Maestría</td>
                                                    <td id="puntajeTutorias15_1">15</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_2" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_2') }}"></td>
                                                    <td id="tutorias2">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision2"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_2" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>c)</td>
                                                    <td>Proyecto de</td>
                                                    <td>Tesis y otras</td>
                                                    <td>TSU, Lic y especialidad</td>
                                                    <td id="puntajeTutorias10_1">10</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_3" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_3') }}"></td>
                                                    <td id="tutorias3">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision3"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_3" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>d)</td>
                                                    <td>Dirección trabajo en realización</td>
                                                    <td>Tesis</td>
                                                    <td>Doctorado</td>
                                                    <td id="puntajeTutorias55">55</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_4" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_4') }}"></td>
                                                    <td id="tutorias4">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision4"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_4" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>e)</td>
                                                    <td>Dirección trabajo en realización</td>
                                                    <td>Tesis</td>
                                                    <td>Maestria</td>
                                                    <td id="puntajeTutorias45">45</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_5" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_5') }}"></td>
                                                    <td id="tutorias5">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision5"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_5" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>f)</td>
                                                    <td>Dirección trabajo en realización</td>
                                                    <td>Tesis y otras</td>
                                                    <td>TSU, Lic y especialidad</td>
                                                    <td id="puntajeTutorias35">35</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_6" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_6') }}"></td>
                                                    <td id="tutorias6">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision6"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_6" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>g)</td>
                                                    <td>Dirección trabajo terminado</td>
                                                    <td>Tesis</td>
                                                    <td>Doctorado</td>
                                                    <td id="puntajeTutorias70">70</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_7" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_7') }}"></td>
                                                    <td id="tutorias7">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision7"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_7" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>h)</td>
                                                    <td>Dirección trabajo terminado</td>
                                                    <td>Tesis</td>
                                                    <td>Maestría</td>
                                                    <td id="puntajeTutorias60">60</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_8" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_8') }}"></td>
                                                    <td id="tutorias8">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision8"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_8" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>i)</td>
                                                    <td>Dirección trabajo terminado</td>
                                                    <td>Tesis y otras</td>
                                                    <td>TSU, Lic y especialidad</td>
                                                    <td id="puntajeTutorias50">50</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_9" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_9') }}"></td>
                                                    <td id="tutorias9">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision9"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_9" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>j)</td>
                                                    <td>Revisión de trabajo terminado</td>
                                                    <td>Tesis</td>
                                                    <td>Doctorado</td>
                                                    <td id="puntajeTutorias30_1">30</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_10" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_10') }}"></td>
                                                    <td id="tutorias10">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision10"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_10" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>k)</td>
                                                    <td>Revisión de trabajo terminado</td>
                                                    <td>Tesis</td>
                                                    <td>Maestría</td>
                                                    <td id="puntajeTutorias20_2">50</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_11" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_11') }}"></td>
                                                    <td id="tutorias11">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision11"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_11" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>l)</td>
                                                    <td>Revisión de trabajo terminado</td>
                                                    <td>Tesis y otras</td>
                                                    <td>TSU, Lic y especialidad</td>
                                                    <td id="puntajeTutorias15_2">15</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_12" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_12') }}"></td>
                                                    <td id="tutorias12">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision12"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_12" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>m)</td>
                                                    <td>Sinodalía</td>
                                                    <td>Examen</td>
                                                    <td>Doctorado</td>
                                                    <td id="puntajeTutorias30_2">30</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_13" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_13') }}"></td>
                                                    <td id="tutorias13">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision13"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_13" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>n)</td>
                                                    <td>Sinodalía</td>
                                                    <td>Examen</td>
                                                    <td>Maestría</td>
                                                    <td id="puntajeTutorias20_3">15</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_14" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_14') }}"></td>
                                                    <td id="tutorias14">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision14"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_14" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>o)</td>
                                                    <td>Sinodalía</td>
                                                    <td>Examen</td>
                                                    <td>TSU, Lic y especialidad</td>
                                                    <td id="puntajeTutorias15_3">15</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_15" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_15') }}"></td>
                                                    <td id="tutorias15">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision15"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_15" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>p)</td>
                                                    <td>Distinciones</td>
                                                    <td></td>
                                                    <td>Doctorado</td>
                                                    <td id="puntajeTutorias15_4">15</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_16" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_16') }}"></td>
                                                    <td id="tutorias16">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision16"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_16" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                                <tr>
                                                    <td>q)</td>
                                                    <td>Distinciones</td>
                                                    <td></td>
                                                    <td>Maestría</td>
                                                    <td id="puntajeTutorias10_2">10</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="puntaje3_9_17" oninput="onActv3SubTotal3_9()" value="{{ oldValueOrDefault('puntaje3_9_17') }}"></td>
                                                    <td id="tutorias17">0</td>
                                                    <td class="td_obs"><span id="tutoriasComision17"></span></td>
                                                    <td class="td_obs"><input class="table-header" id="obs3_9_17" type="text" placeholder="Comenta aqui"></td>
                                                </tr>

                                            </tbody>

                                        </table>
                                        <!-- 📌 TABLA INFORMATIVA CORRECTAMENTE COLOCADA FUERA DEL tbody Y FUERA DEL TABLE PRINCIPAL -->
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="acreditacion" scope="col">Acreditación:</th>
                                                    <th class="descripcion"><b>DSE para pregrado, DIIP para posgrado</b></th>
                                                    <th>
                                                        <button id="btn3_9" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </form>
                            </div> 
                        </div>
                        <div id="step11" style="display: none">
                                <form id="form3_10" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store310', 'form3_10');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}} --}}
                                    <input type="hidden" name="user_type" value="docente">
                                    @csrf
                                        <!--3.10 Trabajos dirigidos para la titulación de estudiantes-->
                                        <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3" for="">115</label>
                                        </h4>
                                        <table class="table table-sm tutorias">
                                            <thead>
                                                <tr>
                                                    <th scope="col" colspan=3>Actividad</th>
                                                    <th colspan="5" class="table-ajust" scope="col"></th>
                                                    <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                    <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                    </th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_10" class="acreditacion" colspan=2>3.10 Tutorías a estudiantes</th>
                                                    <th colspan="6"></th>
                                                    <th id="score3_10">0</th>
                                                    <th id="comision3_10">0</th>

                                                </tr>
                                                <tr>
                                                    <th colspan="2"></th>
                                                    <th class="acreditacion">Puntaje</th>
                                                    <th class="text-center acreditacion form3_10Cantidad">Cantidad</th>
                                                    <th colspan="6"></th>
                                                    <th class="text-center acreditacion" scope="col">Observaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <thead>
                                                <tr>
                                                    <!--Tutorias a estudantes 3_10 individuales, grupales -->
                                                    <td>a)</td>
                                                    <td>Por alumno(a) por semestre, grupales</td>
                                                    <td id="puntajeGrupales">3</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="grupalesCant" oninput="onActv3SubTotal3_10()"
                                                            value="{{ oldValueOrDefault('grupalesCant') }}">
                                                    </td>
                                                    <td colspan="4"></td>

                                                    <td id="evaluarGrupales"></td>
                                                    <td class="td_obs"><span id="comisionGrupal"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsGrupal"></td>
                                                </tr>
                                                <tr>
                                                    <td>b)</td>
                                                    <td>Por alumno(a) por semestre, individuales</td>
                                                    <td id="puntajeIndividual">6</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="individualCant" oninput="onActv3SubTotal3_10()"
                                                            value="{{ oldValueOrDefault('individualCant') }}">
                                                    </td>
                                                    <td colspan="4"></td>
                                                    <td id="evaluarIndividual"></td>
                                                    <td class="td_obs"><span id="comisionIndividual"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsIndividual"></td>
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
                        </div>
                        <div id="step12" style="display: none">
                                <form id="form3_11" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store311', 'form3_11');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}} --}}
                                    <input type="hidden" name="user_type" value="docente">
                                    @csrf
                                    <!--3.11 Trabajos dirigidos para la titulación de estudiantes-->
                                    <h4>Puntaje máximo
                                        <label class="bg-black text-white px-4 mt-3" for="">95</label>
                                    </h4>
                                    <table class="table table-sm tutorias">
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan=3>Actividad</th>
                                                <th colspan="6" class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th id="seccion3_11" class="acreditacion" colspan="9">3.11 Asesoría a estudiantes</th>
                                                <th id="score3_11">0</th>
                                                <th id="comision3_11">0</th>
                                                
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th class="acreditacion">Incisos</th>
                                                <th class="acreditacion">Documento</th>
                                                <th class="acreditacion">Actividad</th>
                                                <th class="acreditacion">Puntaje</th>
                                                <th class="text-center acreditacion">Cantidad</th>
                                                <th colspan="4"></th>
                                                <th class="text-center acreditacion">Subtotal</th>
                                                <th colspan="1"></th>
                                                <th class="text-center acreditacion" scope="col">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--3_11 Asesoria a estudiantes incisos-->
                                            <tr>
                                                <td>a)</td>
                                                <td>Asesorías académicas</td>
                                                <td>Por alumno(a), por semestre</td>
                                                <td id="academica">5</td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantAsesoria"
                                                        oninput="onActv3SubTotal3_11()" value="{{ oldValueOrDefault('cantAsesoria') }}"></td>
                                                <td colspan="4"></td>
                                                <td id="subtotalAsesoria"></td>
                                                <td class="td_obs"><span id="comisionAsesoria"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsAsesoria"></td>
                                            </tr>
                                            <tr>
                                                <td>b)</td>
                                                <td>Servicio social*</td>
                                                <td>Por alumno(a), por semestre</td>
                                                <td id="servicio">20</td>
                                                <td class="td_docente_cantidad"><input type="number" placeholder="0" id="cantServicio"
                                                        oninput="onActv3SubTotal3_11()" value="{{ oldValueOrDefault('cantServicio') }}"></td>
                                                <td colspan="4"></td>
                                                <td id="subtotalServicio"></td>
                                                <td class="td_obs"><span id="comisionServicio"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsServicio"></td>
                                            </tr>
                                            <tr>
                                                <td>c)</td>
                                                <td>Prácticas profesionales</td>
                                                <td>Por alumno(a), por semestre</td>
                                                <td id="practicas">20</td>
                                                <td class="td_docente_cantidad"><input type="number" placeholder="0" id="cantPracticas"
                                                        oninput="onActv3SubTotal3_11()" value="{{ oldValueOrDefault('cantPracticas') }}"></td>
                                                <td colspan="4"></td>
                                                <td id="subtotalPracticas"></td>
                                                <td class="td_obs"><span id="comisionPracticas"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPracticas"></td>
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
                            </div> 
                            <div id="step13" style="display: none">     
                                <form id="form3_12" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store312', 'form3_12');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}} --}}
                                    <input type="hidden" name="user_type" value="docente">
                                    @csrf
                                        <!--3.12 Trabajos dirigidos para la titulación de estudiantes-->
                                        <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3" for="">150</label>
                                        </h4>
                                        <table class="table table-sm tutorias">
                                            <thead>
                                                <tr>
                                                    <th scope="col" colspan=3>Actividad</th>
                                                    <th colspan="5" class="table-ajust" scope="col"></th>
                                                    <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                    <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_12" class="acreditacion" colspan="8">3.12 Publicaciones de
                                                        investigación
                                                        relacionadas con el
                                                        contenido
                                                        de los PE que imparte el docente</th>
                                                    
                                                    <th id="score3_12">0</th>
                                                    <th id="comision3_12">0</th>
                                                    
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <th class="acreditacion">Incisos</th>
                                                    <th class="acreditacion">Actividad</th>
                                                    <th class="acreditacion">Obra</th>
                                                    <th class="acreditacion">Nivel</th>
                                                    <th class="acreditacion">Puntaje</th>
                                                    <th class="text-center acreditacion">Cantidad</th>
                                                    <th colspan="2" ></th>
                                                    <th class="acreditacion">Subtotal</th>
                                                    <th colspan="1" ></th>
                                                    <th class="text-center acreditacion" scope="col">Observaciones</th>
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
                                                    <td class="td_docente_cantidad"><input type="number" id="cantCientifico"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantCientifico') }}"></td>
                                                    <td colspan="2"></td>
                                                    <td id="subtotalCientificos"></td>
                                                    <td class="td_obs"><span id="comisionCientificos"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCientificos"></td>
                                                </tr>
                                                <tr>
                                                    <td>b)</td>
                                                    <td>Autor(a) o coautor(a) de libros de divulgación</td>
                                                    <td>--</td>
                                                    <td>--</td>
                                                    <td id="puntajeDivulgacion"><b>50</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantDivulgacion"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantDivulgacion') }}"></td>
                                                    <td colspan="2"></td>
                                                    <td id="subtotalDivulgacion"></td>
                                                    <td class="td_obs"><span id="comisionDivulgacion"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsDivulgacion"></td>
                                                </tr>
                                                <tr>
                                                    <td>c)</td>
                                                    <td>Traducción de libros</td>
                                                    <td>--</td>
                                                    <td>--</td>
                                                    <td id="puntajeTraduccion"><b>40</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantTraduccion"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantTraduccion') }}"></td>
                                                    <td colspan="2"></td>
                                                    <td id="subtotalTraduccion"></td>
                                                    <td class="td_obs"><span id="comisionTraduccion"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsTraduccion"></td>
                                                </tr>
                                                <tr>
                                                    <td>d)</td>
                                                    <td>Autor(a) o coautor(a) de artículos</td>
                                                    <td>Con Arbitraje</td>
                                                    <td>Internacional</td>
                                                    <td id="puntajeArbitrajeInt">60</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantArbitrajeInt"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantArbitrajeInt') }}">
                                                    </td>
                                                    <td colspan="2"></td>
                                                    <td id="subtotalArbitrajeInt"></td>
                                                    <td class="td_obs"><span id="comisionArbitrajeInt"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsArbitrajeInt"></td>
                                                </tr>
                                                <tr>
                                                    <td>e)</td>
                                                    <td>Autor(a) o coautor(a) de artículos</td>
                                                    <td>Con Arbitraje</td>
                                                    <td>Nacional</td>
                                                    <td id="puntajeArbitrajeNac">30</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantArbitrajeNac"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantArbitrajeNac') }}">
                                                    </td>
                                                    <td colspan="2"></td>
                                                    <td id="subtotalArbitrajeNac"></td>
                                                    <td class="td_obs"><span id="comisionArbitrajeNac"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsArbitrajeNac"></td>
                                                </tr>
                                                <tr>
                                                    <td>f)</td>
                                                    <td>Autor(a) o coautor(a) de artículos</td>
                                                    <td>Sin Arbitraje</td>
                                                    <td>Internacional</td>
                                                    <td id="puntajeSinInt">15</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantSinInt"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantSinInt') }}"></td>
                                                        <td colspan="2"></td>
                                                    <td id="subtotalSinInt"></td>
                                                    <td class="td_obs"><span id="comisionSinInt"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsSinInt"></td>
                                                </tr>
                                                <tr>
                                                    <td>g)</td>
                                                    <td>Autor(a) o coautor(a) de artículos</td>
                                                    <td>Sin Arbitraje</td>
                                                    <td>Nacional</td>
                                                    <td id="puntajeSinNac">10</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantSinNac"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantSinNac') }}"></td>
                                                        <td colspan="2"></td>
                                                    <td id="subtotalSinNac"></td>
                                                    <td class="td_obs"><span id="comisionSinNac"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsSinNac"></td>
                                                </tr>
                                                <tr>
                                                    <td>h)</td>
                                                    <td>Capítulo de libro especializado</td>
                                                    <td>Autor(a) o coautor (a) de capítulo de libro internacional o nacional</td>
                                                    <td>--</td>
                                                    <td id="puntajeAutor">25</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantAutor"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantAutor') }}"></td>
                                                        <td colspan="2"></td>
                                                    <td id="subtotalAutor"></td>
                                                    <td class="td_obs"><span id="comisionAutor"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsAutor"></td>
                                                </tr>
                                                <tr>
                                                    <td>i)</td>
                                                    <td>Capítulo de libro especializado</td>
                                                    <td>Editor(a) o coeditor(a) de libro</td>
                                                    <td>--</td>
                                                    <td id="puntajeEditor">25</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantEditor"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantEditor') }}"></td>
                                                        <td colspan="2"></td>
                                                    <td id="subtotalEditor"></td>
                                                    <td class="td_obs"><span id="comisionEditor"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsEditor"></td>
                                                </tr>
                                                <tr>
                                                    <td>j)</td>
                                                    <td>Sitio web</td>
                                                    <td>Diseño de sitio web</td>
                                                    <td>--</td>
                                                    <td id="puntajeWeb">20</td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantWeb"
                                                            oninput="onActv3SubTotal3_12()" value="{{ oldValueOrDefault('cantWeb') }}"></td>
                                                        <td colspan="2"></td>
                                                    <td id="subtotalWeb"></td>
                                                    <td class="td_obs"><span id="comisionWeb"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsWeb"></td>
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
                            </div>
                            <div id="step14" style="display: none">  
                                <form id="form3_13" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store313', 'form3_13');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}} --}}
                                    <input type="hidden" name="user_type" value="docente">
                                    @csrf
                                    <!--3.13 Proyectos académicos de investigación-->
                                    <h4>Puntaje máximo
                                        <label class="bg-black text-white px-4 mt-3" for="">130</label>
                                    </h4>
                                    <table class="table table-sm tutorias">
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan=3>Actividad</th>
                                                <th colspan="4" class="table-ajust" scope="col"></th>
                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <th id="seccion3_13" class="acreditacion" colspan="7">3.13 Proyectos académicos de
                                                investigación</th>
                                            <th id="score3_13">0</th>
                                            <th id="comision3_13">0</th>
                                            
                                        </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th class="acreditacion">Incisos</th>
                                                <th class="acreditacion">Documento</th>
                                                <th class="acreditacion">Puntaje</th>
                                                <th class="text-center acreditacion">Cantidad</th>
                                                <th colspan="3"></th>
                                                <th class="acreditacion">Subtotal</th>
                                                <th colspan="1"></th>
                                                <th class="text-center acreditacion" scope="col">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--Incisos 3.13-->
                                            <tr>
                                                <td>a)</td>
                                                <td>Inicio de proyecto de investigación con financiamiento externo</td>
                                                <td id="puntajeInicioFinanExt">50</td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantInicioFinanExt"
                                                        oninput="onActv3SubTotal3_13()" value="{{ oldValueOrDefault('cantInicioFinanExt') }}">
                                                </td>
                                                <td colspan="3"></td>
                                                <td id="subtotalInicioFinanExt"></td>
                                                <td class="td_obs"><span id="comisionInicioFinancimientoExt"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsInicioFinancimientoExt"></td>
                                            </tr>
                                            <tr>
                                                <td>b)</td>
                                                <td>Inicio de proyecto de investigación interno, aprobado por CAAC</td>
                                                <td id="puntajeInicioInvInterno">25</td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantInicioInvInterno"
                                                        oninput="onActv3SubTotal3_13()" value="{{ oldValueOrDefault('cantInicioInvInterno') }}">
                                                </td>
                                                <td colspan="3"></td>
                                                <td id="subtotalInicioInvInterno"></td>
                                                <td class="td_obs"><span id="comisionInicioInvInterno"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsInicioInvInterno"></td>
                                            </tr>
                                            <tr>
                                                <td>c)</td>
                                                <td>Reporte cumplido del periodo anual del proyecto de investigación con
                                                    financiamiento externo
                                                </td>
                                                <td id="puntajeReporteFinanciamExt">100</td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantReporteFinanciamExt"
                                                        oninput="onActv3SubTotal3_13()" value="{{ oldValueOrDefault('cantReporteFinanciamExt') }}">
                                                </td>
                                                <td colspan="3"></td>
                                                <td id="subtotalReporteFinanciamExt"></td>
                                                <td class="td_obs"><span id="comisionReporteFinanciamExt"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsReporteFinanciamExt"></td>
                                            </tr>
                                            <tr>
                                                <td>d)</td>
                                                <td>Reporte cumplido del periodo anual del proyecto de investigación interno,
                                                    aprobado por CAAC
                                                </td>
                                                <td id="puntajeReporteInvInt">50</td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantReporteInvInt"
                                                        oninput="onActv3SubTotal3_13()" value="{{ oldValueOrDefault('cantReporteInvInt') }}">
                                                </td>
                                                <td colspan="3"></td>
                                                <td id="subtotalReporteInvInt"></td>
                                                <td class="td_obs"><span id="comisionReporteInvInt"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsReporteInvInt"></td>
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
                            </div>   
                            <div id="step15" style="display: none">  
                                <form id="form3_14" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store314', 'form3_14');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}}
                                    <input type="hidden" name="user_type" value="docente">
                                    @csrf
                                <!--3.14 Participación como ponente en congresos o eventos académicos del Área de Conocimiento o afines del docente-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th  colspan="4" class="table-ajust" scope="col"></th>
                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_14" class="acreditacion" colspan="7">3.14 Participación como ponente
                                                en congresos
                                                o eventos
                                                académicos
                                                del Área de Conocimiento o afines del docente</th>
                                            <th id="score3_14">0</th>
                                            <th id="comision3_14">0</th>
                                            
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion" colspan=2>Congresos y eventos académicos</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="text-center acreditacion cantidad3_14">Cantidad</th>
                                            <th colspan="3"></th>
                                            <th class="acreditacion">Subtotal</th>
                                            <th colspan="1"></th>
                                            <th class="text-center acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--Incisos 3.14-->
                                        <tr>
                                            <td>a)</td>
                                            <td>Internacional</td>
                                            <td id="puntajeCongresoInt"><b>25</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCongresoInt"
                                                    oninput="onActv3SubTotal3_14()" value="{{ oldValueOrDefault('cantCongresoInt') }}"></td>
                                            <td colspan="3"></td>

                                            <td id="subtotalCongresoInt">0</td>
                                            <td class="td_obs"><span id="comisionCongresoInt"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCongresoInt"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Nacional</td>
                                            <td id="puntajeCongresoNac"><b>20</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCongresoNac"
                                                    oninput="onActv3SubTotal3_14()" value="{{ oldValueOrDefault('cantCongresoNac') }}"></td>
                                            <td colspan="3"></td>
                                            <td id="subtotalCongresoNac">0</td>
                                            <td class="td_obs"><span id="comisionCongresoNac"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCongresoNac"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Local</td>
                                            <td id="puntajeCongresoLoc"><b>10</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCongresoLoc"
                                                    oninput="onActv3SubTotal3_14()" value="{{ oldValueOrDefault('cantCongresoLoc') }}"></td>
                                            <td colspan="3"></td>
                                            <td id="subtotalCongresoLoc">0</td>
                                            <td class="td_obs"><span id="comisionCongresoLoc"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCongresoLoc"></td>
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
                            </div>
                            <div id="step16" style="display: none">  
                                <form id="form3_15" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store315', 'form3_15');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}}
                                    <input type="hidden" name="user_type" value="docente">
                                @csrf
                                <!--3.15 Registro de patentes y productos de investigación tecnológica y educativa -->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">60</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th colspan="4" class="table-ajust" scope="col"></th>

                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_15" class="acreditacion" colspan="7">3.15 Registro de patentes y
                                                productos de
                                                investigación
                                                tecnológica
                                                y educativa</th>

                                            <th id="score3_15">0</th>
                                            <th id="comision3_15">0</th>
                                            
                                        </tr>
                                        <tr>
                                            <th colspan="2"></th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="text-center acreditacion form3_15Cantidad">Cantidad</th>
                                            <th colspan="5"></th>
                                            <th class="text-center acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <thead>
                                        <tr>
                                            <td>a)</td>
                                            <td>Registro de patentes</td>
                                            <td id="puntajePatentes"><b>60</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantPatentes" 
                                                    oninput="onActv3SubTotal3_15()" value="{{ oldValueOrDefault('puntajePatentes') }}"></td>
                                            <td colspan="3"></td>

                                            <td id="subtotalPatentes">0</td>
                                            <td class="td_obs"><span id="comisionPatententes"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPatentes"></td>
                                        </tr>
                                        </thead>
                                        <thead>
                                        <tr>
                                            <td>b)</td>
                                            <td>Desarrollo de prototipos</td>
                                            <td id="puntajePrototipos"><b>30</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantPrototipos" 
                                                    oninput="onActv3SubTotal3_15()" value="{{ oldValueOrDefault('cantPrototipos') }}"></td>
                                            <td colspan="3"></td>
                                            <td id="subtotalPrototipos">0</td>
                                            <td class="td_obs"><span id="comisionPrototipos"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPrototipos"></td>
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
                            </div>    
                            <br>
                            <div id="step17" style="display: none">  
                                <form id="form3_16" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store316', 'form3_16');">
                                     
                                    {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}}
                                    <input type="hidden" name="user_type" value="docente">
                                @csrf
                                <!--3.16 Actividades de arbitraje, revisión, correción y edición -->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">30</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>

                                            <th colspan="5" class="text-center">
                                                <h3>Investigación</h3>
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=3>Actividad</th>
                                            <th colspan="4" class="table-ajust" scope="col"></th>

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
                                            
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion">Incisos</th>
                                            <th class="acreditacion">Actividad</th>
                                            <th class="acreditacion">Nivel</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="text-center acreditacion">Cantidad</th>
                                            <th colspan="2" ></th>
                                            
                                            <th class="acreditacion">Subtotal</th>
                                            <th></th>
                                            <th class="text-center acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>a)</td>
                                            <td>Arbitraje a proyectos de investigación</td>
                                            <td>Internacional</td>
                                            <td id="puntajeArbInt"><b>30</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantArbInt" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantArbInt') }}"></td>
                                            <td colspan="2"></td>
                                            
                                            <td id="subtotalArbInt"></td>
                                            <td class="td_obs"><span id="comisionArbInt"></span></td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsArbInt"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Arbitraje a proyectos de investigación</td>
                                            <td>Nacional</td>
                                            <td id="puntajeArbINac"><b>25</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantArbNac" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantArbNac') }}"></td>
                                            <td colspan="2"></td>
                                            
                                            <td id="subtotalArbNac"></td>
                                            <td class="td_obs"><span id="comisionArbNac"></span></td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsArbNac"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Arbitraje de publicaciones</td>
                                            <td>Internacional</td>
                                            <td id="puntajePubInt"><b>20</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantPubInt" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantPubInt') }}"></td>
                                            <td colspan="2"></td>
                                            <td id="subtotalPubInt"></td>
                                            <td class="td_obs"><span id="comisionPubInt"></span></td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPubInt"></td>
                                        </tr>
                                        <tr>
                                            <td>d)</td>
                                            <td>Arbitraje de publicaciones</td>
                                            <td>Nacional</td>
                                            <td id="puntajePubINac"><b>10</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantPubNac" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantPubNac') }}"></td>
                                            <td colspan="2"></td>
                                            <td id="subtotalPubNac"></td>
                                            <td class="td_obs"><span id="comisionPubNac"></span></td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPubNac"></td>
                                        </tr>
                                        <tr>
                                            <td>e)</td>
                                            <td>Revisor(a) de libros, corrector(a)</td>
                                            <td>Internacional</td>
                                            <td id="puntajeRevInt"><b>30</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantRevInt" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantRevInt') }}"></td>
                                            <td colspan="2"></td>
                                            <td id="subtotalRevInt"></td>
                                            <td class="td_obs"><span id="comisionRevInt"></span></td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRevInt"></td>
                                        </tr>
                                        <tr>
                                            <td>f)</td>
                                            <td>Revisor(a) de libros, corrector(a)</td>
                                            <td>Nacional</td>
                                            <td id="puntajeRevINac"><b>25</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantRevNac" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantRevNac') }}"></td>
                                            <td colspan="2"></td>
                                            <td id="subtotalRevNac"></td>
                                            <td class="td_obs"><span id="comisionRevNac"></span></td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRevNac"></td>
                                        </tr>
                                        <tr>
                                            <td>g)</td>
                                            <td>Consejo editorial de revista, edición de revista</td>
                                            <td>----</td>
                                            <td id="puntajeRevista"><b>10</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantRevista" 
                                                    oninput="onActv3SubTotal3_16()" value="{{ oldValueOrDefault('cantRevista') }}"></td>
                                            <td colspan="2"></td>
                                            <td id="subtotalRevista"></td>
                                            <td class="td_obs"><span id="comisionRevista"></span></td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRevista"></td>
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
                            </div>    
                            <br>
                            <div id="step18" style="display: none">  
                                <form id="form3_17" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store317', 'form3_17');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}}
                                    <input type="hidden" name="user_type" value="docente">
                                    @csrf
                                    <!--3.17 Proyectos académicos de extensión y difusión-->
                                    <h4>Puntaje máximo
                                        <label class="bg-black text-white px-4 mt-3" for="">50</label>
                                    </h4>
                                    <table class="table table-sm tutorias">
                                        <thead>
                                            <tr>

                                                <th colspan="6" class="text-center">
                                                    <h3>Cuerpos Colegiados</h3>
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan=2>Actividad</th>
                                                <th colspan="4" class="table-ajust" scope="col"></th>

                                                <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th id="seccion3_17" class="acreditacion" colspan="5"> 3.17 Proyectos académicos de
                                                    extensión y
                                                    difusión</th>

                                                <th></th>
                                                <th id="score3_17">0</th>
                                                <th id="comision3_17">0</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th class="acreditacion">Puntaje</th>
                                                <th class="text-center acreditacion">Cantidad</th>
                                                <th colspan="3"></th>
                                                <th class="text-center acreditacion" scope="col">Observaciones</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>a)</td>
                                                <td>Inicio de proyectos de extensión y difusión con financiamiento externo</td>
                                                <td></td>
                                                <td id="puntajeDifusionExt"><b>15</b></td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantDifusionExt" 
                                                        oninput="onActv3SubTotal3_17()" value="{{ oldValueOrDefault('cantDifusionExt') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalDifusionExt"></td>
                                                <td class="td_obs"><span id="comisionDifusionExt"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsDifusionExt"></td>
                                            </tr>
                                            <tr>
                                                <td>b)</td>
                                                <td>Inicio de proyectos de extensión y difusión internos, aprobados por CAAC
                                                </td>
                                                <td></td>
                                                <td id="puntajeDifusionInt"><b>10</b></td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantDifusionInt" 
                                                        oninput="onActv3SubTotal3_17()" value="{{ oldValueOrDefault('cantDifusionInt') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalDifusionInt"></td>
                                                <td class="td_obs"><span id="comisionDifusionInt"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsDifusionInt"></td>
                                            </tr>
                                            <tr>
                                                <td>c)</td>
                                                <td>Reporte cumplido del periodo anual de proyecto de extensión y difusión con
                                                    financiamiento
                                                    externo
                                                </td>
                                                <td></td>
                                                <td id="puntajeRepDifusionExt"><b>35</b></td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantRepDifusionExt" 
                                                        oninput="onActv3SubTotal3_17()" value="{{ oldValueOrDefault('cantRepDifusionExt') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalRepDifusionExt"></td>
                                                <td class="td_obs"><span id="comisionRepDifusionExt"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRepDifusionExt"></td>
                                            </tr>
                                            <tr>
                                                <td>d)</td>
                                                <td>Reporte cumplido del periodo anual de proyecto de extensión y difusión
                                                    internos, aprobados por
                                                    CAAC</td>
                                                <td></td>
                                                <td id="puntajeRepDifusionInt"><b>20</b></td>
                                                <td class="td_docente_cantidad"><input type="number" id="cantRepDifusionInt" 
                                                        oninput="onActv3SubTotal3_17()" value="{{ oldValueOrDefault('cantRepDifusionInt') }}">
                                                </td>
                                                <td></td>
                                                <td id="subtotalRepDifusionInt"></td>
                                                <td class="td_obs"><span id="comisionRepDifusionInt"></span>
                                                </td>
                                                <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRepDifusionInt"></td>
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
                            </div>
                            <br>
                            <div id="step19" style="display: none">  
                                <form id="form3_18" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store318', 'form3_18');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="docente">
                                @csrf
                                <!--3.18 Organización de congresos o eventos institucionales del área de conocimiento de la o el Docente-->
                                <h4>Puntaje máximo
                                    <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                </h4>
                                <table class="table table-sm tutorias">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan=2>Actividad</th>
                                            <th colspan="5" class="table-ajust" scope="col"></th>

                                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th id="seccion3_18" class="acreditacion" colspan="7"> 3.18 Organización de congresos
                                                o eventos
                                                institucionales del
                                                área
                                                de conocimiento de la o el Docente</th>

                                            <th id="score3_18">0</th>
                                            <th id="comision3_18">0</th>
                                            
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="acreditacion">Incisos</th>
                                            <th class="acreditacion" colspan=1 style="padding-left: 170px;">Actividad</th>
                                            <th></th>
                                            <th class="acreditacion">Nivel</th>
                                            <th class="acreditacion">Puntaje</th>
                                            <th class="text-center acreditacion">Cantidad</th>
                                            <th></th>
                                            <th class="acreditacion">Subtotal</th>
                                            <th></th>
                                            <th class="text-center acreditacion" scope="col">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>a)</td>
                                            <td>Comité organizador</td>
                                            <td></td>
                                            <td>Internacional**</td>
                                            <td id="puntajeComOrgInt"><b>40</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantComOrgInt" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComOrgInt') }}"></td>
                                            <td></td>
                                            <td id="subtotalComOrgInt"></td>
                                            <td class="td_obs"><span id="comisionComOrgInt"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComOrgInt"></td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Comité organizador</td>
                                            <td></td>
                                            <td>Nacional</td>
                                            <td id="puntajeComOrgNac"><b>20</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantComOrgNac"
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComOrgNac') }}"></td>
                                            <td></td>
                                            <td id="subtotalComOrgNac"></td>
                                            <td class="td_obs"><span id="comisionComOrgNac"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComOrgNac"></td>
                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Comité organizador</td>
                                            <td></td>
                                            <td>Regional</td>
                                            <td id="puntajeComOrgRegc"><b>10</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantComOrgReg"
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComOrgReg') }}"></td>
                                            <td></td>
                                            <td id="subtotalComOrgReg"></td>
                                            <td class="td_obs"><span id="comisionComOrgReg"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComOrgReg"></td>
                                        </tr>
                                        <tr>
                                            <td>d)</td>
                                            <td>Comisiones de Apoyo</td>
                                            <td></td>
                                            <td>Internacional</td>
                                            <td id="puntajeComApoyoInt"><b>40</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantComApoyoInt" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComApoyoInt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalComApoyoInt"></td>
                                            <td class="td_obs"><span id="comisionComApoyoInt"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComApoyoInt"></td>
                                        </tr>
                                        <tr>
                                            <td>e)</td>
                                            <td>Comisiones de Apoyo</td>
                                            <td></td>
                                            <td>Nacional</td>
                                            <td id="puntajeComApoyoNac"><b>20</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantComApoyoNac" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComApoyoNac') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalComApoyoNac"></td>
                                            <td class="td_obs"><span id="comisionComApoyoNac"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComApoyoNac"></td>
                                        </tr>
                                        <tr>
                                            <td>f)</td>
                                            <td>Comisiones de Apoyo</td>
                                            <td></td>
                                            <td>Regional</td>
                                            <td id="puntajeComApoyoRegc"><b>10</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantComApoyoReg" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantComApoyoReg') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalComApoyoReg"></td>
                                            <td class="td_obs"><span id="comisionComApoyoReg"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComApoyoReg"></td>
                                        </tr>
                                        <tr>
                                            <td>g)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comité organizador</td>
                                            <td>Internacional</td>
                                            <td id="puntajeCicloComOrgInt"><b>20</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCicloComOrgInt" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComOrgInt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComOrgInt"></td>
                                            <td class="td_obs"><span id="comisionCicloComOrgInt"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCicloComOrgInt"></td>
                                        </tr>
                                        <tr>
                                            <td>h)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comité organizador</td>
                                            <td>Nacional</td>
                                            <td id="puntajeCicloComOrgNac"><b>15</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCicloComOrgNac" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComOrgNac') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComOrgNac"></td>
                                            <td class="td_obs"><span id="comisionCicloComOrgNac"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCicloComOrgNac"></td>
                                        </tr>
                                        <tr>
                                            <td>i)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comité organizador</td>
                                            <td>Regional/Institucional</td>
                                            <td id="puntajeCicloComOrgReg"><b>10</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCicloComOrgReg" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComOrgReg') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComOrgReg"></td>
                                            <td class="td_obs"><span id="comisionCicloComOrgReg"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCicloComOrgReg"></td>
                                        </tr>
                                        <tr>
                                            <td>j)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comisiones de apoyo</td>
                                            <td>Internacional</td>
                                            <td id="puntajeCicloComApoyoInt"><b>20</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCicloComApoyoInt" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComApoyoInt') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComApoyoInt"></td>
                                            <td class="td_obs"><span id="comisionCicloComApoyoInt"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCicloComApoyoInt"></td>
                                        </tr>
                                        <tr>
                                            <td>k)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comisiones de apoyo</td>
                                            <td>Nacional</td>
                                            <td id="puntajeCicloComApoyoNac"><b>15</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCicloComApoyoNac" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComApoyoNac') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComApoyoNac"></td>
                                            <td class="td_obs"><span id="comisionCicloComApoyoNac"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCicloComApoyoNac"></td>
                                        </tr>
                                        <tr>
                                            <td>l)</td>
                                            <td>Ciclo de conferencias, simposio, coloquio, etc.</td>
                                            <td>Comisiones de apoyo</td>
                                            <td>Regional/Institucional</td>
                                            <td id="puntajeCicloComApoyoReg"><b>10</b></td>
                                            <td class="td_docente_cantidad"><input type="number" id="cantCicloComApoyoReg" 
                                                    oninput="onActv3SubTotal3_18()" value="{{ oldValueOrDefault('cantCicloComApoyoReg') }}">
                                            </td>
                                            <td></td>
                                            <td id="subtotalCicloComApoyoReg"></td>
                                            <td class="td_obs"><span id="comisionCicloComApoyoReg"></span>
                                            </td>
                                            <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCicloComApoyoReg"></td>
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
                            </div>    
                            <br>
                            <div id="step20" style="display: none">  
                                <form id="form3_19" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store319', 'form3_19');">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                     
                                    {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}}
                                    <input type="hidden" name="user_type" value="docente">

                                    @csrf
                                    <!--3.19 Participación en cuerpos colegiados-->
                                    <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3" for="">40</label>
                                    </h4>
                                        <table class="table table-sm tutorias">
                                            <thead>
                                                <tr>
                                                    <th scope="col" colspan=2>Actividad</th>
                                                    <th colspan="5" class="table-ajust" scope="col"></th>

                                                    <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                                                    <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora
                                                    </th>
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <th id="seccion3_19" class="acreditacion" colspan="7"> 3.19 Participación en
                                                        cuerpos colegiados
                                                    </th>

                                                    <th id="score3_19">0</th>
                                                    <th id="comision3_19">0</th>
                                                    
                                                </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <th class="acreditacion">Incisos</th>
                                                    <th class="acreditacion" colspan="2" style="padding-left: 170px;">Actividad
                                                    </th>
                                                    
                                                    <th class="acreditacion">Nivel</th>
                                                    <th class="acreditacion">Puntaje</th>
                                                    <th class="acreditacion text-center">Cantidad</th>
                                                    <th></th>
                                                    <th class="acreditacion">Subtotal</th>
                                                    <th></th>
                                                    <th class="acreditacion" scope="col">Observaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>a)</td>
                                                    <td>Representante del profesorado ante H. CGU</td>
                                                    <td></td>
                                                    <td>Titular o suplente</td>
                                                    <td id="puntajeCGUtitular"><b>20</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantCGUtitular" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCGUtitular') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalCGUtitular"></td>
                                                    <td class="td_obs"><span id="comCGUtitular"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCGUtitular"></td>
                                                </tr>
                                                <tr>
                                                    <td>b)</td>
                                                    <td>Representante del profesorado ante H. CGU</td>
                                                    <td></td>
                                                    <td>Participación como miembro de comisión especial</td>
                                                    <td id="puntajeCGUespecial"><b>15</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantCGUespecial" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCGUespecial') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalCGUespecial"></td>
                                                    <td class="td_obs"><span id="comCGUespecial"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCGUespecial"></td>
                                                </tr>
                                                <tr>
                                                    <td>c)</td>
                                                    <td>Representante del profesorado ante H. CGU</td>
                                                    <td></td>
                                                    <td>Participación como miembro en comisión permanente</td>
                                                    <td id="puntajeCGUpermanente"><b>10</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantCGUpermanente" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCGUpermanente') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalCGUpermanente"></td>
                                                    <td class="td_obs"><span id="comCGUpermanente"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCGUpermanente"></td>
                                                </tr>
                                                <tr>
                                                    <td>d)</td>
                                                    <td>Representante del profesorado ante CAAC</td>
                                                    <td></td>
                                                    <td>Titular o suplente</td>
                                                    <td id="puntajeCAACtitular"><b>10</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantCAACtitular" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCAACtitular') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalCAACtitular"></td>
                                                    <td class="td_obs"><span id="comCAACtitular"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCAACtitular"></td>
                                                </tr>
                                                <tr>
                                                    <td>e)</td>
                                                    <td>Representante del profesorado ante CAAC</td>
                                                    <td></td>
                                                    <td>Participación como integrante de comisión</td>
                                                    <td id="puntajeCAACintegCom"><b>5</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantCAACintegCom" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantCAACintegCom') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalCAACintegCom"></td>
                                                    <td class="td_obs"><span id="comCAACintegCom"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsCAACintegCom"></td>
                                                </tr>
                                                <tr>
                                                    <td>f)</td>
                                                    <td>Comisiones</td>
                                                    <td></td>
                                                    <td>Departamentales</td>
                                                    <td id="puntajeComDepart"><b>15</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantComDepart" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantComDepart') }}"></td>
                                                    <td></td>
                                                    <td id="subtotalComDepart"></td>
                                                    <td class="td_obs"><span id="comComDepart"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComDepart"></td>
                                                </tr>
                                                <tr>
                                                    <td>g)</td>
                                                    <td>Comisiones</td>
                                                    <td></td>
                                                    <td>Dictaminadora del PEDPD</td>
                                                    <td id="puntajeComPEDPD"><b>15</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantComPEDPD"
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantComPEDPD') }}"></td>
                                                    <td></td>
                                                    <td id="subtotalComPEDPD"></td>
                                                    <td class="td_obs"><span id="comComPEDPD"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComPEDPD"></td>
                                                </tr>
                                                <tr>
                                                    <td>h)</td>
                                                    <td>Comisiones</td>
                                                    <td></td>
                                                    <td>Participación como integrante del Comité Académico de Posgrado</td>
                                                    <td id="puntajeComPartPos"><b>5</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantComPartPos" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantComPartPos') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalComPartPos"></td>
                                                    <td class="td_obs"><span id="comComPartPos"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsComPartPos"></td>
                                                </tr>
                                                <tr>
                                                    <td>i)</td>
                                                    <td>Responsable</td>
                                                    <td></td>
                                                    <td>De posgrado</td>
                                                    <td id="puntajeRespPos"><b>25</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantRespPos" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantRespPos') }}"></td>
                                                    <td></td>
                                                    <td id="subtotalRespPos"></td>
                                                    <td class="td_obs"><span id="comRespPos"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRespPos"></td>
                                                </tr>
                                                <tr>
                                                    <td>j)</td>
                                                    <td>Responsable</td>
                                                    <td></td>
                                                    <td>De carrera</td>
                                                    <td id="puntajeRespCarrera"><b>15</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantRespCarrera" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantRespCarrera') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalRespCarrera"></td>
                                                    <td class="td_obs"><span id="comRespCarrera"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRespCarrera"></td>
                                                </tr>
                                                <tr>
                                                    <td>k)</td>
                                                    <td>Responsable</td>
                                                    <td></td>
                                                    <td>De unidad de producción</td>
                                                    <td id="puntajeRespProd"><b>20</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantRespProd" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantRespProd') }}"></td>
                                                    <td></td>
                                                    <td id="subtotalRespProd"></td>
                                                    <td class="td_obs"><span id="comRespProd"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRespProd"></td>
                                                </tr>
                                                <tr>
                                                    <td>l)</td>
                                                    <td>Responsable</td>
                                                    <td></td>
                                                    <td>De laboratorio de docencia e investigación</td>
                                                    <td id="puntajeRespLab"><b>15</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantRespLab" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantRespLab') }}"></td>
                                                    <td></td>
                                                    <td id="subtotalRespLab"></td>
                                                    <td class="td_obs"><span id="comRespLab"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsRespLab"></td>
                                                </tr>
                                                <tr>
                                                    <td>m)</td>
                                                    <td>Sinodalías de examen de oposición</td>
                                                    <td></td>
                                                    <td>Profesorado</td>
                                                    <td id="puntajeExamProf"><b>15</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantExamProf" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantExamProf') }}"></td>
                                                    <td></td>
                                                    <td id="subtotalExamProf"></td>
                                                    <td class="td_obs"><span id="comExamProf"></span></td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsExamProf"></td>
                                                </tr>
                                                <tr>
                                                    <td>n)</td>
                                                    <td>Sinodalías de examen de oposición</td>
                                                    <td></td>
                                                    <td>Ayudantes académicos</td>
                                                    <td id="puntajeExamAcademicos"><b>5</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantExamAcademicos" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantExamAcademicos') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalExamAcademicos"></td>
                                                    <td class="td_obs"><span id="comExamAcademicos"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsExamAcademicos"></td>
                                                </tr>
                                                <tr>
                                                    <td>o1)</td>
                                                    <td>Cuerpo académico registrado ante PRODEP</td>
                                                    <td>En formación</td>
                                                    <td>Responsable</td>
                                                    <td id="puntajePRODEPformResp"><b>15</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantPRODEPformResp" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPformResp') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalPRODEPformResp"></td>
                                                    <td class="td_obs"><span id="comPRODEPformResp"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPRODEPformResp"></td>
                                                </tr>
                                                <tr>
                                                    <td>o2)</td>
                                                    <td>Cuerpo académico registrado ante PRODEP</td>
                                                    <td>En formación</td>
                                                    <td>Integrante</td>
                                                    <td id="puntajePRODEPformInteg"><b>10</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantPRODEPformInteg" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPformInteg') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalPRODEPformInteg"></td>
                                                    <td class="td_obs"><span id="comPRODEPformInteg"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPRODEPformInteg"></td>
                                                </tr>
                                                <tr>
                                                    <td>p1)</td>
                                                    <td>Cuerpo académico registrado ante PRODEP</td>
                                                    <td>En consolidación</td>
                                                    <td>Responsable</td>
                                                    <td id="puntajePRODEPenconsResp"><b>25</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantPRODEPenconsResp"
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPenconsResp') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalPRODEPenconsResp"></td>
                                                    <td class="td_obs"><span id="comPRODEPenconsResp"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPRODEPenconsResp"></td>
                                                </tr>
                                                <tr>
                                                    <td>p2)</td>
                                                    <td>Cuerpo académico registrado ante PRODEP</td>
                                                    <td>En consolidación</td>
                                                    <td>Integrante</td>
                                                    <td id="puntajePRODEPenconsInteg"><b>15</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantPRODEPenconsInteg" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPenconsInteg') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalPRODEPenconsInteg"></td>
                                                    <td class="td_obs"><span id="comPRODEPenconsInteg"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPRODEPenconsInteg"></td>
                                                </tr>
                                                <tr>
                                                    <td>q1)</td>
                                                    <td>Cuerpo académico registrado ante PRODEP</td>
                                                    <td>Consolidado</td>
                                                    <td>Responsable</td>
                                                    <td id="puntajePRODEPconsResp"><b>35</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantPRODEPconsResp" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPconsResp') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalPRODEPconsResp"></td>
                                                    <td class="td_obs"><span id="comPRODEPconsResp"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPRODEPconsResp"></td>
                                                </tr>
                                                <tr>
                                                    <td>q2)</td>
                                                    <td>Cuerpo académico registrado ante PRODEP</td>
                                                    <td>Consolidado</td>
                                                    <td>Integrante</td>
                                                    <td id="puntajePRODEPconsInteg"><b>25</b></td>
                                                    <td class="td_docente_cantidad"><input type="number" id="cantPRODEPconsInteg" 
                                                            oninput="onActv3SubTotal3_19()" value="{{ oldValueOrDefault('cantPRODEPconsInteg') }}">
                                                    </td>
                                                    <td></td>
                                                    <td id="subtotalPRODEPconsInteg"></td>
                                                    <td class="td_obs"><span id="comPRODEPconsInteg"></span>
                                                    </td>
                                                    <td class="td_obs"><input class="table-header" type="text" placeholder="Comenta aqui" id="obsPRODEPconsInteg"></td>
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
                            </div>        
                            <br>

                            {{-- Contenedores para los formularios dinámicos --}}
                            @php $dynamicFormIndex = 0; @endphp
                            @foreach($dynamicForms as $form)
                                @if(Str::startsWith($form->form_type, '3.') && !in_array($form->form_type, $staticFormTypes))
                                    @php $dynamicFormIndex++;
                                    $renderData = $renderDataByForm[$form->id] ?? $form->form_data;
                                    // Asegurar que renderData sea un array (decodificar si es string)
                                    if (is_string($renderData)) {
                                        $renderData = json_decode($renderData, true) ?? [];
                                    }
                                    // Asegurar que form_structure sea un array
                                    $structure = $form->form_structure;
                                    if (is_string($structure)) {
                                        $structure = json_decode($structure, true) ?? [];
                                    }
                                    // Fallback para evitar errores si structure es null
                                    if (!is_array($structure)) {
                                        $structure = [];
                                    }
                                    @endphp
                                    <div id="step{{ $staticStepCount + $dynamicFormIndex }}" style="display: none">
                                        <h4>Puntaje máximo
                                            <label class="bg-black text-white px-4 mt-3">{{ $form->puntaje_maximo }}</label>
                                        </h4>
                                        <form id="dynamic-form-{{ $form->id }}" method="POST" onsubmit="event.preventDefault(); submitDynamicForm('{{ url('/dynamic-forms/save-response') }}', 'dynamic-form-{{ $form->id }}', {{ $staticStepCount + $dynamicFormIndex }});">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                             
                                            {{-- <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}"> --}}
                                            <input type="hidden" name="user_type" value="docente">
                                            <input type="hidden" name="form_id" value="{{ $form->id }}">

                                            <h3>{{ $form->form_name }}</h3>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            @if(is_array($structure) || is_object($structure))
                                                                @foreach($structure as $column)
                                                                    <th class="text-center align-middle">{{ $column['name'] }}</th>
                                                                @endforeach
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($renderData) && (is_array($renderData) || is_object($renderData)))
                                                            @foreach($renderData as $rowIndex => $row)
                                                                <tr>
                                                                    @foreach($structure as $colIndex => $column)
                                                                        @php
                                                                            $key = $column['key'];
                                                                            $isActividad = ($key === 'actividad');
                                                                            $isCommission = ($key === 'puntaje_de_la_comision_dictaminadora');
                                                                            $value = $row[$key] ?? '';
                                                                        @endphp
                                                                        
                                                                        <td>
                                                                            @if($isActividad)
                                                                                <span class="fw-bold">{{ $value }}</span>
                                                                                <input type="hidden" name="data[{{ $rowIndex }}][{{ $key }}]" value="{{ $value }}">
                                                                            @elseif($isCommission)
                                                                                <span class="text-muted text-center d-block">-</span>
                                                                            @else
                                                                                <input type="text" class="form-control form-control-sm text-center" 
                                                                                    name="data[{{ $rowIndex }}][{{ $key }}]" 
                                                                                    value="{{ $value }}"
                                                                                >
                                                                            @endif
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="100%" class="text-center text-muted">No hay datos disponibles para este formulario.</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>

                                            <p class="mt-3"><strong>Acreditación:</strong> {{ $form->acreditacion }}</p>

                                            <button type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                                        </form>
                                    </div>
                                @endif
                            @endforeach

</main>

<footer>
    <p class="text-center">Convocatoria actual: {{ $convocatoria ?? 'No asignada' }}</p>
    <p class="text-center">Periodo: {{ $periodo ?? 'Sin definir' }}</p>
    @component('components.pie-pag', ['number' => '2'])@endcomponent
    @component('components.pie-pag', ['number' => '2'])@endcomponent 
</footer>
                    </div>
                </div>
            </div>
@endif
</div>

<script>

const placeholderObs = "Escribe tu comentario aquí";

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


let dataDocentes = {

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
    dataDocentes[this.id] = this.value;
}


 // MAPA DE RUTAS
 const routeMap = {
    form3_1: { store: '/formato-evaluacion/store31', fetch: '/formato-evaluacion/get-data-31' },
    form3_2: { store: '/formato-evaluacion/store32', fetch: '/formato-evaluacion/get-data-32' },
    form3_3: { store: '/formato-evaluacion/store33', fetch: '/formato-evaluacion/get-data-33' },
    form3_4: { store: '/formato-evaluacion/store34', fetch: '/formato-evaluacion/get-data-34' },
    form3_5: { store: '/formato-evaluacion/store35', fetch: '/formato-evaluacion/get-data-35' },
    form3_6: { store: '/formato-evaluacion/store36', fetch: '/formato-evaluacion/get-data-36' },
    form3_7: { store: '/formato-evaluacion/store37', fetch: '/formato-evaluacion/get-data-37' },
    form3_8: { store: '/formato-evaluacion/store38', fetch: '/formato-evaluacion/get-data-38' },
    form3_8_1: { store: '/formato-evaluacion/store381', fetch: '/formato-evaluacion/get-data-381' },
    form3_9: { store: '/formato-evaluacion/store39', fetch: '/formato-evaluacion/get-data-39' },
    form3_10: { store: '/formato-evaluacion/store310', fetch: '/formato-evaluacion/get-data-310' },
    form3_11: { store: '/formato-evaluacion/store311', fetch: '/formato-evaluacion/get-data-311' },
    form3_12: { store: '/formato-evaluacion/store312', fetch: '/formato-evaluacion/get-data-312' },
    form3_13: { store: '/formato-evaluacion/store313', fetch: '/formato-evaluacion/get-data-313' },
    form3_14: { store: '/formato-evaluacion/store314', fetch: '/formato-evaluacion/get-data-314' },
    form3_15: { store: '/formato-evaluacion/store315', fetch: '/formato-evaluacion/get-data-315' },
    form3_16: { store: '/formato-evaluacion/store316', fetch: '/formato-evaluacion/get-data-316' },
    form3_17: { store: '/formato-evaluacion/store317', fetch: '/formato-evaluacion/get-data-317' },
    form3_18: { store: '/formato-evaluacion/store318', fetch: '/formato-evaluacion/get-data-318' },
    form3_19: { store: '/formato-evaluacion/store319', fetch: '/formato-evaluacion/get-data-319' },
};


//steps de los formularios
const stepMap = {
    form3_1: 1,
    form3_2: 2,
    form3_3: 3,
    form3_4: 4,
    form3_5: 5,
    form3_6: 6,
    form3_7: 7,
    form3_8: 8,
    form3_8_1: 9,
    form3_9: 10,
    form3_10: 11,
    form3_11: 12,
    form3_12: 13,
    form3_13: 14,
    form3_14: 15,
    form3_15: 16,
    form3_16: 17,
    form3_17: 18,
    form3_18: 19,
    form3_19: 20,
};

    function showStep(stepNumber) {
        document.querySelectorAll('[id^="step"]').forEach(step => {
            step.style.display = "none";
        });

        const current = document.getElementById(`step${stepNumber}`);
        if (current) {
            current.style.display = "block";
        }
    }

    function submitDynamicForm(url, formId, currentStep) {
        const form = document.getElementById(formId);
        const formData = new FormData(form);
        
        // Construct JSON payload from FormData
        const structuredData = {
            user_id: formData.get('user_id'),
            email: formData.get('email'),
            user_type: formData.get('user_type'),
            form_id: formData.get('form_id'),
            data: []
        };
        
        // Extract table data
        const rows = form.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            const rowData = {};
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name; 
                // Regex to extract key from name="data[rowIndex][key]"
                const match = name.match(/\[(\w+)\]$/);
                if (match) {
                    const key = match[1];
                    rowData[key] = input.value;
                }
            });
            structuredData.data.push(rowData);
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(structuredData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('✅ Formulario enviado correctamente', 'green');
                
                // Handle Step Transition
                if (currentStep) {
                    const nextStep = currentStep + 1;
                    // Check if next step exists
                    if (document.getElementById(`step${nextStep}`)) {
                        showStep(nextStep);
                        localStorage.setItem("ultimoStepDocencia", nextStep);
                    } else {
                        // End of forms
                        localStorage.setItem("ultimoStepDocencia", "FIN");
                        showMessage('Has completado todos los formularios.', 'blue');
                    }
                }
            } else {
                showMessage('❌ Error: ' + (data.message || 'Error al enviar'), 'red');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('❌ Error de conexión', 'red');
        });
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
                        formData['user_id'] = document.querySelector('input[name="user_id"]').value;
                        formData['email'] = document.querySelector('input[name="email"]').value;
                        formData['user_type'] = document.querySelector('input[name="user_type"]').value;
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
                                        */
                                       
                                        break;

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
                                let score3_4Label = form.querySelector('th[id="score3_4"]');
                                formData['cantInternacional'] = document.getElementById('cantInternacional').value;
                                formData['cantNacional'] = document.getElementById('cantNacional').value;
                                formData['cantidadRegional'] = document.getElementById('cantidadRegional').value;
                                formData['cantPreparacion'] = document.getElementById('cantPreparacion').value;
                                formData['cantInternacional2'] = document.getElementById('cantInternacional2').textContent;
                                formData['cantNacional2'] = document.getElementById('cantNacional2').textContent;
                                formData['cantidadRegional2'] = document.getElementById('cantidadRegional2').textContent;
                                formData['cantPreparacion2'] = document.getElementById('cantPreparacion2').textContent;

                                formData['score3_4'] = parseInt(score3_4Label.innerText, 10) || 0; 
                               
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
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": csrfToken
                                },
                                body: JSON.stringify(formData)
                            });

                            const data = await response.json();

                            // =============================
                            //   VALIDACIÓN FALLIDA
                            // =============================
                            if (!data.success) {
                                showMessage('Formulario no enviado', 'red');
                                console.error("Errores:", data.errors);
                                return;
                            }

                            // =============================
                            //   TODO BIEN
                            // =============================
                            showMessage('Formulario enviado', 'green');

                            // =============================
                            //   AVANZAR AL SIGUIENTE STEP
                            // =============================
                            const currentStep = stepMap[formId];
                            const nextStep = currentStep + 1;

                            if (document.getElementById(`step${nextStep}`)) {
                                showStep(nextStep);
                                localStorage.setItem("ultimoStepDocencia", nextStep);
                            } else {
                                // Si no hay paso siguiente (ni fijo ni dinámico)
                                showMessage("Has completado todos los formularios disponibles.", "blue");
                                
                                localStorage.setItem("ultimoStepDocencia", "FIN");
                            }

                        } catch (error) {
                            showMessage("Error de conexión", "red");
                            console.error("Error al enviar:", error);
                        }
                    }

                    // =============================
                    //       RESTAURAR PROGRESO
                    // =============================
                    const ultimo = localStorage.getItem("ultimoStepDocencia");

                    if (ultimo) {
                        if (ultimo === "FIN") {
                            // buscar el último step disponible dinámicamente
                        const steps = document.querySelectorAll('[id^="step"]');
                        const max = Math.max(...[...steps].map(s => Number(s.id.replace("step", ""))));
                        showStep(max);
                        } else {
                            showStep(parseInt(ultimo));
                        }
                    } else {
                        showStep(1);
                    }

                    // =============================
                    //     ASIGNAR ÓNSUBMIT
                    // =============================
                    Object.keys(routeMap).forEach(formId => {
                        const form = document.getElementById(formId);
                        if (!form) return;

                        form.onsubmit = function (event) {
                            event.preventDefault();
                            const storeUrl = routeMap[formId].store;
                            submitForm(storeUrl, formId);
                        };
                    });



        // // Función para actualizar el label en el footer con la convocatoria y periodo de evaluación
        // function actualizarLabelConvocatoriaPeriodo(convocatoria, periodo) {
        //     const label = document.getElementById('convocatoriaPeriodoLabel');
        //     label.textContent = `Convocatoria: ${convocatoria}, Período: ${periodo}`;
        // }

        // Captura la convocatoria y periodo de evaluación al enviar el formulario form1
        // document.addEventListener('DOMContentLoaded', function () {
        //     const form1 = document.getElementById('form1');
        //     form1.addEventListener('submit', function (event) {
        //         event.preventDefault(); // Evita el envío del formulario para manejarlo con JavaScript

        //         // Captura los valores del formulario form1
        //         const convocatoria = document.getElementById('convocatoria').value;
        //         const periodo = document.getElementById('periodo').value;

        //         // Actualiza el label en el footer con los valores capturados
        //         actualizarLabelConvocatoriaPeriodo(convocatoria, periodo);
        //         console.log(label);
        //     });
        // });

        // document.addEventListener('DOMContentLoaded', function () {
        //     // Get the canvas element
        //     var canvas = document.getElementById('convocatoriaCanvas');
        //     var context = canvas.getContext('2d');

        //     // Function to update the canvas with 'Convocatoria' value
        //     function updateCanvas(text) {
        //         // Clear the canvas
        //         context.clearRect(200, 100, canvas.width, canvas.height);

        //         // Set text properties
        //         context.font = '20px Arial';
        //         context.fillStyle = 'black';
        //         context.textAlign = 'right';
        //         context.textBaseline = 'middle';

        //         // Draw the text
        //         context.fillText(text, canvas.width / 2, canvas.height / 2);
        //     }

        //     // Get the input element with id 'convocatoria'
        //     var convocatoriaInput = document.getElementById('convocatoria');
        //     if (convocatoriaInput) {
        //         // Update the canvas initially with the placeholder value or empty
        //         updateCanvas(convocatoriaInput.placeholder);

        //         // Listen for input events to dynamically update the canvas
        //         convocatoriaInput.addEventListener('input', function () {
        //             var newValue = convocatoriaInput.value;
        //             updateCanvas(newValue);
        //         });
        //     }
        // });


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


//Actividad 9
function onActv3SubTotal3_9(){

  // obtener solo elementos con ese índice
  const scoreElement = document.getElementById('score3_9');
    //Puntajes
  const puntajeTutorias20_1 = parseFloat(document.getElementById("puntajeTutorias20_1").textContent);
  const puntajeTutorias15_1 = parseFloat(document.getElementById("puntajeTutorias15_1").textContent);
  const puntajeTutorias10_1 = parseFloat(document.getElementById("puntajeTutorias10_1").textContent);
  const puntajeTutorias55 = parseFloat(document.getElementById("puntajeTutorias55").textContent);
  const puntajeTutorias45 = parseFloat(document.getElementById("puntajeTutorias45").textContent);
  const puntajeTutorias35 = parseFloat(document.getElementById("puntajeTutorias35").textContent);
  const puntajeTutorias70 = parseFloat(document.getElementById("puntajeTutorias70").textContent);
  const puntajeTutorias60 = parseFloat(document.getElementById("puntajeTutorias60").textContent);
  const puntajeTutorias50 = parseFloat(document.getElementById("puntajeTutorias50").textContent);
  const puntajeTutorias30_1 = parseFloat(document.getElementById("puntajeTutorias30_1").textContent);
  const puntajeTutorias20_2 = parseFloat(document.getElementById("puntajeTutorias20_2").textContent);
  const puntajeTutorias15_2 = parseFloat(document.getElementById("puntajeTutorias15_2").textContent);
  const puntajeTutorias30_2 = parseFloat(document.getElementById("puntajeTutorias30_2").textContent);
  const puntajeTutorias20_3 = parseFloat(document.getElementById("puntajeTutorias20_3").textContent);
  const puntajeTutorias15_3 = parseFloat(document.getElementById("puntajeTutorias15_3").textContent);
  const puntajeTutorias15_4 = parseFloat(document.getElementById("puntajeTutorias15_4").textContent);
  const puntajeTutorias10_2 = parseFloat(document.getElementById("puntajeTutorias10_2").textContent);

  //Cantidad
  const puntaje3_9_1 = parseFloat(document.getElementById("puntaje3_9_1").value);
  const puntaje3_9_2 = parseFloat(document.getElementById("puntaje3_9_2").value);
  const puntaje3_9_3 =  parseFloat(document.getElementById("puntaje3_9_3").value);
  const puntaje3_9_4 = parseFloat(document.getElementById("puntaje3_9_4").value);
  const puntaje3_9_5 = parseFloat(document.getElementById("puntaje3_9_5").value);
  const puntaje3_9_6 =  parseFloat(document.getElementById("puntaje3_9_6").value);
  const puntaje3_9_7 = parseFloat(document.getElementById("puntaje3_9_7").value);
  const puntaje3_9_8 = parseFloat(document.getElementById("puntaje3_9_8").value);
  const puntaje3_9_9 =  parseFloat(document.getElementById("puntaje3_9_9").value);
  const puntaje3_9_10 = parseFloat(document.getElementById("puntaje3_9_10").value);
  const puntaje3_9_11 = parseFloat(document.getElementById("puntaje3_9_11").value);
  const puntaje3_9_12 =  parseFloat(document.getElementById("puntaje3_9_12").value);
  const puntaje3_9_13 = parseFloat(document.getElementById("puntaje3_9_13").value);
  const puntaje3_9_14 = parseFloat(document.getElementById("puntaje3_9_14").value);
  const puntaje3_9_15 =  parseFloat(document.getElementById("puntaje3_9_15").value);
  const puntaje3_9_16 = parseFloat(document.getElementById("puntaje3_9_16").value);
  const puntaje3_9_17 = parseFloat(document.getElementById("puntaje3_9_17").value);

  //calculos subtotales
    //a)
    const tutorias1 = subtotal(puntajeTutorias20_1, puntaje3_9_1);
    document.getElementById("tutorias1").innerHTML= tutorias1;
    console.log("tutorias 1:", tutorias1);

    //b
    const tutorias2 = subtotal(puntajeTutorias15_1, puntaje3_9_2);
    document.getElementById("tutorias2").innerHTML= tutorias2;
    console.log("tutorias 2:", tutorias2);

    //c)
    const tutorias3 = subtotal(puntajeTutorias10_1, puntaje3_9_3);
    document.getElementById("tutorias3").innerHTML= tutorias3;
    console.log("tutorias 3:", tutorias3);

    //d
    const tutorias4 = subtotal(puntajeTutorias55, puntaje3_9_4);
    document.getElementById("tutorias4").innerHTML= tutorias4;
    console.log("tutorias 4:", tutorias4);

    //e
    const tutorias5 = subtotal(puntajeTutorias45, puntaje3_9_5);
    document.getElementById("tutorias5").innerHTML= tutorias5;
    console.log("tutorias 5:", tutorias5);

    //f
    const tutorias6 = subtotal(puntajeTutorias35,puntaje3_9_6);
    document.getElementById("tutorias6").innerHTML = tutorias6; 
    console.log("tutorias 6:", tutorias6)
    
    //g
    const tutorias7 = subtotal(puntajeTutorias70,puntaje3_9_7);
    document.getElementById("tutorias7").innerHTML = tutorias7;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias7:", tutorias7)
    
    //h
    const tutorias8 = subtotal(puntajeTutorias60,puntaje3_9_8);
    document.getElementById("tutorias8").innerHTML = tutorias8;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias8:", tutorias8)
    
    //i
    const tutorias9 = subtotal(puntajeTutorias50,puntaje3_9_9);
    document.getElementById("tutorias9").innerHTML = tutorias9;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias9:", tutorias9)

    //j
    const tutorias10 = subtotal(puntajeTutorias30_1,puntaje3_9_10);
    document.getElementById("tutorias10").innerHTML = tutorias10;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias10:", tutorias10)

    //k
    const tutorias11 = subtotal(puntajeTutorias20_2,puntaje3_9_11);
    document.getElementById("tutorias11").innerHTML = tutorias11;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias11:", tutorias11)

    //l
    const tutorias12 = subtotal(puntajeTutorias15_2,puntaje3_9_12);
    document.getElementById("tutorias12").innerHTML = tutorias12;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias12:", tutorias12)

    //m
    const tutorias13 = subtotal(puntajeTutorias30_2,puntaje3_9_13);
    document.getElementById("tutorias13").innerHTML = tutorias13;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias13:", tutorias13)

    //n
    const tutorias14 = subtotal(puntajeTutorias20_3,puntaje3_9_14);
    document.getElementById("tutorias14").innerHTML = tutorias14;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias14:", tutorias14)

    //o
    const tutorias15 = subtotal(puntajeTutorias15_3,puntaje3_9_15);
    document.getElementById("tutorias15").innerHTML = tutorias15;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias15:", tutorias15)

    //p
    const tutorias16 = subtotal(puntajeTutorias15_4,puntaje3_9_16);
    document.getElementById("tutorias16").innerHTML = tutorias16;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias16:", tutorias16)
    
    //q
    const tutorias17 = subtotal(puntajeTutorias10_2,puntaje3_9_17);
    document.getElementById("tutorias17").innerHTML = tutorias17;
    console.log("🚀 ~ onActv3SubTotal3_9 ~ tutorias17:", tutorias17)

//subtotal minimo resultante
    const score3_9 = minTutorias(tutorias1,tutorias2,tutorias3, tutorias4, tutorias5, tutorias6, tutorias7, tutorias8, tutorias9, tutorias10, tutorias11, tutorias12, tutorias13, tutorias14, tutorias15, tutorias16, tutorias17);
    scoreElement.textContent = score3_9.toFixed(2);
    document.getElementById("score3_9").innerHTML = score3_9;
    console.log(`Puntaje tabla ${componentIndex}:`, score3_9);
 
if (!isNaN(score3_9)) {
    docencia += score3_9;
  if(docencia>=700){

  document.getElementById("docencia").innerHTML = 700;
  }else{
    document.getElementById("docencia").innerHTML = docencia;
  }
     

    if (typeof window.updateTotalDocencia === 'function') {
        window.updateTotalDocencia();
    }
}
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

        function subtotal(value1, value2) {
      const st = value1 * value2;
      return st;
    }

    // --- New Data Fetching and Populating Logic ---

    let docenteDataCache = null; // Cache to store all form data

    /**
     * Fetches all form data for the logged-in user and stores it in the cache.
     */
    async function fetchAllDocenteData() {
        if (docenteDataCache) {
            return docenteDataCache; // Return cached data if available
        }

        // This is our new endpoint for the logged-in teacher
        const allDataEndpoint = '/formato-evaluacion/get-authenticated-docente-data'; 

        try {
            const response = await fetch(allDataEndpoint);
            if (!response.ok) {
                throw new Error('No se pudieron obtener los datos del servidor.');
            }
            docenteDataCache = await response.json();
            return docenteDataCache;
        } catch (error) {
            showMessage(error.message, 'red');
            console.error('Error fetching all docente data:', error);
            return null;
        }
    }

    /**
     * Populates the currently visible form with data from the cache.
     */
    async function populateCurrentForm() {
        const allData = await fetchAllDocenteData();
        if (!allData) {
            showMessage('No se pudieron cargar los datos para editar.', 'orange');
            return;
        }

        const currentStepDiv = document.querySelector('[id^="step"]:not([style*="display: none"])');
        if (!currentStepDiv) {
            showMessage('No hay un formulario visible para editar.', 'orange');
            return;
        }

        const formId = currentStepDiv.querySelector('form').id;
        const formData = allData[formId]; // e.g., allData['form3_1']

        if (!formData) {
            showMessage('No hay datos guardados para este formulario.', 'blue');
            return;
        }

        const form = document.getElementById(formId);
        for (const key in formData) {
            if (Object.hasOwnProperty.call(formData, key)) {
                const input = form.querySelector(`[name="${key}"], [id="${key}"]`);
                if (input) {
                    input.value = formData[key];
                }
            }
        }
        showMessage('Datos cargados. Ahora puedes editar el formulario.', 'green');
    }

    document.getElementById('edit-form-btn').addEventListener('click', populateCurrentForm);

    // --- End of New Logic ---

        </script>
        </body>

</html>
