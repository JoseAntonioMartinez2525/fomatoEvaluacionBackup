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
    .p2{
        font-weight: bold;
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
    $userType = Auth::user()->user_type;
    $sections;
    $subtotalAdded = false; 
@endphp

    <main class="container">
        <form id="form4" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-resume', 'form4');" >
            @csrf
            <div>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
            <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
            <center>
            <h2 id="resumen">Resumen</h2>
            <h4>A ser llenado por la Comisión del PEDPD</h4></center>
            <table class="resumenTabla">
            <thead>
                
    <tr>
    <th id="actv">Actividad</th>
    <th id="pMaximo">Puntaje máximo</th>
    <th id="pComision">Puntaje otorgado Comisión PEDPD</th>
    </tr>
    @if (isset($sections['data']) && is_array($sections['data']))
    @php
    $counter = 0;
    $subtotalIndexes = [13, 17, 23, 27];
    @endphp
    @foreach ($sections['data'] as $index => $data)
    @if (in_array($index, $subtotalIndexes))
    {{-- Mostrar subtotales --}}
    <tr>
    <td colspan="2">
    <center><b>{{ $data['label'] }}</b></center>
    </td>
    <td><b>
    @if (in_array($data['label'], ['1. Permanencia en las actividades de la docencia', '2. Dedicación en el desempeño docente', '3. Calidad en la docencia']))
        <label class="p2">{{ $data['comision'] }}</label>
    @else
        <label>{{ $data['comision'] }}</label>
    @endif
    </b></td>
    </tr>
    @else
    {{-- Datos normales --}}
    <tr>
    <td @if (in_array($data['label'], ['1. Permanencia en las actividades de la docencia', '2. Dedicación en el desempeño docente'])) style="font-weight: bold" @elseif (in_array($data['label'], ['3. Calidad en la docencia'])) style="font-weight: bold" @elseif(in_array($data['label'], ['1.1 Años de experiencia docente en la institución', '2.1 Carga de trabajo docente frente a grupo'])) style="background-color: #A4DDED;" @endif>
    {{ $data['label'] }}
    </td>
    <td @if ($counter === 0 || $counter === 2 || $counter === 4) style="font-weight: bold" @endif class="p1">
    {{ $data['value'] }}
    </td>
    <td class="tdResaltado">
    @if (in_array($data['label'], ['1. Permanencia en las actividades de la docencia', '2. Dedicación en el desempeño docente', '3. Calidad en la docencia']))
        <span id="{{ $data['id'] ?? '' }}" class="p2">{{ $data['comision'] }}</span>
    @elseif (in_array($index, $subtotalIndexes))
        {{-- Este bloque maneja los subtotales y no les aplica el fondo amarillo --}}
        <span id="{{ $data['id'] ?? '' }}">{{ $data['comision'] }}</span>
    @else
        {{-- Aplicar fondo amarillo para todos los demás casos --}}
        <span style="background-color: #FFC72C;" id="{{ $data['id'] ?? '' }}">{{ $data['comision'] }}</span>
    @endif
    </td>
    </tr>
    @endif

    @php
    $counter++; // Incrementamos el contador en cada iteración
    @endphp
    @endforeach
    @endif

    @dd($sections);
                <tr>

                    <td>
                        <center><b>Total logrado en la evaluación</b></center>
                    </td>
                    <td></td>
                    <td><label id="totalComision" for="" class="p2"></label></td>
                </tr>
                
                <tr>
                    <td>
                        <center><b>Total de puntaje obtenido en la evaluación</b></center>
                    </td>
                    <td></td>
                        <td><b><label id="totalComisionRepetido" class="p2">{{ $totalComisionRepetido }}</label></b></td>
            
                <tr>
                    <th>Nivel obtenido de acuerdo al artículo 10 del Reglamento</th> 
                    <th>Mínima de Calidad</th>
                    <th><b><span id="minimaCalidad">{{ $minimaCalidad }}</span></b></th>
                </tr>
                <tr>
                    <th></th>
                    <th>Mínima Total</th>
                    <th><b><span id="minimaTotal">{{ $minimaTotal }}</span></b></th>
                </tr>
            </thead>
            </table>
            <center>
                <button type="submit" class="btn custom-btn buttonSignature">Enviar</button>
            </center>
            </div>
        </form>

        <br>
    </div>
    </main>

    <div>

        <footer>
            <div>
                <label id="convocatoriaPeriodoLabel" style="color:black;"></label>
            </div>
            @component('components.pie-pag', ['number' => '3'])
            @endcomponent
        </footer>

    </div>
</div>
</div>
</div>

    <script>


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
                nav.style.transition = 'display 0.5s ease'; 
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

       
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function submitForm(url, formId) {
            // Get form data
            let form = document.getElementById(formId);
            let formData = new FormData(form);
            formData.append('formId', formId);

            // Recoge los datos dependiendo del formulario actual
            if (formId == 'form4') {
                formData.set('user_id', form.querySelector('input[name="user_id"]').value);
                formData.set('email', form.querySelector('input[name="email"]').value);

                // Obtener valores de los labels y spans
             formData.set('comision_actividad_1_total', document.getElementById('comision1Total').innerText);
                formData.set('comision_actividad_2_total', document.getElementById('comision2Total').innerText);
                formData.set('comision_actividad_3_total', document.getElementById('comision3Total').innerText);
                formData.set('total_puntaje', document.getElementById('totalComisionRepetido').innerText);
                formData.set('minima_calidad', document.getElementById('minimaCalidad').innerText);
                formData.set('minima_total', document.getElementById('minimaTotal').innerText);


                // Log form data to check values
                console.log('Form data: ', formData);
            }
            try {
                let response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const contentType = response.headers.get('Content-Type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Invalid JSON response');
                }

                let data = await response.json();
                console.log('Response received from server:', data);

                if (formId === 'form5') {
                document.getElementById('reportLink').classList.remove('d-none');
                }

               
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        }

        window.submitForm = submitForm;
    });



    document.addEventListener('DOMContentLoaded', function () {
        const userId = {{ auth()->user()->id }};

        async function fetchData(url, params = {}) {
            const queryString = new URLSearchParams(params).toString();
            const fullUrl = `${url}?${queryString}`;

            try {
                let response = await fetch(fullUrl, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                });


                let data = await response.json();
                return data;
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error.message);
                alert(error.message);
            }
        }

        async function loadAllData() {
            let data = await fetchData('/formato-evaluacion/get-form-data', { dictaminador_id: userId });

            if (data && data.dictaminador.dictaminador_id) {
                // Asignar los valores de las comisiones automáticamente
                if (data.form_data) {
                    Object.keys(data.form_data).forEach(formKey => {
                        const form = data.form_data[formKey];
                        const comisionField = formKey.replace('DictaminatorsResponse', '').toLowerCase() + 'Comision';
                        if (form && document.getElementById(comisionField)) {
                            document.getElementById(comisionField).textContent = form.comision || '0';
                        }
                    });

                    // Calcular el puntaje total
                    calculateTotalScore();
                }
            } else {
                console.error('Error: Dictaminador not found or user type is invalid.');
            }
        }


    
    });


            function min700(...values){
                const total = values.reduce((acc, val) => acc + val, 0);
                return Math.min(total, 700);
            }

            function total() {
                const comision1 = parseFloat(document.getElementById('comision1Total').textContent) || 0;
                const comision2 = parseFloat(document.getElementById('comision2Total').textContent) || 0;
                const comision3 = parseFloat(document.getElementById('comision3Total').textContent) || 0;

                let suma = comision1 + comision2 + comision3;
                return Math.min(suma, 700);
            }



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
            document.getElementById('reportLink').classList.remove('d-none');
        }
    });

    document.addEventListener('DOMContentLoaded', async () => {
           

            // Current user type from the backend
            const userType = @json($userType);  // Get user type from backend

            // Fetch dictaminador options if user type is null or empty
            if (userType != 'docente') {
                try {
                    const response = await fetch('/formato-evaluacion/get-dictaminadores');
                    const dictaminadores = await response.json();

                        const dictaminadorId = event.target.value;


                        if (dictaminadorId) {
                            try {

                                const response = await axios.get('/formato-evaluacion/get-dictaminador-data', {
                                    params: { email: email, dictaminador_id: dictaminadorId }  // Send both ID and email
                                });
                                const data = response.data;

                                document.getElementById('comision1').textContent = data.form2.comision1 || '0';
                                document.getElementById('actv2Comision').textContent = data.form2_2.actv2Comision || '0';
                                document.getElementById('actv3Comision').textContent = data.form3_1.actv3Comision || '0';
                           
                                // Populate fields with fetched data
                                for (let i = 2; i <= 19; i++) {
                                    const elementId = 'comision3_' + i;
                                    const formId = 'form3_' + i;

                                    const scoreValue = data[formId]['comision3_' + i] || '0';

                                    document.getElementById(elementId).textContent = scoreValue;
                                }

                                    console.error('No form data found for the selected dictaminador.');

                                    // Reset input values if no data found
                                    document.querySelector('input[name="dictaminador_id"]').value = '0';
                                    document.querySelector('input[name="user_id"]').value = '0';
                                    document.querySelector('input[name="email"]').value = '';
                                    document.querySelector('input[name="user_type"]').value = '';


                            } catch (error) {
                                console.error('Error fetching dictaminador data:', error);
                            }
                        }
                   
                } catch (error) {
                    console.error('Error fetching dictaminadores:', error);
                    
                }
            }
        });

        function minWithSum(value1, value2) {
            const sum = value1 + value2;
            return Math.min(sum, 200);


        }


    </script>

</body>

</html>