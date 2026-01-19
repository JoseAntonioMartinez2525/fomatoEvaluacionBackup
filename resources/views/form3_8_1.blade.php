@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$docenteConfig =  $docenteConfig ?? [
        'formKey' => 'form3_8_1',
        'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data', 
        'docentesEndpoint' => '/formato-evaluacion/get-docentes',
        'dictEndpoint' => '/formato-evaluacion/get-form-data381',
        'dictCollectionKey' => 'form3_8_1',
        'userTypeForDict' => '',
        'docenteMappings' => [
        // score y su copia
        'score3_8_1' => 'score3_8_1',     
        // cantidades y subtotales
        'puntaje3_8_1' => 'puntaje3_8_1',
        'puntajeHoras3_8_1' => 'puntajeHoras3_8_1',
        ],
        // Mapeos para respuestas de dictaminadores (si aplica)
    'dictMappings' => [
        // comisiones / comIncisos
        '#comision3_8_1' => 'comision3_8_1',
        'comisionDict3_8_1' => 'comisionDict3_8_1',

        // observaciones (span o elementos de texto)
        '#obs3_8_1_1' => 'obs3_8_1_1',

        // repetir score/rc/stotals para sobrescribir si vienen desde dictaminador
        'score3_8_1' => 'score3_8_1',
        // cantidades y subtotales
        'puntaje3_8_1' => 'puntaje3_8_1',
        'puntajeHoras3_8_1' => 'puntajeHoras3_8_1',
    ],

    // Inputs ocultos que deben llenarse desde docenteData.form3_8_1
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
        'score3_8_1' => '0',
        '#comision3_8_1' => '0',
        '#obs3_8_1_1' => '',


    ],

];

if (!isset($docenteConfigForm)) {
    $docenteConfigForm = [
        'extraFields' => [
            'score3_8_1',
            'puntaje3_8_1',
            'puntajeHoras3_8_1',
            'comision3_8_1',
            'comisionDict3_8_1',
            'obs3_8_1_1',
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


    <link href="{{ asset('css/onePage.css') }}" rel="stylesheet">
<style>
.punto3_8_1{
    font-weight: none!important;
}

#PuntajeMaximo{
    color: #ffff;
    background-color: black;
    padding-left: 2rem;
    padding-right: 2rem;


}

button#btn3_8_1Button{
    margin-left: 60rem;
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

    $hasData = false;
    $checkFields = ['comision3_8_1'];
    foreach($checkFields as $f) {
        if (!empty($docenteConfig[$f] ?? null)) {
            $hasData = true;
            break;
        }
    }
$formId = $docenteConfigForm['formId'] ?? 'form3_8_1';
$formNumber = '381';
@endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if(isset($showSearch) && $userType !== 'docente' && $showSearch)
        {{-- Buscar Docentes: --}}
        <x-docente-search />
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_1 -->
        <form id="form3_8_1" action="/formato-evaluacion/store-form381" method="POST" data-teacher-email="{{ $teacherEmailFromUrl ?? '' }}" data-custom-url="true">
            @csrf
            @if($userType == 'dictaminador')
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            @endif
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="{{ $teacherEmailFromUrl ?? '' }}">
            <input type="hidden" name="user_type" value="">
            <div>
                <br>
                <!--3.8.1 RSU-->
                <h4>Puntaje máximo
                    @if($userType == 'secretaria') <!-- usuario secretaria -->
                        @if($mostrarSoloSpan)
                            <span id="PuntajeMaximo">40</span>
                        @else
                            <input class="pmax text-white px-4 mt-3" id="puntajeMaximo" placeholder="40" readonly
                                oninput="actualizarPuntajeMaximo(this.value);">
                            <button class="btn custom-btn printButtonClass" onclick="habilitarEdicion('puntajeMaximo')">Editar</button>
                            <button class="btn custom-btn printButtonClass" onclick="guardarEdicion('puntajeMaximo')">Guardar</button>
                        @endif
                    @else
                        <span id="PuntajeMaximo">40</span>

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
                        
                    </tr>
                </thead>
                <tbody>
                    <thead>
                        <tr>
                            <td id="seccion3_8_1" colspan="3" class="punto3_8_1" scope=col style="padding:-60px;" title="Responsabilidad Social Universitaria">
                                3.8.1 RSU</td>
                            <td id="score3_8_1" for="">0</td>
                            <td id="comision3_8_1">0</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td class="punto3_8_1">Factor</td>
                            <td class="punto3_8_1">Horas</td>
                            <td colspan="1"></td>
                            <td class="obsv table-ajust2" style="padding-left: -200px;">Observaciones</td>

                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td>1 por cada hora</td>
                            <td id="p3_8_1_1">1</td>
                            <td id="puntaje3_8_1"></td>
                            <td id="puntajeHoras3_8_1" class="rightSelect"></td>
                            <td class="td_obs rightSelect">
                                @if ($userType == 'dictaminador')
                                    <input type="number" step="0.01" id="comisionDict3_8_1" name="comisionDict3_8_1"
                                        oninput="onActv3Comision3_8_1()"
                                        value="{{ oldValueOrDefault('comisionDict3_8_1') }}">
                                @else
                                    <span id="comisionDict3_8_1" name="comisionDict3_8_1"></span>
                                @endif

                            </td>
                            <td class="td_obs text-center">
                                @if ($userType == 'dictaminador')
                                    <input class="table-header" id="obs3_8_1_1" name="obs3_8_1_1" type="text">
                                @else
                                    <span id="obs3_8_1_1" name="obs3_8_1_1"></span>
                                @endif

                            </td>
                        </tr>
                    </thead>
                    <!--Tabla informativa Acreditacion Actividad 3.8.1-->
                    <table>
                        <thead>
                            <tr>
                                <th class="acreditacion" scope="col">Acreditacion: </th>

                                <th class="descripcion" id="form3_8_1_1Acreditacion"><b>
                                        *JD,CAAC, DDCE, DDIE, SA,DIIP, según
                                        corresponda. </b> </th>
                                {{-- Lógica de botones --}}
                                @if($userType != 'docente')
                                <x-edit-button formId="{{ $formId }}" :form-number="$formNumber" :has-data="$hasData" :user-type="$userType" />
                                @endif
                                {{-- y el botón Enviar sólo se muestra por JS/Blade según la lógica; si quieres mantener fallback: --}}
                                @if(!$hasData && $userType != 'secretaria' && $userType != 'docente')
                                    <button type="submit" class="btn custom-btn printButtonClass" id="btn3_8_1Button">Enviar</button>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </tbody>
            </table>
        </form>
    </main>
    <center>

        <footer id="footerForm3_4">
            <center>
                <div id="convocatoria">
                    <!-- Mostrar convocatoria -->
                    @if(isset($convocatoria))

                        <div style="margin-right: -700px;">
                            <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                        </div>
                    @endif
                </div>
            </center>

            <div id="piedepagina" style="margin-left: 500px;margin-top:10px;">
                <x-form-renderer :forms="[['view' => 'form3_8_1', 'startPage' => 13, 'endPage' => 13]]" />
            </div>
        </footer>
    </center>
    <script>
        // let userType = "{{ $userType }}";
        
        window.onload = function () {
            const footerHeight = document.querySelector('footer').offsetHeight;
            const elements = document.querySelectorAll('.prevent-overlap');

            elements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                // Verifica si el elemento está demasiado cerca del footer y aplica page-break-before si es necesario
                if (rect.bottom + footerHeight > viewportHeight) {
                    element.style.pageBreakBefore = "always"; // Forzar salto antes
                }
            });

        };
        
        function minWithSum(value1, value2) {
            const sum = value1 + value2;
            return Math.min(sum, 200);


        }

         function showNotification(message, type) {
                // Create notification element
                const notification = document.createElement('div');
                notification.textContent = message;
                notification.style.cssText = `
        position: fixed;
        top: 500px;
        right: 500px;
        padding: 15px;
        background-color: ${type === 'success' ? '#4CAF50' : '#f44336'};
        color: white;
        border-radius: 10px;
        z-index: 1000;
    `;
             // Add animation keyframes
             const style = document.createElement('style');
             style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
             document.head.appendChild(style);

             // Add to document
             document.body.appendChild(notification);

             // Remove after 3 seconds
             setTimeout(() => {
                 notification.style.animation = 'slideOut 0.3s ease-in forwards';
                 setTimeout(() => {
                     notification.remove();
                     style.remove();
                 }, 300);
             }, 3000);
         }

          let puntajeMaximoGlobal = 40; // Estado global inicial

            // Habilita la edición del input
            function habilitarEdicion(idElemento) {
                if (userType !== '') return;
                const elemento = document.getElementById(idElemento);
                elemento.removeAttribute('readonly');
                //elemento.style.backgroundColor = 'white';
            }

            // Guarda el valor editado y bloquea el campo
            function guardarEdicion(idElemento) {
                if (userType !== '') return;
                const elemento = document.getElementById(idElemento);
                elemento.setAttribute('readonly', true);
                elemento.style.backgroundColor = '#353e4e'; // Fondo deshabilitado
                const puntajeMaximo = elemento.value;

                // Enviar el puntaje máximo al backend
                // Enviar el puntaje máximo al backend
                fetch('/formato-evaluacion/update-puntaje-maximo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ puntajeMaximo }) // Usa el valor actualizado
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data.message);
                        puntajeMaximoGlobal = puntajeMaximo; // Actualiza el valor global
                        alert('El puntaje máximo ha sido actualizado: ' + puntajeMaximoGlobal);
                    })
                    .catch(error => {
                        console.error('Error al actualizar el puntaje máximo:', error);
                        alert('Hubo un error al actualizar el puntaje máximo.');
                    });
            }

            // Actualiza el puntaje máximo dinámicamente
            function actualizarPuntajeMaximo(valor) {
                if (userType !== '') return;
                puntajeMaximoGlobal = valor;
                console.log('Puntaje máximo actualizado:', puntajeMaximoGlobal);
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

