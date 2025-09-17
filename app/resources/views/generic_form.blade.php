@php
    $locale = app()->getLocale() ?: 'en';
    $newLocale = str_replace('_', '-', $locale);
@endphp
<!DOCTYPE html>
<html lang="{{ $newLocale }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Añadir nuevo Formulario</title>

    <x-head-resources />
    <style>
        @media print {
            .footer-number::after {
                content: "1";
            }
        }

        .table input {
            width: 100%;
            box-sizing: border-box;
            padding: 5px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        #formName,
        #numColumns,
        #numRows {
            width: 300px;
        }

        #actividadPrincipal {
            width: 380px;
        }

        #puntajeAEvaluar,
        #puntajeComision {
            width: 150px;
            background-color: transparent;
            font-weight: bold;
        }

        #puntajeAEvaluar,
        {
        color: #ffff;

        }
    </style>

</head>

<body class="font-sans antialiased">
    <x-general-header />
    <div class="bg-gray-50 text-black/50">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            @if (Route::has('login'))
                        @if (Auth::check() && Auth::user()->user_type === '')
                            <x-nav-menu :user="Auth::user()" />
                        @endif

                        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                            <div class="flex lg:justify-center lg:col-start-2"></div>

                            <nav class="-mx-3 flex flex-1 justify-end"></nav>

                            @php
                                $user = Auth::user();
                                $userType = $user->user_type;
                                $user_identity = $user->id; 
                            @endphp

                            <!--Llenado de los campos-->
                                <div id="formContainer">
                                <!-- Título -->
                                <h2>{{ $formTitle }}</h2>

                                <!-- Nombre del formulario dinámico-->
                                <form id="dynamicForm" method="POST" action="{{ route('store.dynamic.form') }}">
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                                    @csrf
                                  @foreach($formFields as $field)
                                            <div class="form-group">
                                                <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                                <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="form-control"
                                                    id="{{ $field['name'] }}" value="{{ $field['value'] }}">
                                            </div>
                                        @endforeach
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
            @endif
        </div>
        </main>


    </div>
    </div>
    </div>

    <script>


        function handleClick(event) {
            var currentTarget = event.currentTarget;
            // Use the event data here. 
            console.log('Button clicked: ' + currentTarget.getAttribute('data-id'));
        } document.addEventListener('DOMContentLoaded', onload);
        

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

        

        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            async function submitForm(url, formId) {
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
                        formData['horasActv2'] = form.querySelector('input[name="horasActv2"]').value;
                        formData['puntajeEvaluar'] = form.querySelector('input[name="puntajeEvaluar"]').value;
                        //formData['comision1'] = form.querySelector('input[name="comision1"]').value;
                        formData['obs1'] = form.querySelector('input[name="obs1"]').value;
                        break;

                    case 'form2_2':
                        formData['user_id'] = form.querySelector('input[name="user_id"]').value;
                        formData['email'] = form.querySelector('input[name="email"]').value;
                        let hoursLabel = form.querySelector('label[id="hoursText"]');
                        //let actv2ComisionLabel = form.querySelector('td[id="actv2Comision"]');

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


                try {
                    let response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    });

                    let responseText = await response.text(); // Obtener el texto de la respuesta
                    console.log('Raw response from server:', responseText);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    let data = JSON.parse(responseText);
                    console.log('Response received from server:', data);
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                }
            }

            window.submitForm = submitForm;
            
        });


        // Función para actualizar el label en el footer con la convocatoria y periodo de evaluación
        function actualizarLabelConvocatoriaPeriodo(convocatoria, periodo) {
            const label = document.getElementById('convocatoriaPeriodoLabel');
            label.textContent = `Convocatoria: ${convocatoria}, Período: ${periodo}`;
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
                document.getElementById('jsonDataLink').classList.remove('d-none');
            }
        });
       
    </script>

</body>
</html>
