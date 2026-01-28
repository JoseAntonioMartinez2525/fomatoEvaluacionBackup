@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
@endphp
<!DOCTYPE html>
<html lang="{{ $newLocale }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ $logo }}" type="image/png">
  <title>Evaluación docente</title> 
  
  <x-head-resources />
{{-- @include('partials.timer') --}}
<style>
  @media print {
    .footer-number::after {
      content: "1";
    }
  }


body.dark-mode #continuar{
  background-color: #456483!important;
}

body.dark-mode #continuar:hover{
    background-color: #6a5b9f!important;
    font-weight: bold;
}

button#edit-form-btn{
 margin-inline-start: 20rem;
 background-color: #82bdb2;
 border-color: transparent;
 color: white;
}

{
  width: min-content;
}

</style>
<script>
    window.isDarkModeGlobal = {{ $darkMode ?? false ? 'true' : 'false' }};
</script>
</head>

<body class="font-sans antialiased">
  <x-general-header />
  <div class="bg-gray-50 text-black/50">
    <div class="relative min-h-screen flex flex-col items-center justify-center">
      @if (Route::has('login'))
        @if (Auth::check())
        <x-nav-docentes :user="Auth::user()">
          <li class="nav-item">
            <a class="nav-link active enlaceSN" style="width: 300px;font-size: 20px;" href="javascript:void(0);" onclick="showStep(1)" title="Formato de Evaluación docente"><i class="fa-solid fa-align-justify"></i>&nbsp;Evaluación</a>
          </li>
          <ul class="actv3"><i class="fa-solid fa-clipboard-user"></i>&nbsp;Actividades/Apartados:
            <li><a href="javascript:void(0);" onclick="showStep(2)">1. Permanencia en las actividades de la docencia</a></li>
            <li><a href="javascript:void(0);" onclick="showStep(3)">2. Dedicación en el desempeño docente</a></li>
          </ul>
          {{-- <li class="nav-item"><a class="nav-link active enlaceSN" style="width: 300px;font-size: 20px;" href="{{ route('docencia') }}" title="Formato de Evaluación docente"><i class="fas fa-chalkboard-teacher"></i>&nbsp;Calidad en la docencia</a></li> --}}
        </x-nav-docentes>
        @endif
            <div id="instrucionEdit" style="margin-inline-start: 20rem;">
              <p>*Nota: Para editar una de las tablas de los formularios, haga clic en el botón ✎ Editar Formulario. <br> También podrá dirigirse a este elemento haciendo clic en cualquiera de los formularios deseados, ubicados en la barra de menú al lado izquierdo.</p>
            </div>
        <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>
        <button id="edit-form-btn" class="btn btn-info"><i class="fa-solid fa-pencil"></i>&nbsp;Editar Formulario</button>

        </section>

        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
        <div class="flex lg:justify-center lg:col-start-2"></div>

        <nav class="-mx-3 flex flex-1 justify-end"></nav>

  <div id="timer">   

        <div id="step1" style="display:block; margin-inline-start:10rem;">
            <div id="form1_readonly">

            <br>
            <label for="convocatoria" class="label">Convocatoria</label>
            <input name="convocatoria" type="text" class="input-header mb-3" id="convocatoria" value="{{ $convocatoria ?? '' }}" readonly>

            <label for="periodo" class="label">Periodo de evaluación:</label>
            <input name="periodo" id="periodo" type="text" class="input-header mb-3" value="{{ $periodo ?? '' }}" readonly>

            <label for="nombre" class="label">Nombre del personal académico:</label> 
            <input name="nombre" type="text" class="input-header mb-3" value="{{ $nombre ?? Auth::user()->name }}" readonly>

            <label for="area" class="label">Área de Conocimiento:</label>
            <input name="area" type="text" class="input-header mb-3" value="{{ $area ?? 'No definida' }}" readonly>

            <label for="departamento" class="label">Departamento Académico:</label>
            <input name="departamento" type="text" class="input-header mb-3" value="{{ $departamento ?? 'No definido' }}" readonly>
            <br><br>

            <center class="printCenter"><h5>Instrucciones</h5></center>

            <div class="container flex">
            <p class="instrucciones">1 La persona a ser evaluada deberá completar la información en
            cantidades u horas en los campos
            marcados en <u><b>color gris</b></u>. <br>
            2 La Comisión Dictaminadora deberá llenar los campos marcados en color azul cielo (puntajes totales o
            subtotales, según sea el caso). <br>
            3 No se deberán modificar fórmulas, ni agregar o quitar renglones. <br>
            4 Este formato deberá presentarse en forma independiente de la documentación que acrediten las
            actividades realizadas. <b>Para la evaluación no es necesario entregar las obras completas-libros,
            manuales, publicaciones,etc.,</b> sino entregar el documento probatorio que se indique en la Guía de
            definiciones. <br>
            5 La Comisión Dictaminadora no tomará en cuenta documentación que no esté contemplada dentro del
            formato de evaluación, asimismo no se aceptará documentación presentada de forma extemporánea.
            <center><button type="button" class="btn custom-btn" id="btn1" onclick="showStep(2)">Continuar</button>
            </center>
            </div>

          </div>
        </div>

        </header>

        <main class="container">
        <!--Actividad 1: Permanencia en las actividades de la docencia	-->
       <div id="step2" style="display:none;">   
        <form id="form2" method="POST" onsubmit="event.preventDefault(); submitForm('store2', 'form2');">
          <div>
          <h4>Puntaje máximo
          <label class="bg-black text-white px-4" for="">100</label>
          </h4>

          </div>
          @csrf
          <!-- Add hidden fields for user_id and email -->
          <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
          <input type="hidden" name="email" value="{{ auth()->user()->email }}">
          <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">

          <input type="hidden" id="puntajeEvaluarInput" name="puntajeEvaluar" value="0">
          <table class="table table-sm">
          <thead>
          <tr>
          <th scope="col">Actividad</th>
          <th class="table-ajust" scope="col">Años</th>
          <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
          <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
          <th class="table-ajust" scope="col">Observaciones</th>
          </tr>
          </thead>
          <tbody>
          <tr>
          <td style="margin-right: auto;"><b>1. Permanencia en las actividades de la docencia</b></td>
          <td class="horasActv2">
          @if(old('horasActv2') === null)
        
          <input type="number" id="horasActv2" name="horasActv2" class="form-control" value="{{ old('horasActv2') ?? '0.00' }}">
          @endif
          </td>
          <td id="puntajeEvaluar" class="puntajeEvaluar text-white">
          <label id="puntajeEvaluarText">0</label>
          </td>
          <td class="table-header comision">
            <span id="comision1" name="comision1" class="table-header comision"></span>
          {{-- <input type="number" id="comision1" name="comision1" class="table-header comision" step="any"> --}}
          </td>
          <td>
          <input id="obs1" name="obs1" class="table-header" type="text"></input>
          </td>
          </tr>
          </tbody>
          </table>
          <table>
          <thead>
          <tr>
          <th style="font-weight: normal; text-size: 20px;" scope="col">Acreditacion: </th>
          <th style="width:60px;padding-left: 100px;">SG</th>
          <th style="font-weight: normal; padding-left: 100px;">6.25 puntos por cada año de experiencia docente
          cumplido. A partir de los 16 años de experiencia docente se otorgarán los 100 puntos</th>
          </tr>
          </thead>
          </table>
          <button type="submit" class="btn custom-btn" id="form2_1Button">Enviar</button>
        </form>
       </div>

        <div id="step3" style="display:none;">
            <form id="form2_2" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store3', 'form2_2');">
            @csrf
            <div>
            <!--Actividad 2: Dedicacion en el Desempeño docente	-->
            <h4>Puntaje máximo
            <label class="bg-black text-white px-4 mt-3" for="">200</label>
            </h4>
            </div>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
            <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
            <table class="table table-sm">
            <thead>
            <tr>
            <th scope="col">Actividad</th>
            <th class="table-ajust" scope="col">Horas</th>
            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
            <th class="table-ajust" scope="col">Observaciones</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <td><b>2. Dedicacion en el Desempeño docente</b></td>
            <td for=""></td>
            <td id="hours" name="hours" for=""><label id="hoursText" for="">0</label></td>
            <td id="actv2Comision" name="actv2Comision" for=""></td>
            </tr>
            <tr>
            <td><label for="">a) Posgrado</label>
            <label for="">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Semestre </label>
            </td>
            <td><input id="horasPosgrado" name="horasPosgrado" class="horasActv2" placeholder="0" type="number" value="{{ oldValueOrDefault('horasPosgrado') }}"></td>
            <td class="puntajeEvaluar2"><label id="DSE" name="dse"class="puntajeEvaluar" type="text"></label></td>
            <td class="comision actv"><span id="comisionPosgrado"></span></td>
            <td><input id="obs2" name="obs2" class="table-header" type="text"></td>
            </tr>
            <tr>
            <td>b) Licenciatura y TSU
            <label for="">&nbsp &nbsp &nbsp &nbsp Horas </label>
            </td>
            <td>
            <input id="horasSemestre" name="horasSemestre" class="horasActv2" placeholder="0" type="number"  value="{{ oldValueOrDefault('horasSemestre') }}">
            </td>
            <td class="puntajeEvaluar2"><label id="DSE2" name="dse2" class="puntajeEvaluar" type="text"></label></td>
            <td class="comision actv"><span id="comisionLic"></span>
            </td>
            <td><input id="obs2_2" name="obs2_2" class="table-header" type="text"></input></td>
            </tr>
            </tbody>
            </table>
            <table>
            <thead>
            <tr>
            <th style="font-weight: normal; text-size: 20px;" scope="col">Acreditacion: </th>
            <th style="width:60px;padding-left: 100px;">DSE/DIIP</th>
            <th style="font-weight: normal; padding-left: 100px;">8.5 puntos por cada hora/semana/año en cada
            caso
            </th>
            <th>
            <button type="submit" class="btn custom-btn" id="form2_2Button">Enviar</button>
            </th>
            </tr>
            </thead>
            </table>      
          </form>

          <input type="hidden" id="hoursHidden" name="hours" value="0">
        </div>

        <div id="continueButtonWrapper" style="display:none; text-align:center; margin-top:20px;">
          <a href="{{ url('docencia') }}" class="btn" id="continuar">Continuar a Actividad 3 — Calidad en la Docencia</a>
        </div>
   </div>    

    @endif
    </div>
    </main>



<footer>
  <div>
 
<canvas id="convocatoriaCanvas" width="1500" height="500"></canvas>
  </div>

</footer>

  </div>
  </div>
  </div>
  </div>

  <script>
document.addEventListener("DOMContentLoaded", () => {
    const btnContinue = document.getElementById('continuar');
    const body = document.body;

    let isDarkMode = body.classList.contains('dark-mode');

    // Función para aplicar colores normales según el modo
    function applyNormalColors() {
        if (isDarkMode) {
            btnContinue.style.backgroundColor = "#456483";
            btnContinue.style.color = "floralwhite";
        } else {
            btnContinue.style.backgroundColor = "#72aaca";
            btnContinue.style.color = "white";
        }
    }

    // Estilo inicial
    applyNormalColors();
    btnContinue.style.transition = "background-color 0.3s"; // suaviza hover

    // Hover dinámico
    btnContinue.addEventListener("mouseenter", () => {
        btnContinue.style.backgroundColor = isDarkMode ? "#6a5b9f" : "#7ac1ca";
    });

    btnContinue.addEventListener("mouseleave", () => {
        applyNormalColors(); // vuelve al color normal según el modo
    });
});







    // const convocatoria = document.querySelector('nav a').textContent.trim();
    // const periodo = document.getElementById('periodo').textContent;
    // const nombre = document.querySelector('input[name="nombre"]').value;
    // const area = document.querySelector('select[name="area"]').value;
    // const departamento = document.querySelector('select[name="departamento"]').value;
    const horasPosgrado = document.getElementById('horasPosgrado').value;
    const horasSemestre = document.getElementById('horasSemestre').value;

    const obs1 = document.getElementById('obs1').textContent;
    const obs2 = document.getElementById('obs2').textContent;
    const obs2_2 = document.getElementById('obs2_2').textContent;
    const hours = document.querySelector('#hoursText');

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
    
function onChange() {
    // Obtener elementos (si no existen, salimos sin error)
    const horasPosgradoEl = document.getElementById("horasPosgrado");
    const horasSemestreEl = document.getElementById("horasSemestre");
    const dseEl = document.getElementById("DSE");
    const dse2El = document.getElementById("DSE2");
    const hoursTextEl = document.getElementById("hoursText");

    // Si ninguno de los inputs existe, no hay nada que calcular
    if (!horasPosgradoEl && !horasSemestreEl) return;

    // Leer valores de forma segura (0 por defecto)
    const puntajePosgrado = parseFloat(horasPosgradoEl?.value) || 0;
    const puntajeSemestre = parseFloat(horasSemestreEl?.value) || 0;

    // Cálculos
    const dsePosgrado = puntajePosgrado * 8.5;
    const dseSemestre = puntajeSemestre * 8.5;
    const hora = dsePosgrado + dseSemestre;

    // Actualizar elementos si existen
    if (dseEl) dseEl.innerText = Number.isFinite(dsePosgrado) ? dsePosgrado : 0;
    if (dse2El) dse2El.innerText = Number.isFinite(dseSemestre) ? dseSemestre : 0;

    // minWithSum puede ser tu función existente; si no existe, usamos hora directamente
    const minimo = (typeof minWithSum === 'function') ? minWithSum(dsePosgrado, dseSemestre) : hora;

    if (hoursTextEl) hoursTextEl.innerText = Number.isFinite(minimo) ? minimo : 0;

    const hoursHidden = document.getElementById("hoursHidden");
    if (hoursHidden) hoursHidden.value = minimo;

    // Para debugging (opcional)
    console.log('onChange ->', { puntajePosgrado, puntajeSemestre, dsePosgrado, dseSemestre, minimo, hoursHidden });
}




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
    let lastScrollLeft = 0; // Variable to store the last horizontal scroll position

    window.addEventListener('scroll', () => {
      let currentScrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

      // Check if scrolling to the right
      if (currentScrollLeft > lastScrollLeft) {
        // Scrolling to the right, hide the navigation
        nav.style.display = 'none';
      } else {
        // Scrolling to the left or not horizontally, show the navigation
        nav.style.display = 'block';
      }

      lastScrollLeft = currentScrollLeft <= 0 ? 0 : currentScrollLeft; // For Mobile or negative scrolling
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

    function subtotal(value1, value2) {
      const st = value1 * value2;
      return st;
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
    function actualizarData(fieldId, value) {
      console.log(`Campo ${fieldId} actualizado a ${value}`);
    }

    document.querySelector('input[name="horasActv2"]').addEventListener('input', function () {
      let horasActv2 = parseFloat(this.value) || 0;
      let puntajeEvaluar = 0;

      const A40 = 6.25;
      const B56 = 17;
      const B57 = 50;
      const variables = {};
      const variablesMultiplicadas = {};

      for (let i = 40; i <= 55; i++) {
        variables[`B${i}`] = i - 39;

        variablesMultiplicadas[`C${i}`] = A40 * variables[`B${i}`]; // Calculate and store in variablesMultiplicated object

        if (horasActv2 === variables[`B${i}`]) {
          puntajeEvaluar = variablesMultiplicadas[`C${i}`];
          break;
        }
      }

      const C56 = B56 * A40;
      const C57 = B57 * A40;

      if (horasActv2 >= 16) {
        puntajeEvaluar = 100;
      }

      // Obtiene una referencia a la etiqueta <td id="puntajeEvaluar">
      let puntajeEvaluarElement = document.getElementById('puntajeEvaluar');

      // Actualiza el valor de la etiqueta <td id="puntajeEvaluar">
      puntajeEvaluarElement.innerText = puntajeEvaluar.toFixed(2);
      document.getElementById('puntajeEvaluarInput').value = puntajeEvaluar.toFixed(2);
    });
    // Define the submitForm function globally
    async function submitForm(url, formId) {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // Get form data
      let formData = {};
      let form = document.getElementById(formId);
      // Ensure the form element exists
      if (!form) {
        console.error(`Form with id "${formId}" not found.`);
        return;
      }

      // Recoge los datos dependiendo del formulario actual
      switch (formId) {
        case 'form2':
          formData['user_id'] = form.querySelector('input[name="user_id"]').value;
          formData['email'] = form.querySelector('input[name="email"]').value;
          formData['user_type'] = form.querySelector('input[name="user_type"]').value;
          formData['horasActv2'] = form.querySelector('input[name="horasActv2"]').value;
          formData['puntajeEvaluar'] = form.querySelector('#puntajeEvaluarInput')?.value || 0; // Use the hidden input for the value
          formData['obs1'] = form.querySelector('input[name="obs1"]').value;
          break;

        case 'form2_2':
          formData['user_id'] = form.querySelector('input[name="user_id"]').value;
          formData['email'] = form.querySelector('input[name="email"]').value;
          formData['user_type'] = form.querySelector('input[name="user_type"]').value;
          console.log('User Type:', formData['user_type']);
          formData['horasPosgrado'] = form.querySelector('input[name="horasPosgrado"]').value || '';
          formData['horasSemestre'] = form.querySelector('input[name="horasSemestre"]').value || '';
          formData['dse'] = form.querySelector('label[name="dse"]').textContent || '';
          formData['dse2'] = form.querySelector('label[name="dse2"]').textContent || '';
          
          let hoursLabel = form.querySelector('label[id="hoursText"]');

          if (!hoursLabel) {
            console.error('Label with id "hoursText" not found.');
          } else {
            formData['hours'] = hoursLabel.innerText;
          }
          formData['obs2'] = form.querySelector('input[name="obs2"]').value || '';
          formData['obs2_2'] = form.querySelector('input[name="obs2_2"]').value || '';
          break;
      }

      console.log('Form data:', formData);
      
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

        // Read response body as text and attempt to parse JSON (so we can show server error messages)
        const responseText = await response.text();
        let responseData;
        try {
          responseData = responseText ? JSON.parse(responseText) : {};
        } catch (err) {
          responseData = { success: false, message: responseText || 'Invalid JSON response from server' };
        }

        if (!response.ok) {
          console.error('Server responded with an error:', responseData.message || responseText);
          showMessage('Error de servidor: ' + ('No se pudo procesar la solicitud.'), 'red');
          return;
        }

      // Solo mostrar éxito si el servidor marca success === true
        if (responseData && responseData.success === true) {
          // Mensaje de éxito manual
          showMessage('Formulario enviado', 'green');
            // --- Lógica para avanzar al siguiente paso ---
            const currentStep = stepMap[formId];
            const nextStep = currentStep + 1;

            if (document.getElementById(`step${nextStep}`) || (formId === 'form2_2' && document.getElementById('continueButtonWrapper'))) {
                showStep(nextStep);
                localStorage.setItem("ultimoStepWelcome", nextStep);
            } else {
                // Si es el último formulario (form2_2), mostramos el botón para continuar a 'docencia'
                if (formId === 'form2_2') {
                    document.getElementById('continueButtonWrapper').style.display = 'block';
                    localStorage.setItem("ultimoStepWelcome", "FIN");
                }
            }

        } else {
          console.error('Submission failed:', responseData);
          showMessage('Formulario no enviado. Verifique los datos.', 'red');
        }

      } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
         showMessage('Error de conexión. Intente de nuevo.', 'red');

      }
    }

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
        // Los mensajes aquí son informativos para el usuario durante la edición.
        const allData = await fetchAllDocenteData();
        if (!allData) {
            showMessage('No se pudieron cargar los datos para editar.', 'red');
            return;
        }

        // Determine the currently visible step/form
        let currentFormId = null;
        const currentStepDiv = document.querySelector('[id^="step"]:not([style*="display: none"])');
        if (!currentStepDiv) {
            // No mostramos mensaje si no hay formulario, es un estado normal al inicio.
            return;
        }
        currentFormId = currentStepDiv.querySelector('form').id;

        if (!currentFormId) {
            return;
        }

        const formData = allData[currentFormId]; // e.g., allData['form1']

        if (!formData) {
            showMessage('No hay datos previos para este formulario.', 'blue');
            return;
        }

        const form = document.getElementById(currentFormId);
          for (const key in formData) {
              if (Object.hasOwnProperty.call(formData, key)) {
                  const element = form.querySelector(`[name="${key}"], [id="${key}"]`);
                  if (element) {
                      if (element.tagName === 'INPUT' || element.tagName === 'SELECT' || element.tagName === 'TEXTAREA') {
                          element.value = formData[key];
                          // Disparamos el evento input para recalcular valores dinámicos
                          if (element.id === 'horasPosgrado' || element.id === 'horasSemestre' || element.id === 'horasActv2') {
                              element.dispatchEvent(new Event('input'));
                          }

                      } else if (element.tagName === 'LABEL' || element.tagName === 'SPAN') {
                            // Etiquetas simples: actualizamos su texto
                            element.textContent = formData[key];
                        } else if (element.tagName === 'TD' || element.tagName === 'TH') {
                            // Si el TD/TH contiene labels/spans con ids conocidos, actualizamos esos hijos
                            // (esto evita borrar <label id="hoursText"> o <label id="DSE"> dentro del td)
                            const childHours = element.querySelector('#hoursText');
                            const childDSE = element.querySelector('#DSE');
                            const childDSE2 = element.querySelector('#DSE2');

                            if (childHours) {
                                childHours.innerText = formData[key];
                            } else if (childDSE) {
                                childDSE.innerText = formData[key];
                            } else if (childDSE2) {
                                childDSE2.innerText = formData[key];
                            } else {
                                // Ningún child específico encontrado: ponemos el texto directo en el TD
                                element.textContent = formData[key];
                            }
                        }
                  }
              }
          }

          if (currentFormId === 'form2_2') {
        // Llamada única y segura una vez que ya se asignaron todos los values
        setTimeout(() => {
            try { onChange(); } catch (e) { console.error('onChange error:', e); }
        }, 50);
    }

        showMessage('Datos cargados. Ahora puedes editar el formulario.', 'green');
    }

    // MAPA DE RUTAS Y STEPS (similar a docencia.blade.php)
    const routeMap = {
        form2: { store: '/formato-evaluacion/store2', fetch: '/formato-evaluacion/get-data2' },
        form2_2: { store: '/formato-evaluacion/store3', fetch: '/formato-evaluacion/get-data22' },
    };

    const stepMap = {
        form2: 2,
        form2_2: 3, // Usamos 3 para diferenciarlo
    };

    function showStep(stepNumber) {
        document.querySelectorAll('[id^="step"]').forEach(step => {
            step.style.display = "none";
        });
        document.getElementById("continueButtonWrapper").style.display = "none";

        const stepId = `step${stepNumber.toString().replace('.', '_')}`;
        const current = document.getElementById(stepId);
        if (current) {
            current.style.display = "block";
        } else if (stepNumber > 3) { // Si hemos pasado el último formulario
            document.getElementById('continueButtonWrapper').style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Restaurar progreso
        const ultimo = localStorage.getItem("ultimoStepWelcome");
        if (ultimo) {
            if (ultimo === "FIN") {
                showStep(3); // Un número mayor que el último step para mostrar el botón
            } else {
                showStep(parseFloat(ultimo));
            }
        } else {
            showStep(1);
        }

        // Asignar onsubmit
        Object.keys(routeMap).forEach(formId => {
            const form = document.getElementById(formId);
            if (!form) return;

            form.onsubmit = function (event) {
                event.preventDefault();
                const storeUrl = routeMap[formId].store;
                submitForm(storeUrl, formId);
            };
        });

        document.getElementById('edit-form-btn').addEventListener('click', populateCurrentForm);
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
      context.textAlign = 'center';
      context.textBaseline = 'middle';

      // Draw the text
      context.fillText(text, canvas.width / 2, canvas.height / 2);
    }

    // Get the input element with id 'convocatoria'
    var convocatoriaInput = document.getElementById('convocatoria');
    if (convocatoriaInput) {
      // Update the canvas initially with the placeholder value or empty
      updateCanvas(convocatoriaInput.value || convocatoriaInput.placeholder);

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

    const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
    if (toggleDarkModeButton) {
      const widthDarkButton = window.outerWidth - 230;
      toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
    }

    toggleDarkMode();
  });   
  
  document.addEventListener('DOMContentLoaded', function() {
  const inputs = document.querySelectorAll('#horasPosgrado, #horasSemestre');
  inputs.forEach(input => input.addEventListener('input', onChange));
});


  </script>

</body>

</html>