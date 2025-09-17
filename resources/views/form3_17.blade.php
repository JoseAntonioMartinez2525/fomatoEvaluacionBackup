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
   <style>
    #piedepagina { display: none; }

    @media print {
        #piedepagina{
            display: block !important;
        }
        body {
            margin-left: 450px;
            margin-top: -10px;
            padding: 0;
            font-size: .9rem;

        }

       footer {
    position: absolute; /* Usar absolute en lugar de fixed */
    font-size: .8rem;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
    background-color: white;
    z-index: 10;
    padding: 5px 0;
    border-top: 1px solid #ccc;
}

    footer::after {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            
            background: white;
            padding: 5px;
            z-index: 10;
        }


    .prevent-overlap {
        page-break-before: always;
    }

    #convocatoria {
        margin: 0;
        font-size: .8rem;
    }

    #piedepagina {
        margin: 0;
         page-break-inside: avoid; /* Evitar saltos dentro del pie de página */
    }

    div {
        page-break-after: avoid;
        page-break-before: avoid;
    }
    

    @page {
        size: landscape;
        margin: 20mm; /* Ajusta según sea necesario */
        
    }


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
@endphp

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

    <div class="container mt-4" id="seleccionDocente">
        @if($userType !== 'docente')
            <!-- Select para dictaminador seleccionando docentes -->
            <label for="docenteSelect">Seleccionar Docente:</label>
            <select id="docenteSelect" class="form-select"> <!--name="docentes[]" multiple-->
                <option value="">Seleccionar un docente</option>
                <!-- Aquí se llenarán los docentes con JavaScript -->
            </select>
        @endif
    </div>

    <main class="container">
        <!-- Form for Part 3_1 -->
        <form id="form3_17" method="POST" onsubmit="event.preventDefault(); submitForm('/store-form317', 'form3_17');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
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
                            <h3 style="width: 350px;" id="cuerpos_colegiados">Cuerpos Colegiados</h3>
                        </th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th scope="col" colspan=6>Actividad</th>
                        <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th id="seccion3_17" class="acreditacion" colspan=3> 3.17 Proyectos académicos de
                            extensión y
                            difusión</th>
                        <th class="acreditacion" colspan="1">Puntaje</th>
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
                        <td id="form3_17_a"colspan="2">Inicio de proyectos de extensión y difusión con financiamiento externo</td>
                        <td id="puntajeDifusionExt"><b>15</b></td>
                        <td id="cantDifusionExt"></td>
                        <td></td>
                        <td id="subtotalDifusionExt"></td>
                        <td>
                        @if ($userType == 'dictaminador')
                            <input type="number" step="0.01" id="comisionDifusionExt" value="{{ oldValueOrDefault('comisionDifusionExt') }}" oninput="onActv3Comision3_17()">
                        @else
                            <span id="comisionDifusionExt"></span>
                        @endif
                        </td>
                        <td>
                        @if ($userType == 'dictaminador')
                            <input class="table-header" type="text" id="obsDifusionExt">
                        @else
                            <span id="obsDifusionExt"></span>
                        @endif
                         </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td colspan="2">Inicio de proyectos de extensión y difusión internos, aprobados por CAAC
                        </td>
                        <td id="puntajeDifusionInt"><b>10</b></td>
                        <td id="cantDifusionInt"></td>
                        <td></td>
                        <td id="subtotalDifusionInt"></td>
                        <td>
                        @if ($userType == 'dictaminador')    
                            <input type="number" step="0.01" id="comisionDifusionInt" value="{{ oldValueOrDefault('comisionDifusionInt') }}" oninput="onActv3Comision3_17()">
                        @else
                             <span id="comisionDifusionInt"></span>
                        @endif
                        </td>
                        <td>
                        @if ($userType == 'dictaminador')      
                            <input class="table-header" type="text" id="obsDifusionInt">
                        @else
                            <span id="obsDifusionInt"></span>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td colspan="2">Reporte cumplido del periodo anual de proyecto de extensión y difusión con
                            financiamiento
                            externo
                        </td>
                        <td id="puntajeRepDifusionExt"><b>35</b></td>
                        <td id="cantRepDifusionExt" ></td>
                        <td></td>
                        <td id="subtotalRepDifusionExt"></td>
                        <td>
                        @if ($userType == 'dictaminador')  
                            <input type="number" step="0.01" id="comisionRepDifusionExt" value="{{ oldValueOrDefault('comisionRepDifusionExt') }}" oninput="onActv3Comision3_17()">
                        @else
                            <span id="comisionRepDifusionExt"></span>
                        @endif
                        </td>
                        <td>
                        @if ($userType == 'dictaminador')  
                            <input class="table-header" type="text" id="obsRepDifusionExt">
                        @else
                            <span id="obsRepDifusionExt"></span>
                        @endif    
                        </td>
                    </tr>
                    <tr>
                        <td>d)</td>
                        <td colspan="2">Reporte cumplido del periodo anual de proyecto de extensión y difusión
                            internos, aprobados por
                            CAAC</td>
                        <td id="puntajeRepDifusionInt"><b>20</b></td>
                        <td id="cantRepDifusionInt">
                        </td>
                        <td></td>
                        <td id="subtotalRepDifusionInt"></td>
                        <td>
                        @if ($userType == 'dictaminador')      
                            <input type="number" step="0.01" id="comisionRepDifusionInt" value="{{ oldValueOrDefault('comisionRepDifusionInt') }}" oninput="onActv3Comision3_17()">
                        @else
                            <span id="comisionRepDifusionInt"></span>
                        @endif
                        </td>
                        <td>
                        @if ($userType == 'dictaminador')      
                            <input class="table-header" type="text" id="obsRepDifusionInt">
                        @else
                            <span id="obsRepDifusionInt"></span>
                        @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.17-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>
                        <th class="descripcion"><b>CAAC, DDCEU</b></th>
                        @if($userType == 'dictaminador')
                        <th><button id="btn3_17" type="submit" class="btn custom-btn printButtonClass">Enviar</button></th>
                        @endif
                    </tr>
                </thead>
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
        <x-form-renderer :forms="[['view' => 'form3_17', 'startPage' => 25, 'endPage' => 25]]" />
    </div>
</footer>
    </center>
    <script>
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
    document.addEventListener('DOMContentLoaded', async () => {
        const userType = @json($userType);  // Inject user type from backend to JS
        const user_identity = @json($user_identity);
        const docenteSelect = document.getElementById('docenteSelect');

        if (docenteSelect) {
            // Cuando el usuario es dictaminador
            if (userType === 'dictaminador') {
                try {
                    const response = await fetch('/get-docentes');
                    const docentes = await response.json();

                    docentes.forEach(docente => {
                        const option = document.createElement('option');
                        option.value = docente.email;
                        option.textContent = docente.email;
                        docenteSelect.appendChild(option);
                    });

                    docenteSelect.addEventListener('change', async (event) => {
                        const email = event.target.value;

                        if (email) {
                            axios.get('/get-docente-data', { params: { email } })
                                .then(response => {
                                    const data = response.data;

                                    // Populate fields with fetched data
                                    document.getElementById('score3_17').textContent = data.form3_17.score3_17 || '0';

                                    // Cantidades
                                    document.getElementById('cantDifusionExt').textContent = data.form3_17.cantDifusionExt || '0';
                                    document.getElementById('cantDifusionInt').textContent = data.form3_17.cantDifusionInt || '0';
                                    document.getElementById('cantRepDifusionExt').textContent = data.form3_17.cantRepDifusionExt || '0';
                                    document.getElementById('cantRepDifusionInt').textContent = data.form3_17.cantRepDifusionInt || '0';


                                    // Subtotales
                                    document.getElementById('subtotalDifusionExt').textContent = data.form3_17.subtotalDifusionExt || '0';
                                    document.getElementById('subtotalDifusionInt').textContent = data.form3_17.subtotalDifusionInt || '0';
                                    document.getElementById('subtotalRepDifusionExt').textContent = data.form3_17.subtotalRepDifusionExt || '0';
                                    document.getElementById('subtotalRepDifusionInt').textContent = data.form3_17.subtotalRepDifusionInt || '0';

                                    //  hidden inputs
                                    document.querySelector('input[name="user_id"]').value = data.form3_17.user_id || '';
                                    document.querySelector('input[name="email"]').value = data.form3_17.email || '';
                                    document.querySelector('input[name="user_type"]').value = data.form3_17.user_type || '';
                                    // Actualizar convocatoria
                                    const convocatoriaElement = document.getElementById('convocatoria');
                                    if (convocatoriaElement) {
                                        if (data.form1) {
                                            convocatoriaElement.textContent = data.form1.convocatoria || '';
                                        } else {
                                            console.error('form1 no está definido en la respuesta.');
                                        }
                                    } else {
                                        console.error('Elemento con ID "convocatoria" no encontrado.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching docente data:', error);
                                });
                            //await asignarDocentes(user_identity, email);
                        }
                    });
                } catch (error) {
                    console.error('Error fetching docentes:', error);
                    alert('No se pudo cargar la lista de docentes.');
                }
            }
            // Cuando el userType está vacío
            else if (userType === '') {

                try {
                    const response = await fetch('/get-docentes');

                    const docentes = await response.json();

                    docentes.forEach(docente => {
                        const option = document.createElement('option');
                        option.value = docente.email;
                        option.textContent = docente.email;
                        docenteSelect.appendChild(option);
                    });

                    docenteSelect.addEventListener('change', async (event) => {
                        const email = event.target.value;

                        if (email) {
                            axios.get('/get-docente-data', { params: { email } })
                                .then(response => {
                                    const data = response.data;

                                    // Actualizar convocatoria

                                    // Verifica si la respuesta contiene los datos esperados
                                    if (data.docente) {
                                        const convocatoriaElement = document.getElementById('convocatoria');

                                        // Mostrar la convocatoria si existe
                                        if (convocatoriaElement) {
                                            if (data.docente.convocatoria) {
                                                convocatoriaElement.textContent = data.docente.convocatoria;
                                            } else {
                                                convocatoriaElement.textContent = 'Convocatoria no disponible';
                                            }
                                        }
                                    }
                                });
                            // Lógica para obtener datos de DictaminatorsResponseForm2
                            try {
                                const response = await fetch('/get-dictaminators-responses');
                                const dictaminatorResponses = await response.json();
                                // Filtrar la entrada correspondiente al email seleccionado
                                const selectedResponseForm3_17 = dictaminatorResponses.form3_17.find(res => res.email === email);
                                if (selectedResponseForm3_17) {

                                    document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_17.dictaminador_id || '0';
                                    document.querySelector('input[name="user_id"]').value = selectedResponseForm3_17.user_id || '';
                                    document.querySelector('input[name="email"]').value = selectedResponseForm3_17.email || '';
                                    document.querySelector('input[name="user_type"]').value = selectedResponseForm3_17.user_type || '';
                                    document.getElementById('score3_17').textContent = selectedResponseForm3_17.score3_17 || '0';
                                    document.getElementById('comision3_17').textContent = selectedResponseForm3_17.comision3_17 || '0';

                                    // Cantidades
                                    document.getElementById('cantDifusionExt').textContent = selectedResponseForm3_17.cantDifusionExt || '0';
                                    document.getElementById('cantDifusionInt').textContent = selectedResponseForm3_17.cantDifusionInt || '0';
                                    document.getElementById('cantRepDifusionExt').textContent = selectedResponseForm3_17.cantRepDifusionExt || '0';
                                    document.getElementById('cantRepDifusionInt').textContent = selectedResponseForm3_17.cantRepDifusionInt || '0';


                                    // Subtotales
                                    document.getElementById('subtotalDifusionExt').textContent = selectedResponseForm3_17.subtotalDifusionExt || '0';
                                    document.getElementById('subtotalDifusionInt').textContent = selectedResponseForm3_17.subtotalDifusionInt || '0';
                                    document.getElementById('subtotalRepDifusionExt').textContent = selectedResponseForm3_17.subtotalRepDifusionExt || '0';
                                    document.getElementById('subtotalRepDifusionInt').textContent = selectedResponseForm3_17.subtotalRepDifusionInt || '0';


                                    // Comisiones
                                    document.querySelector('#comisionDifusionExt').textContent = selectedResponseForm3_17.comisionDifusionExt || '0';
                                    document.querySelector('#comisionDifusionInt').textContent = selectedResponseForm3_17.comisionDifusionInt || '0';
                                    document.querySelector('#comisionRepDifusionExt').textContent = selectedResponseForm3_17.comisionRepDifusionExt || '0';
                                    document.querySelector('#comisionRepDifusionInt').textContent = selectedResponseForm3_17.comisionRepDifusionInt || '0';


                                    // Observaciones
                                    document.querySelector('#obsDifusionExt').textContent = selectedResponseForm3_17.obsDifusionExt || '';
                                    document.querySelector('#obsDifusionInt').textContent = selectedResponseForm3_17.obsDifusionInt || '';
                                    document.querySelector('#obsRepDifusionExt').textContent = selectedResponseForm3_17.obsRepDifusionExt || '';
                                    document.querySelector('#obsRepDifusionInt').textContent = selectedResponseForm3_17.obsRepDifusionInt || '';


                                } else {
                                    console.error('No form3_17 data found for the selected dictaminador.');

                                    // Reset input values if no data found
                                    document.querySelector('input[name="dictaminador_id"]').value = '0';
                                    document.querySelector('input[name="user_id"]').value = '0';
                                    document.querySelector('input[name="email"]').value = '';
                                    document.querySelector('input[name="user_type"]').value = '';

                                    document.getElementById('score3_17').textContent = '0';

                                    // Cantidades
                                    document.getElementById('cantDifusionExt').textContent = '0';
                                    document.getElementById('cantDifusionInt').textContent = '0';
                                    document.getElementById('cantRepDifusionExt').textContent = '0';
                                    document.getElementById('cantRepDifusionInt').textContent = '0';


                                    // Subtotales
                                    document.getElementById('subtotalDifusionExt').textContent = '0';
                                    document.getElementById('subtotalDifusionInt').textContent = '0';
                                    document.getElementById('subtotalRepDifusionExt').textContent = '0';
                                    document.getElementById('subtotalRepDifusionInt').textContent = '0';


                                    // Comisiones
                                    document.querySelector('#comisionDifusionExt').textContent = '0';
                                    document.querySelector('#comisionDifusionInt').textContent = '0';
                                    document.querySelector('#comisionRepDifusionExt').textContent = '0';
                                    document.querySelector('#comisionRepDifusionInt').textContent = '0';


                                    // Observaciones
                                    document.querySelector('#obsDifusionExt').textContent = '';
                                    document.querySelector('#obsDifusionInt').textContent = '';
                                    document.querySelector('#obsRepDifusionExt').textContent = '';
                                    document.querySelector('#obsRepDifusionInt').textContent = '';

                                    document.getElementById('comision3_17').textContent = '0';
                                }
                            } catch (error) {
                                console.error('Error fetching dictaminators responses:', error);
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error fetching docentes:', error);
                    alert('No se pudo cargar la lista de docentes.');
                }


            }



        }

    });

        // Function to handle form submission
        async function submitForm(url, formId) {
            const formData = {};
            const form = document.getElementById(formId);

            if (!form) {
                console.error(`Form with id "${formId}" not found.`);
                return;
            }

            formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
            formData['user_id'] = form.querySelector('input[name="user_id"]').value;
            formData['email'] = form.querySelector('input[name="email"]').value;
            formData['user_type'] = form.querySelector('input[name="user_type"]').value;

            // Cantidades
            formData['cantDifusionExt'] = form.querySelector('td[id="cantDifusionExt"]').textContent;
            formData['cantDifusionInt'] = form.querySelector('td[id="cantDifusionInt"]').textContent;
            formData['cantRepDifusionExt'] = form.querySelector('td[id="cantRepDifusionExt"]').textContent;
            formData['cantRepDifusionInt'] = form.querySelector('td[id="cantRepDifusionInt"]').textContent;


            // Subtotales
            formData['subtotalDifusionExt'] = document.getElementById('subtotalDifusionExt').textContent;
            formData['subtotalDifusionInt'] = document.getElementById('subtotalDifusionInt').textContent;
            formData['subtotalRepDifusionExt'] = document.getElementById('subtotalRepDifusionExt').textContent;
            formData['subtotalRepDifusionInt'] = document.getElementById('subtotalRepDifusionInt').textContent;


            // Comisiones
            formData['comisionDifusionExt'] = form.querySelector('input[id="comisionDifusionExt"]').value;
            formData['comisionDifusionInt'] = form.querySelector('input[id="comisionDifusionInt"]').value;
            formData['comisionRepDifusionExt'] = form.querySelector('input[id="comisionRepDifusionExt"]').value;
            formData['comisionRepDifusionInt'] = form.querySelector('input[id="comisionRepDifusionInt"]').value;

            // Observaciones
            formData['obsDifusionExt'] = form.querySelector('input[id="obsDifusionExt"]').value;
            formData['obsDifusionInt'] = form.querySelector('input[id="obsDifusionInt"]').value;
            formData['obsRepDifusionExt'] = form.querySelector('input[id="obsRepDifusionExt"]').value;
            formData['obsRepDifusionInt'] = form.querySelector('input[id="obsRepDifusionInt"]').value;

            formData['score3_17'] = document.getElementById('score3_17').textContent;
            formData['comision3_17'] = document.getElementById('comision3_17').textContent;

            // Observations

            console.log('Form data:', formData);

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

                //Mensaje al usuario
                if (responseData.success) {
                    showMessage('Formulario enviado', 'green');
                } else {
                    showMessage('Formulario no enviado', 'red');
                }
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        }
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