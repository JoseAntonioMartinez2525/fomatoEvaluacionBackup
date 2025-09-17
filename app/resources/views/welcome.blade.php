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
<style>
  @media print {
    .footer-number::after {
      content: "1";
    }
  }
</style>

</head>

<body class="font-sans antialiased">
  <x-general-header />
  <div class="bg-gray-50 text-black/50">
    <div class="relative min-h-screen flex flex-col items-center justify-center">
      @if (Route::has('login'))
        @if (Auth::check() && Auth::user()->user_type === 'docente')
        <x-nav-docentes :user="Auth::user()" />
        @endif
        <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

        </section>

        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
        <div class="flex lg:justify-center lg:col-start-2"></div>

        <nav class="-mx-3 flex flex-1 justify-end"></nav>


        <form id="form1" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store', 'form1');">

        <label for="convocatoria" class="label">Convocatoria</label>
        <input name="convocatoria" type="text" class="input-header mb-3" id="convocatoria"></input>

        <label for="periodo" class="label">Periodo de evaluación:</label>
        <input name="periodo" id="periodo" type="text" class="input-header mb-3"></input>

        <label for="nombre" class="label">Nombre del personal académico:</label> <input name="nombre" type="text"
        class="input-header mb-3"></input>

        <label for="area" class="label">Área de Conocimiento:</label>
        <select name="area" id="area" class="form-select input-header" aria-label="Default select example" required>
        @foreach ($areaOptions as $option)
        <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
        </select>
        <label for="departamento" class="label">Departamento Académico:</label>
        <select name="departamento" id="departamento" class="input-header" aria-label="Default select example"
        required>
        @foreach ($departamentoOptions as $option)
        <option option value="{{ $option }}">{{ $option }}</option>
        @endforeach
        </select><br><br>

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
        <center><button type="submit" class="btn custom-btn" id="btn1">Enviar</button>
        </center>
        </div>

        </form>
        </header>

        <main class="container">
        <!--Actividad 1: Permanencia en las actividades de la docencia	-->

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
        <input type="number" id="comision1" name="comision1" class="table-header comision" step="any">
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
        <td><input id="horasPosgrado" name="horasPosgrado" class="horasActv2" placeholder="0" type="number" oninput="onChange()"value="{{ oldValueOrDefault('horasPosgrado') }}"></td>
        <td class="puntajeEvaluar2"><label id="DSE" name="dse"class="puntajeEvaluar" type="text"></label></td>
        <td class="comision actv"><input id="comisionPosgrado" placeholder="0" for=""
        oninput="onActv2Comision()"></input></td>
        <td><input id="obs2" name="obs2" class="table-header" type="text"></td>
        </tr>
        <tr>
        <td>b) Licenciatura y TSU
        <label for="">&nbsp &nbsp &nbsp &nbsp Horas </label>
        </td>
        <td>
        <input id="horasSemestre" name="horasSemestre" class="horasActv2" placeholder="0" type="number" oninput="onChange()" value="{{ oldValueOrDefault('horasSemestre') }}">
        </td>
        <td class="puntajeEvaluar2"><label id="DSE2" name="dse2" class="puntajeEvaluar" type="text"></label></td>
        <td class="comision actv"><input id="comisionLic" placeholder="0" oninput="onActv2Comision()"></input>
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

    const convocatoria = document.querySelector('nav a').textContent.trim();
    const periodo = document.getElementById('periodo').textContent;
    const nombre = document.querySelector('input[name="nombre"]').value;
    const area = document.querySelector('select[name="area"]').value;
    const departamento = document.querySelector('select[name="departamento"]').value;
    const horasPosgrado = document.getElementById('horasPosgrado').value;
    const horasSemestre = document.getElementById('horasSemestre').value;

    const obs1 = document.getElementById('obs1').textContent;
    const obs2 = document.getElementById('obs2').textContent;
    const obs2_2 = document.getElementById('obs2_2').textContent;
    const hours = document.querySelector('#hoursText');
    //const actv2Comision = document.querySelector('#actv2ComisionText');


    let data = {
      convocatoria: convocatoria,
      periodo: periodo,
      nombre: nombre,
      area: area,
      departamento: departamento,
      horasPosgrado: horasPosgrado,
      horasSemestre: horasSemestre,
      obs1: obs1,
      obs2: obs2,
      obs2_2: obs2_2,
      docencia: docencia,
      hours: hoursText,
  
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
    function onChange() {
      // Obtener los valores de los inputs
      const puntajePosgrado = parseFloat(document.getElementById("horasPosgrado").value);
      const puntajeSemestre = parseFloat(document.getElementById("horasSemestre").value);
      const h = parseFloat(document.querySelector('#hoursText'));

      // Realizar los cálculos
      const dsePosgrado = puntajePosgrado * 8.5;
      const dseSemestre = puntajeSemestre * 8.5;
      const hora = (dsePosgrado + dseSemestre);

      // Actualizar el contenido de las etiquetas <label>
      document.getElementById("DSE").innerText = dsePosgrado;
      document.getElementById("DSE2").innerText = dseSemestre;

      // Mostrar los valores actualizados en la consola
      console.log(dsePosgrado);
      console.log(dseSemestre);

      const minimo = minWithSum(dsePosgrado, dseSemestre);

      document.getElementById("hoursText").innerText = minimo;
      console.log(minimo);


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
    function actualizarData() {
      data[this.id] = this.value;
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
    // Definir la función submitForm fuera de DOMContentLoaded
    
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
          case 'form1':
            formData['convocatoria'] = form.querySelector('input[name="convocatoria"]').value;
            formData['periodo'] = form.querySelector('input[name="periodo"]').value;
            formData['nombre'] = form.querySelector('input[name="nombre"]').value;
            formData['area'] = form.querySelector('select[name="area"]').selectedOptions[0].textContent;
            formData['departamento'] = form.querySelector('select[name="departamento"]').selectedOptions[0].textContent;
            break;

          case 'form2':
            formData['user_id'] = form.querySelector('input[name="user_id"]').value;
            formData['email'] = form.querySelector('input[name="email"]').value;
            formData['user_type'] = form.querySelector('input[name="user_type"]').value;
            formData['horasActv2'] = form.querySelector('input[name="horasActv2"]').value;
            formData['puntajeEvaluar'] = form.querySelector('input[name="puntajeEvaluar"]').value;
            formData['obs1'] = form.querySelector('input[name="obs1"]').value;
            break;

          case 'form2_2':
            formData['user_id'] = form.querySelector('input[name="user_id"]').value;
            formData['email'] = form.querySelector('input[name="email"]').value;
            formData['user_type'] = form.querySelector('input[name="user_type"]').value;
            console.log('User Type:', formData['user_type']);
            formData['horasPosgrado'] = form.querySelector('input[name="horasPosgrado"]').value;
            formData['horasSemestre'] = form.querySelector('input[name="horasSemestre"]').value;
            formData['dse'] = form.querySelector('label[name="dse"]').textContent;
            formData['dse2'] = form.querySelector('label[name="dse2"]').textContent;
            
            let hoursLabel = form.querySelector('label[id="hoursText"]');

            if (!hoursLabel) {
              console.error('Label with id "hoursText" not found.');
            } else {
              formData['hours'] = hoursLabel.innerText;
            }
            formData['obs2'] = form.querySelector('input[name="obs2"]').value;
            formData['obs2_2'] = form.querySelector('input[name="obs2_2"]').value;
            break;
        }

        console.log('Form data:', formData);
        
        // Enviar datos al servidor
        try {
          // let appUrl = `/formato-evaluacion/`+url;
          let response = await fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
          });

          /*
          const responseText = await response.text(); // Obtener la respuesta como texto
          console.log('Raw response from server:', responseText); // Ver qué se devuelve
            */
          if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
          }

          let data = await response.json(); // Esto será seguro de usar si estás seguro de que la respuesta es JSON
          console.log('Response received from server:', data);
          showMessage('Formulario enviado', 'green');

        } catch (error) {
          console.error('There was a problem with the fetch operation:', error);
           showMessage('Formulario no enviado', 'red');

        }
      }

      // Cuando el DOM se ha cargado completamente, puedes agregar los controladores de eventos
      document.addEventListener('DOMContentLoaded', function () {
        // Asociar la función a los formularios
        const form1 = document.getElementById('form1');
        if (form1) {
          form1.onsubmit = function (event) {
            event.preventDefault(); // Previene el envío por defecto
            submitForm('/formato-evaluacion/store', 'form1'); // Llama a la función submitForm
          };
        }
        const form2 = document.getElementById('form2');
        if (form2) {
          form2.onsubmit = function (event) {
            event.preventDefault(); // Previene el envío por defecto
            submitForm('/formato-evaluacion/store2', 'form2'); // Llama a la función submitForm
          };
        }

        const form2_2 = document.getElementById('form2_2');
        if (form2_2) {
          form2_2.onsubmit = function (event) {
            event.preventDefault(); // Previene el envío por defecto
            submitForm('/formato-evaluacion/store3', 'form2_2'); // Llama a la función submitForm
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
          console.log (label);
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
      context.textAlign = 'center';
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