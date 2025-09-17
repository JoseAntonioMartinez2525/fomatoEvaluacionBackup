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
    
    body.dark-mode span, body.dark-mode #horasPosgrado, body.dark-mode #horasSemestre, 
    body.dark-mode #comisionPosgrado,  body.dark-mode #comisionLic{
    background-color:transparent;
        color: #ffffff;
    }

body.dark-mode nav.nav.flex-column {
    background: linear-gradient(90deg, rgb(14, 34, 69),  rgb(13, 31, 63)) !important;
}

body.dark-mode nav.nav.flex-column a:hover {
   color:  rgb(122, 164, 237);
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

<div class="container mt-4 printButtonClass">
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
        <!-- Form for Part 2_2 -->
        <form id="form2_2" method="POST" onsubmit="event.preventDefault(); submitForm('/store-form22', 'form2_2');">
            @csrf
            <div>
                <!-- Activity 2: Commitment in Teaching Performance -->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">200</label>
                </h4>
            </div>
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
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
                        <td><b>2. Dedicación en el Desempeño docente</b></td>
                        <td for=""></td>
                        <td id="hours" name="hours" for=""><label id="hoursText" for="">0</label></td>
                        <td id="actv2Comision" for=""></td>
                        </tr>
                        <tr>
                            <td><label for="">a) Posgrado</label>
                                <label for="">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Semestre </label>
                            </td>
                            <td class="cantidad"><span id="horasPosgrado" name="horasPosgrado" class="horasActv2"></span>
                            </td>
                            <td class="puntajeEvaluar2"><label id="dse" name="dse" class="puntajeEvaluar" type="text"></label></td>
                             @if($userType == 'dictaminador')
                                    <td class="comision actv filled">
                                        <input type="number" step="0.01" id="comisionPosgrado" name="comisionPosgrado" for="" oninput="onActv2Comision()"
                                        value="{{ oldValueOrDefault('comisionPosgrado') }}">
                                    </input>
                                    </td>
                                <td class="filled"><input id="obs2" name="obs2" class="table-header" type="text"></td>

                            @else
                                <td class="comision actv"><span id="comisionPosgrado" name="comisionPosgrado"></span></td>
                                <td><span id="obs2" name="obs2" class="table-header"></span></td>

                            @endif

                        </tr>
                        <tr>
                            <td>b) Licenciatura y TSU
                                <label for="">&nbsp &nbsp &nbsp &nbsp Horas </label>
                            </td>
                            <td class="cantidad"><span id="horasSemestre" name="horasSemestre" class="horasActv2"></span>
                            </td>
                            <td class="puntajeEvaluar2"><label id="dse2" name="dse2" class="puntajeEvaluar" type="text"></label></td>
                            @if($userType == 'dictaminador')
                                <td class="comision actv"><input type="number" step="0.01" id="comisionLic" name="comisionLic" oninput="onActv2Comision()" 
                                value="{{ oldValueOrDefault('comisionLic') }}"></input>
                                </td>
                                <td><input id="obs2_2" name="obs2_2" class="table-header" type="text"></input></td>
                            @else
                            <td class="comision actv"><span id="comisionLic" name="comisionLic"></span>
                            </td>
                            <td><span id="obs2_2" name="obs2_2" class="table-header"></span></td>
                            @endif
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
                                        @if($userType != '')
                                            <button type="submit" class="btn custom-btn printButtonClass" id="form2_2Button">Enviar</button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </form>
                </main>
<center>
    <footer>
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
    <x-form-renderer :forms="[['view' => 'form2_2', 'startPage' => 2, 'endPage' => 2]]" />

        </div>
    </footer>
</center>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            const pageNumberElement = document.getElementById('page-number');
            //const totalPages = 22; // Total de páginas

            // Suponiendo que obtienes el número de página actual de alguna forma
            let currentPage = 2;

            pageNumberElement.innerText = currentPage;
        });
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
                                        document.getElementById('hoursText').textContent = data.form2_2.hours || '0';
                                        document.getElementById('horasPosgrado').textContent = data.form2_2.horasPosgrado || '0';
                                        document.getElementById('horasSemestre').textContent = data.form2_2.horasSemestre || '0';
                                        document.getElementById('dse').textContent = data.form2_2.dse || '0';
                                        document.getElementById('dse2').textContent = data.form2_2.dse2 || '0';
                                        document.querySelector('input[name="user_id"]').value = data.form2_2.user_id || '';
                                        document.querySelector('input[name="email"]').value = data.form2_2.email || '';
                                        document.querySelector('input[name="user_type"]').value = data.form2_2.user_type || '';

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
                                    const selectedResponseForm2_2 = dictaminatorResponses.form2_2.find(res => res.email === email);
                                                              if (selectedResponseForm2_2) {
                                        document.getElementById('hoursText').textContent = selectedResponseForm2_2.hours || '0';
                                        document.getElementById('horasPosgrado').textContent = selectedResponseForm2_2.horasPosgrado || '0';
                                        document.getElementById('horasSemestre').textContent = selectedResponseForm2_2.horasSemestre || '0';
                                        document.getElementById('dse').textContent = selectedResponseForm2_2.dse || '0';
                                        document.getElementById('dse2').textContent = selectedResponseForm2_2.dse2 || '0';
                                        document.querySelector('span[id="comisionPosgrado"]').textContent = selectedResponseForm2_2.comisionPosgrado || '';
                                        document.querySelector('span[id="comisionLic"]').textContent = selectedResponseForm2_2.comisionLic || '';
                                        document.querySelector('td[id="actv2Comision"]').textContent = selectedResponseForm2_2.actv2Comision || '';
                                        document.querySelector('span[id="obs2"]').textContent = selectedResponseForm2_2.obs2 || '';
                                        document.querySelector('span[id="obs2_2"]').textContent = selectedResponseForm2_2.obs2_2 || '';
                                        document.querySelector('input[name="user_id"]').value = selectedResponseForm2_2.user_id || '';
                                        document.querySelector('input[name="email"]').value = selectedResponseForm2_2.email || '';
                                        document.querySelector('input[name="user_type"]').value = selectedResponseForm2_2.user_type || '';
                                    } else {
                                        // Limpiar campos de form2_2 si no hay datos
                                        console.log('No se encontraron respuestas para form2_2 con este email.');
                                        document.getElementById('hoursText').textContent = '0';
                                        document.getElementById('horasPosgrado').textContent = '0';
                                        document.getElementById('horasSemestre').textContent = '0';
                                        document.getElementById('dse').textContent = '0';
                                        document.getElementById('dse2').textContent = '0';
                                        document.querySelector('span[id="comisionPosgrado"]').textContent = '';
                                        document.querySelector('span[id="comisionLic"]').textContent = '';
                                        document.querySelector('td[id="actv2Comision"]').textContent = '';
                                        document.querySelector('span[id="obs2"]').textContent = '';
                                        document.querySelector('span[id="obs2_2"]').textContent = '';
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


    async function submitForm(url, formId) {
        let formData = {};
        let form = document.getElementById(formId);

        if (!form) {
            console.error(`Form with id "${formId}" not found.`);
            return;
        }

        // Gather relevant information from the form
        formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
        formData['user_id'] = form.querySelector('input[name="user_id"]').value;
        formData['email'] = form.querySelector('input[name="email"]').value;
        formData['hours'] = document.getElementById('hoursText').textContent;
        formData['horasPosgrado'] = document.getElementById('horasPosgrado').textContent;
        formData['horasSemestre'] = document.getElementById('horasSemestre').textContent;
        formData['dse'] = document.getElementById('dse').textContent;
        formData['dse2'] = document.getElementById('dse2').textContent;
        formData['comisionPosgrado'] = form.querySelector('input[name="comisionPosgrado"]').value;
        formData['comisionLic'] = form.querySelector('input[name="comisionLic"]').value;
        formData['actv2Comision'] = document.getElementById('actv2Comision').textContent;
        formData['obs2'] = form.querySelector('input[name="obs2"]').value;
        formData['obs2_2'] = form.querySelector('input[name="obs2_2"]').value;
        formData['user_type'] = form.querySelector('input[name="user_type"]').value;

        console.log('Form data:', formData);

        try {
            let response = await fetch(url, {
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

            let responseData = await response.json();
            console.log('Response received from server:', responseData);
            
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