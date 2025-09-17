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
                    <section role="region" aria-label="Response form">
                        <form class="menu">
                            @csrf
                            <nav class="nav flex-column printButtonClass menu">
                                <nav class="nav flex-column" style="padding-top: 50px; height: 900px; background-color: #afc7ce;">
                                    <div class="nav-header" style="display: flex; align-items: center; padding-top: 50px;">
                                        <li class="nav-item">
                                            <a class="nav-link disabled enlaceSN" style="font-size: medium;" href="#">
                                                <i class="fa-solid fa-user"></i>{{ Auth::user()->email }}
                                            </a>
                                        </li>
                                        <li style="list-style: none; margin-right: 20px;">
                                            <a class="enlaceSN" href="{{ route('login') }}">
                                                <i class="fas fa-power-off" style="font-size: 24px;" name="cerrar_sesion"></i>
                                            </a>
                                        </li>
                                    </div>
                                <li class="nav-item">
                                    <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('rules') }}">Artículo 10 REGLAMENTO
                                        PEDPD</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('resumen') }}">Resumen (A ser llenado
                                        por la
                                        Comisión del PEDPD)</a>
                                </li><br>
                                <li id="jsonDataLink" class="d-none">
                                    <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('general') }}">Mostrar datos de los
                                        Usuarios</a>
                                </li>
                                <li id="reportLink" class="nav-item d-none">
                                    <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('perfil') }}">Mostrar Reporte</a>
                                </li>
                                <li class="nav-item">
                                @if(Auth::user()->user_type === 'dictaminador')
                                    <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('comision_dictaminadora') }}">Selección de Formatos</a>
                                @else
                                    <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('secretaria') }}">Selección de Formatos</a>
                                @endif
                                </li>
                            </nav>
                        </form>
                    </section>
                @endif
            @endif
    </div>
<x-general-header />  

@php
$userType = Auth::user()->user_type;
$sections;
$subtotalAdded = false;
@endphp
    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4 printButtonClass">
       @if($userType == '')
            <!--//Select para usuario con user_type vacío seleccionando dictaminadores-->
            <label for="dictaminadorSelect">Seleccionar Dictaminador:</label>
            <select id="dictaminadorSelect" class="form-select">
                <!--<option value="">Seleccionar un dictaminador</option>-->
            </select>

        @endif
    </div>
                    <main class="container">
                        <form id="form4" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); submitForm('/store-resume', 'form4');" >
                            @csrf
                            <div>
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                             <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            <input type="hidden" name="user_type" value="{{Auth()->user()->user_type}}">
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
<tr>
    <td><center><b>Total logrado en la evaluación</b></center></td>
    <td></td>
    <td><label id="totalComision" for="">{{ $totalComisionRepetido }}</label></td>
</tr>
</tr>

<tr>
    <td>1. Permanencia en las actividades de la docencia</td>
    <td>100</td>
    <td><label id="totalComision1" for="">{{ $totalComision1 }}</label></td>
</tr>

<tr>
    <td>2. Dedicación en el desempeño docente</td>
    <td>200</td>
    <td><label id="totalComision2" for="">{{ $totalComision2 }}</label></td>
</tr>
<tr>
    <td>3. Calidad en la docencia</td>
    <td>700</td>
    <td><label id="totalComision3" for="">{{ $total }}</label></td>
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
@if(Auth::user()->user_type === 'dictaminador')  
<button type="submit" class="btn custom-btn buttonSignature">Enviar</button>
@endif
</center>
</div>
                        </form>

                        <br>
                        <form id="form5" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); submitForm('/store-evaluator-signature', 'form5');">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="email" id="email" value="{{ auth()->user()->email }}">
                            <input type="hidden" name="user_type" id="user_type" value="{{ auth()->user()->user_type }}">


                            <table>
                            <thead>
                                <tr>
                                    <th>
                                    <!--*Implementacion en caso que el usuario sea vacio-->
                                        @if($userType === '')
                                        <span class="personaEvaluadora1" type="text" id="personaEvaluadora1"></span>
                                        @else
                                        <input class="personaEvaluadora1" type="text" id="personaEvaluadora1">
                                        @endif
                                    </th>
                                    <th>
                                        <input type="file" class="form-control" id="firma1" name="firma1" accept="image/*">
                                    </th>
                                    <th>
                                        <!-- Aquí se mostrará la firma 1 si ya ha sido subida -->
                                    @if(isset($signature_path_1))  
                                            <img src="{{ $signature_path_1 }}" alt="Firma 1" class="imgFirma" data-firma="firma1" style="display:block;">
                                        @else
                                            <img src="" alt="Firma 1" class="imgFirma" data-firma="firma1" style="display:none;">
                                        @endif  
                                    </th>                                    
                                </tr>
                                <tr>
                                    <td class="p-2">Nombre de la persona evaluadora</td>
                                    <td class="p-2"><span id="firmaTexto">Firma</span> 
                                <small class="text-muted">Tamaño máximo permitido: 2MB</small></td>


                                </tr>
                                <tr>
                                    <th><input class="personaEvaluadora2" type="text" id="personaEvaluadora2"></th>
                                    <th>
                                        <input type="file" class="form-control" id="firma2" name="firma2" accept="image/*">
                                    </th>
                                    <th>
                                        <!-- Aquí se mostrará la firma 2 si ya ha sido subida -->
                                    @if(isset($signature_path_2))  
                                            <img src="{{ $signature_path_2 }}" alt="Firma 2" class="imgFirma" data-firma="firma2" style="display:block;">
                                        @else
                                            <img src="" alt="Firma 2" class="imgFirma" data-firma="firma2" style="display:none;">
                                        @endif

                                    </th>                                    
                                </tr>
                                <tr>
                                    <td class="p-2">Nombre de la persona evaluadora</td>
                                    <td class="p-2"><span id="firmaTexto2">Firma</span> 
                                <small class="text-muted">Tamaño máximo permitido: 2MB</small></td>

                                </tr>
                                <tr>
                                    <th><input class="personaEvaluadora3" type="text" id="personaEvaluadora3"></th>
                                    <th>
                                        <input type="file" class="form-control" id="firma3" name="firma3" accept="image/*">
                                    </th>
                                    <th>
                                        <!-- Aquí se mostrará la firma 3 si ya ha sido subida -->
                                    @if(isset($signature_path_3))  
                                        <img src="{{ $signature_path_3 }}" alt="Firma 3" class="imgFirma" data-firma="firma3" style="display:block;">
                                    @else
                                        <img src="" alt="Firma 3" class="imgFirma" data-firma="firma3" style="display:none;">
                                    @endif
                                    </th>                                    

                                </tr>
                                <tr>
                                    <td class="p-2 mr-2">Nombre de la persona evaluadora</td>
                                    <td class="p-2"><span id="firmaTexto3">Firma</span>
                                <small class="text-muted">Tamaño máximo permitido: 2MB</small></td>
                                <!--Generrar la image<td></td>-->

                                </tr>
                                <tr>
                                    <td style="padding-left: 600px;">
                                        @if(Auth::user()->user_type === 'dictaminador')  
                                        <button type="submit" class="btn custom-btn buttonSignature2">Enviar</button>
                                        @endif
                                    </td>   
                                </tr>
                            </thead>
                             

                                                        
                            </table>
                        </form>
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
       
        function getCommonFormData(form) {
        const formData = {};

        formData['user_id'] = form.querySelector('input[name="user_id"]').value;
        formData['email'] = form.querySelector('input[name="email"]').value;
        formData['user_type'] = form.querySelector('input[name="user_type"]').value;
        console.log('user_type value: ', formData['user_type']);
        return formData;
        }

        async function submitForm(url, formId) {
           const form = document.getElementById(formId);
        if (!form) {
            console.error(`Form with id "${formId}" not found.`);
            return;
        }

        if (formId === 'form4') {
            let formData = getCommonFormData(form);
            formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
            // Obtener valores de los labels y spans
            formData['comision_actividad_1_total'] = document.getElementById('totalComision1').textContent;
            formData['comision_actividad_2_total'] = document.getElementById('totalComision2').textContent;
            formData['comision_actividad_3_total'] = document.getElementById('totalComision3').textContent;
            formData['total_puntaje'] = document.getElementById('totalComisionRepetido').textContent;
            formData['minima_total'] = document.getElementById('minimaTotal').textContent;
            formData['minima_calidad'] = document.getElementById('minimaCalidad').textContent;

            // Log form data to check values
            console.log('Form data for form4: ', formData);
            
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
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        }else if(formId ==='form5'){
            let formData = new FormData(form);
            document.getElementById('reportLink').classList.remove('d-none');

            // Agregar los campos comunes
            let commonData = getCommonFormData(form);
            for (let key in commonData) {
                formData.append(key, commonData[key]);
            }


                // evaluator names
            formData.set('evaluator_name_1', form.querySelector('#personaEvaluadora1').value);
            formData.set('evaluator_name_2', form.querySelector('#personaEvaluadora2').value);
            formData.set('evaluator_name_3', form.querySelector('#personaEvaluadora3').value);

            // Add files to formData
            ['firma1', 'firma2', 'firma3'].forEach((firma) => {
                let fileInput = form.querySelector(`#${firma}`);
                if (fileInput.files.length > 0) {
                    formData.append(firma, fileInput.files[0]);
                }
            });
            
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


        // Mostrar todas las firmas
        if (data.signature_urls) { // Verifica que existan las URLs de las firmas
            const firmas = ['firma1', 'firma2', 'firma3']; // Define tus firmas

            firmas.forEach(firma => {
                const img = document.querySelector(`img[data-firma="${firma}"]`); // Selecciona la imagen correspondiente
                if (data.signature_urls[firma]) {
                    img.src = data.signature_urls[firma]; // Establece la URL de la firma
                    img.style.display = 'block'; // Muestra la imagen
                    img.style.maxWidth = '200px'; // Ajusta a 100px
                    img.style.height = '100px'; // Mantiene proporción
                } else {
                    img.style.display = 'none'; // Oculta la imagen si no hay firma
                }
            });
        }
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
          }

        }   
                window.submitForm = submitForm;  
                
    });



    document.addEventListener('DOMContentLoaded', function () {
        const userId = {{ auth()->user()->id }};
            const dictaminadorSelect = document.getElementById('dictaminadorSelect');
            // Current user type from the backend
            const userType = @json($userType);  // Get user type from backend
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
 //
     async function loadSignatures() {
        let data = await fetchData('/get-evaluator-signature', { 
            user_id: userId, 
            email: email, 
            user_type: userType 
        });

        if (data) {
            // Si las URLs de las firmas están disponibles, las mostramos
             console.log('Datos de firma recibidos:', data); 

                     // Verificar si los elementos imgFirma existen antes de asignarles src
        let imgFirma1 = document.getElementById('imgFirma1');
        let imgFirma2 = document.getElementById('imgFirma2');
        let imgFirma3 = document.getElementById('imgFirma3');

        if (data.signature_path_1 && imgFirma1) {
            imgFirma1.src = data.signature_path_1;
            imgFirma1.style.display = 'block';
        }
        if (data.signature_path_2 && imgFirma2) {
            imgFirma2.src = data.signature_path_2;
            imgFirma2.style.display = 'block';
        }
        if (data.signature_path_3 && imgFirma3) {
            imgFirma3.src = data.signature_path_3;
            imgFirma3.style.display = 'block';
        }
        } else {
            console.error('Error: Signature data not found.');
        }
    }

        //form4
        async function loadAllData() {
            let data = await fetchData('/get-form-data', { dictaminador_id: userId });

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
                await loadSignatures(); 
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

    // Only proceed if user type is not 'docente'
    if (userType !== 'docente') {
        // Add event listener to the appropriate input field or button
        const inputField = document.getElementById('user_id'); // Replace with your actual input field ID
        
        inputField.addEventListener('input', async (event) => {
            const dictaminadorId = event.target.value; // Assuming input field value is used as dictaminadorId

            if (dictaminadorId) {
                try {
                    const email = document.querySelector('input[name="email"]').value;

                    const response = await axios.get('/get-dictaminador-data', {
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

                    if (!data) {
                        console.error('No form data found for the selected dictaminador.');

                        // Reset input values if no data found
                        document.querySelector('input[name="dictaminador_id"]').value = '0';
                        document.querySelector('input[name="user_id"]').value = '0';
                        document.querySelector('input[name="email"]').value = '';
                        document.querySelector('input[name="user_type"]').value = '';
                    }
                } catch (error) {
                    console.error('Error fetching dictaminador data:', error);
                }
            }
        });
    }
});


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

</body>

</html>