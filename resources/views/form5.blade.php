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
<link href="{{ asset('css/resume.css') }}" rel="stylesheet">
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
$userType = Auth::user()->user_type;
//$dictaminadorResponse = auth()->user()->dictaminatorResponseForm3_1;
$dictaminadorId = Auth::user()->dictaminador_id;

                @endphp 
                    
                    <br><br><br><br>               
                  <main class="container">
                        <form id="form5" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-evaluator-signature', 'form5');">
                            @csrf
<input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
<input type="hidden" name="email" id="email" value="{{ auth()->user()->email }}">
<input type="hidden" name="user_type" id="user_type" value="{{ auth()->user()->user_type }}">



                            <table>
                            <thead>
                                <tr>
                                    <th><input class="personaEvaluadora1" type="text" id="personaEvaluadora1"></th>
                                    <th>
                                        <input type="file" class="form-control" id="firma1" name="firma1" accept="image/*" style="margin-left: -400px;">
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
                                        <input type="file" class="form-control" id="firma2" name="firma2" accept="image/*" style="margin-left: -400px;">
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
                                        <input type="file" class="form-control" id="firma3" name="firma3" accept="image/*" style="margin-left: -400px;">
                                    </th>

                                </tr>
                                <tr>
                                    <td class="p-2 mr-2">Nombre de la persona evaluadora</td>
                                    <td class="p-2"><span id="firmaTexto3">Firma</span>
                                <small class="text-muted">Tamaño máximo permitido: 2MB</small></td>


                                </tr>

                            </thead>
                            <thead>
                                <tr>
                                    <td style="padding-left: 650px;"><button type="submit" class="btn custom-btn buttonSignature2">Enviar</button></td>   
                                </tr>

                            </thead>
                            </table>
                        </form>
                        <br>
        </body>
<script>
    let userType = document.querySelector('#user_type').value; // Asegúrate de que #user_type exista en tu formulario
    let userId = document.querySelector('#user_id').value; // Verifica que #user_id esté presente
    let email = document.querySelector('#email').value; 

    const dictaminadorSelect = document.getElementById('dictaminadorSelect');

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
            if (formId === 'form5') {
                formData.set('user_id', userId);
                formData.set('email', email);
                console.log('email value: ', formData['email']);
                formData.set('user_type', userType);
                console.log('user_type value: ', formData['user_type']);

                // evaluator names
                let evaluatorName1 = form.querySelector('#personaEvaluadora1').value;
                let evaluatorName2 = form.querySelector('#personaEvaluadora2').value;
                let evaluatorName3 = form.querySelector('#personaEvaluadora3').value;

                formData.set('evaluator_name_1', evaluatorName1);
                formData.set('evaluator_name_2', evaluatorName2);
                formData.set('evaluator_name_3', evaluatorName3);

                // Add files to formData
                let firma1 = form.querySelector('#firma1');
                if (firma1.files.length > 0) {
                    formData.append('firma1', firma1.files[0]);
                }

                let firma2 = form.querySelector('#firma2');
                if (firma2.files.length > 0) {
                    formData.append('firma2', firma2.files[0]);
                }

                let firma3 = form.querySelector('#firma3');
                if (firma3.files.length > 0) {
                    formData.append('firma3', firma3.files[0]);
                }
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

                if (data.signature_url) {
                    const img = document.createElement('img');
                    img.src = data.signature_url;
                    img.alt = 'Signature';
                    img.style.maxWidth = '400px';
                    img.style.maxHeight = '400px';
                    img.style.marginLeft = '1000px';
                    img.style.marginTop = '-150px';
                    document.body.appendChild(img);
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

    });


    function min700(...values) {
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
        try {
            const response = await fetch('/get-dictaminadores');
            const dictaminadores = await response.json();

            // Get the dictaminador ID from the hidden input field
            const dictaminadorId = document.querySelector('input[name="dictaminador_id"]').value;

            if (dictaminadorId) {
                try {
                    const response = await axios.get('/get-dictaminador-data', {
                        params: { email: document.querySelector('input[name="email"]').value, dictaminador_id: dictaminadorId }
                    });
                    const data = response.data;

                    console.log('Fetched dictaminador data:', data);

                    // Check if the elements exist before setting their textContent
                    const comision1Element = document.getElementById('comision1');
                    if (comision1Element) {
                        comision1Element.textContent = data.form2.comision1 || '0';
                    }

                    const actv2ComisionElement = document.getElementById('actv2Comision');
                    if (actv2ComisionElement) {
                        actv2ComisionElement.textContent = data.form2_2.actv2Comision || '0';
                    }

                    const actv3ComisionElement = document.getElementById('actv3Comision');
                    if (actv3ComisionElement) {
                        actv3ComisionElement.textContent = data.form3_1.actv3Comision || '0';
                    }

                    // Populate fields with fetched data
                    for (let i = 2; i <= 19; i++) {
                        const elementId = 'comision3_' + i;
                        const formId = 'form3_' + i;

                        const scoreValue = data[formId]?.['comision3_' + i] || 0;

                        const scoreElement = document.getElementById(elementId);
                        if (scoreElement) {
                            scoreElement.textContent = scoreValue;
                        }
                    }

                    console.log('No form data found for the selected dictaminador.');

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
    });


    function minWithSum(value1, value2) {
        const sum = value1 + value2;
        return Math.min(sum, 200);


    }

</script>
</html>