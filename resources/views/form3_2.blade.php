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
    body.chrome @media print {
        #convocatoria {
            font-size: 1.2rem;
            color: blue;
            /* Ejemplo de estilo específico para Chrome */
        }
    }

#piedepagina { display: none; }

    @media print {
        #piedepagina{
            display: block !important;
        }
        body {
            margin-left: 200px;
            margin-top: -10px;
            padding: 0;
            font-size: .8rem;

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


        page-break-after: auto; /* La última página no necesita salto extra */

}
    body.dark-mode #r1, body.dark-mode #r2, body.dark-mode #r3, body.dark-mode #prom90_100, 
    body.dark-mode #prom80_90, body.dark-mode #prom70_80 {
        color: black;
    }

    body.dark-mode [id^="obs3_2"] {
        color: black;
    }

    [id^="btn3_"]{
    margin-left: 900px;
}

body.dark-mode [id^="btn3_"]{
        background-color: #456483;
        color: floralwhite;
}

body.dark-mode [id^="btn3_"]:hover {
    background-color: #6a5b9f;
    
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
        <form id="form3_2" method="POST" onsubmit="event.preventDefault(); submitForm('/store-form32', 'form3_2');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <div>
            <!-- Actividad 3.2 Calidad del desempeño docente evaluada por el alumnado -->
            <h4 id="puntajeMaxForm3_2">Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">50</label>
            </h4>
            </div>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Actividad</th>
                            <th class="table-ajust" scope="col"></th>
                            <th class="table-ajust" scope="col"></th>
                            <th class="table-ajust cd" scope="col">Puntaje a evaluar</th>
                            <th class="table-ajust cd" scope="col">Puntaje de la Comisión Dictaminadora</th>
                            <th class="table-ajust" scope="col">Observaciones</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td id="seccion3_2" style="height: 80px; width: 200px;">3.2 Calidad del desempeño
                                docente
                                evaluada por el alumnado
                            </td>
                            <td>Puntaje</td>
                            <td style="text-align:left;">Cantidad</td>
                            <td id="score3_2" for="">0</td>
                            <td id="comision3_2">0</td>
                        </tr>
                    </thead>
                    <thead>
                        <!--prom90-100-->
                        <tr>
                            <td class="ranges">
                                <center>Promedio 90-100</center>
                            </td>
                            <td id="ran1"><b>50</b></td>
                            <td class="elabinput" style="background-color: #c1cfd3;text-align:right;"><span id="r1" name="r1"></span></td>
                            <td id="cant1" name="cant1">0</td>
                            <td>
                            @if($userType == 'dictaminador')
                                <input id="prom90_100" type="number" step="0.01"
                                    oninput="onActv3_2Comision()" value="{{ oldValueOrDefault('prom90_100') }}">
                            @else
                            <span id="prom90_100" name="prom90_100"></span>
                            @endif
                            </td>
                            <td>
                            @if($userType == 'dictaminador')
                                <input id="obs3_2_1" name="obs3_2_1" class="table-header" type="text">
                            @else
                                <span id="obs3_2_1" name="obs3_2_1" class="table-header"></span>
                            @endif

                            </td>
                        </tr>
                        <!--prom80-90-->
                        <tr>
                            <td class="ranges">
                                <center>Promedio 80-90</center>
                            </td>
                            <td id="ran2"><b>40</b></td>
                            <td class="elabInput" style="background-color: #c1cfd3;"><span id="r2" name="r2"></span></td>
                            <td id="cant2" name="cant2">0</td>

                            <td>
                             @if($userType == 'dictaminador')   
                                <input id="prom80_90" type="number" step="0.01"
                                    oninput="onActv3_2Comision()" value="{{ oldValueOrDefault('prom80_90') }}">
                            @else
                                <span id="prom80_90" name="prom80_90"></span>
                            @endif
                            </td>
                            <td>
                            @if($userType == 'dictaminador')    
                                <input id="obs3_2_2" name="obs3_2_2" class="table-header" type="text">
                            @else
                                <span id="obs3_2_2" name="obs3_2_2" class="table-header"></span>
                            @endif
                            </td>
                        </tr>
                        <!--prom70-80-->
                        <tr>
                            <td class="ranges">
                                <center>Promedio 70-80</center>
                            </td>
                            <td id="ran3"><b>30</b></td>
                            <td class="elabInput" style="background-color: #c1cfd3;">
                                <span id="r3" name="r3"></span>
                            </td>
                            <td id="cant3">0</td>
                            <td>
                            @if($userType == 'dictaminador')  
                                <input id="prom70_80" placeholder="0" type="number" step="0.01"
                                        oninput="onActv3_2Comision()" value="{{ oldValueOrDefault('prom70_80') }}">
                            @else
                            <span id="prom70_80" name="prom70_80"></span>
                            @endif
                            </td>
                            <td>
                            @if($userType == 'dictaminador')  
                                <input id="obs3_2_3"  name="obs3_2_3" class="table-header" type="text">
                            @else
                                <span id="obs3_2_3" name="obs3_2_3" class="table-header"></span>
                            @endif
                            </td>
                        </tr>
                    </thead>
                    </table>
                    <!--Tabla informativa Acreditacion Actividad 3.2-->
                <table>
                    <thead>
                        <tr><br>
                            <th class="acreditacion" scope="col">Acreditacion: </th>

                            <th class="descripcionDDIE"><b>DDIE</b>
                            <th> 
                            @if($userType != '')     
                                <button id="btn3_2" type="submit" class="btn custom-btn printButtonClass">Enviar
                            @endif
                            </th>
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
            <x-form-renderer :forms="[['view' => 'form3_2', 'startPage' => 5, 'endPage' => 5]]" />
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
                                            const data = response.data; document.getElementById('score3_2').textContent = data.form3_2.score3_2 || '0';
                                            document.getElementById('r1').textContent = data.form3_2.r1 || '0';
                                            document.getElementById('r2').textContent = data.form3_2.r2 || '0';
                                            document.getElementById('r3').textContent = data.form3_2.r3 || '0';
                                            document.getElementById('cant1').textContent = data.form3_2.cant1 || '0';
                                            document.getElementById('cant2').textContent = data.form3_2.cant2 || '0';
                                            document.getElementById('cant3').textContent = data.form3_2.cant3 || '0';


                                            // Populate hidden inputs
                                            document.querySelector('input[name="user_id"]').value = data.form3_2.user_id || '';
                                            document.querySelector('input[name="email"]').value = data.form3_2.email || '';
                                            document.querySelector('input[name="user_type"]').value = data.form3_2.user_type || '';

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
                        const formName = 'form2';
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
                                        const selectedResponseForm3_2 = dictaminatorResponses.form3_2.find(res => res.email === email);
                                        if (selectedResponseForm3_2) {

                                            document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_2.dictaminador_id || '0';
                                            document.querySelector('input[name="user_id"]').value = selectedResponseForm3_2.user_id || '';
                                            document.querySelector('input[name="email"]').value = selectedResponseForm3_2.email || '';
                                            document.querySelector('input[name="user_type"]').value = selectedResponseForm3_2.user_type || '';

                                            document.getElementById('score3_2').textContent = selectedResponseForm3_2.score3_2 || '0';
                                            document.getElementById('r1').textContent = selectedResponseForm3_2.r1 || '0';
                                            document.getElementById('r2').textContent = selectedResponseForm3_2.r2 || '0';
                                            document.getElementById('r3').textContent = selectedResponseForm3_2.r3 || '0';
                                            document.getElementById('cant1').textContent = selectedResponseForm3_2.cant1 || '0';
                                            document.getElementById('cant2').textContent = selectedResponseForm3_2.cant2 || '0';
                                            document.getElementById('cant3').textContent = selectedResponseForm3_2.cant3 || '0';
                                            document.getElementById('comision3_2').textContent = selectedResponseForm3_2.comision3_2 || '0';
                                            document.querySelector('span[name="prom90_100"]').textContent = selectedResponseForm3_2.prom90_100 || '0';
                                            document.querySelector('span[name="prom80_90"]').textContent = selectedResponseForm3_2.prom80_90 || '0';
                                            document.querySelector('span[name="prom70_80"]').textContent = selectedResponseForm3_2.prom70_80 || '0';
                                            document.querySelector('span[name="obs3_2_1"]').textContent = selectedResponseForm3_2.obs3_2_1 || '';
                                            document.querySelector('span[name="obs3_2_2"]').textContent = selectedResponseForm3_2.obs3_2_2 || '';
                                            document.querySelector('span[name="obs3_2_3"]').textContent = selectedResponseForm3_2.obs3_2_3 || '';


                                        } else {

                                           console.error('No form3_2 data found for the selected dictaminador.');
                                            // Reset input values if no data found
                                            document.querySelector('input[name="dictaminador_id"]').value = '0';
                                            document.querySelector('input[name="user_id"]').value = '0';
                                            document.querySelector('input[name="email"]').value = '';
                                            document.querySelector('input[name="user_type"]').value = '';
                                            document.getElementById('r1').textContent = '0';
                                            document.getElementById('r2').textContent = '0';
                                            document.getElementById('r3').textContent = '0';
                                            document.getElementById('cant1').textContent = '0';
                                            document.getElementById('cant2').textContent = '0';
                                            document.getElementById('cant3').textContent = '0';
                                            document.getElementById('comision3_2').textContent = '0';
                                            document.querySelector('span[name="prom90_100"]').textContent = '0';
                                            document.querySelector('span[name="prom80_90"]').textContent = '0';
                                            document.querySelector('span[name="prom70_80"]').textContent = '0';
                                            document.querySelector('span[name="obs3_2_1"]').textContent = '';
                                            document.querySelector('span[name="obs3_2_2"]').textContent = '';
                                            document.querySelector('span[name="obs3_2_3"]').textContent = '';
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

            // Gather all related information from the form
            formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
            formData['user_id'] = form.querySelector('input[name="user_id"]').value;
            formData['email'] = form.querySelector('input[name="email"]').value;
            formData['user_type'] = form.querySelector('input[name="user_type"]').value;
            formData['r1'] = document.getElementById('r1').textContent;
            formData['prom90_100'] = document.getElementById('prom90_100').value; // Ensure input value is fetched
            formData['r2'] = document.getElementById('r2').textContent;
            formData['r3'] = document.getElementById('r3').textContent;
            formData['prom80_90'] = document.getElementById('prom80_90').value; // Ensure input value is fetched
            formData['cant1'] = document.getElementById('cant1').textContent;
            formData['cant2'] = document.getElementById('cant2').textContent;
            formData['prom70_80'] = document.getElementById('prom70_80').value; // Ensure input value is fetched
            formData['cant3'] = document.getElementById('cant3').textContent;
            formData['prom90_100'] = form.querySelector('input[id="prom90_100"]').value;
            formData['prom80_90'] = form.querySelector('input[id="prom80_90"]').value;
            formData['prom70_80'] = form.querySelector('input[id="prom70_80"]').value;
            formData['score3_2'] = document.getElementById('score3_2').textContent;
            formData['comision3_2'] = document.getElementById('comision3_2').textContent;

            // Observations
            formData['obs3_2_1'] = form.querySelector('input[name="obs3_2_1"]').value;
            formData['obs3_2_2'] = form.querySelector('input[name="obs3_2_2"]').value;
            formData['obs3_2_3'] = form.querySelector('input[name="obs3_2_3"]').value;


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