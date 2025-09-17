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

</head>

<body class="bg-gray-50 text-black/50">

    <div class="relative min-h-screen flex flex-col items-center justify-center">
        @if (Route::has('login'))
            @if (Auth::check())
                <x-nav-menu :user="Auth::user()">
                    <!-- Funcionalidad en caso de nuevos formularios
                    <div>
                        <ul style="list-style: none;"">
                            <li class="nav-item">
                                <a class="nav-link active enlaceSN" style="width: 300px;" href="{{route('dynamic_forms')}}" title="Ingresar nuevo formulario"><i class="fa-solid fa-folder-plus"></i>&nbspIngresar nuevo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active enlaceSN" style="width: 300px;" href="{{route('edit_delete_form')}}"title="Editar ó eliminar formulario" ><i class="fa-solid fa-user-pen"></i>&nbspEditar/Eliminar</a>
                            </li>
                            </ul>
                        </div> 
                -->  
                </x-nav-menu>
            @endif

            </div>
            <x-general-header />
@php
    $userType = Auth::user()->user_type;
@endphp
        @if ($userType == 'dictaminador' || $userType == '')

                        <div class="container mt-4">
                            <!-- Selector para elegir el formulario -->
                            <label for="formSelect">Seleccionar Formulario:</label>
                            <select id="formSelect" class="form-select">
                                <option value=""></option>
                                <option value="resumen_comision">Resumen general de las comisiones</option>
                                <!--<option value="form5">Resumen de la PEDPD(solo firmas)</option>-->
                            </select>
                        </div>
        @endif
        @endif
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
                    case 'form4':
                        formData['user_id'] = form.querySelector('input[name="user_id"]').value;
                        formData['email'] = form.querySelector('input[name="email"]').value;
                        formData['comision1Total'] = form.querySelector('label[id="comision1Total"]').textContent;
                        formData['comision2Total'] = form.querySelector('label[id="comision2Total"]').textContent;
                        formData['comision3Total'] = form.querySelector('label[id="comision3Total"]').textContent;
                        formData['totalComisionRepetido'] = form.querySelector('label[id="totalComisionRepetido"]').textContent;
                        //minimaCalidad
                        formData['minimaCalidad'] = form.querySelector('input[name="minimaCalidad"]').textContent;
                        //minimaTotal
                        formData['minimaTotal'] = form.querySelector('input[name="minimaTotal"]').textContent;
                        break;

                    case 'form5':
                        formData['user_id'] = form.querySelector('input[name="user_id"]').value;
                        formData['email'] = form.querySelector('input[name="email"]').value;
                        formData['comision1Total'] = form.querySelector('label[id="comision1Total"]').textContent;
                        formData['comision2Total'] = form.querySelector('label[id="comision2Total"]').textContent;
                        formData['comision3Total'] = form.querySelector('label[id="comision3Total"]').textContent;
                        formData['totalComisionRepetido'] = form.querySelector('label[id="totalComisionRepetido"]').textContent;
                        //minimaCalidad
                        formData['minimaCalidad'] = form.querySelector('input[name="minimaCalidad"]').textContent;
                        //minimaTotal
                        formData['minimaTotal'] = form.querySelector('input[name="minimaTotal"]').textContent;
                        
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
        document.getElementById('formSelect').addEventListener('change', (event) => {
            const selectedForm = event.target.value;
            const formContainer = document.getElementById('formContainer');

            if (selectedForm) {
                window.location.href = `/formato-evaluacion/${selectedForm}`;
                axios.get(`/formato-evaluacion/get-form-content/${selectedForm}`)
                    .then(response => {
                        formContainer.innerHTML = response.data;
                    })
                    .catch(error => {
                        console.error('Error fetching form content:', error);
                        formContainer.innerHTML = '<p style="margin-left: 120px;">Cargando formulario.....</p>';
                    });
            } else {

                formContainer.innerHTML = '';
            }
        });
</script>

        

    </script>

</body>

</html>