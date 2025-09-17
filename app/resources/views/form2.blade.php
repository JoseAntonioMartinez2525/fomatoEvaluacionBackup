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
    .datosPrimarios{
        margin-left: 50px;
    }

    @media print{
    .datosPrimarios{
        margin-left: 100px;
        font-size: .8rem;
    }
}

    .datosConvocatoria{
        margin-left:80px;
    }
    @media print{
        .datosConvocatoria{
            font-size: .8rem;
            margin-left: 100px;
        }


        page-break-after: auto; /* La última página no necesita salto extra */  
    }

    body.dark-mode #convocatoria2, body.dark-mode #periodo2, body.dark-mode #nombre2, 
    body.dark-mode #area2, body.dark-mode #departamento2 {
    background-color:transparent;
    color: #ffffff;
    font-weight: bold;
}

body.dark-mode #obs1, body.dark-mode #comision1 {
    color:black;
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

<div class="mostrar">
    <main class="container">
        <!-- Form for Part 2 -->
        <form id="form2" method="POST">
            @csrf
            <div><br>
            <div class="datosConvocatoria">
                <div class="row">
                    <label for="convocatoria">Convocatoria:</label>
                    <div class="valor"><span class="input-header" id="convocatoria2"></span></div>
                </div>
                <div class="row">
                    <label for="periodo">Periodo de evaluación:</label>
                    <div class="valor"><span id="periodo2" class="input-header"></span></div>
                </div>
                <div class="row">
                    <label for="nombre">Nombre del personal académico:</label>
                    <div class="valor"><span id="nombre2" class="input-header"></span></div>
                </div>
                <div class="row">
                    <label for="area">Área de Conocimiento:</label>
                    <div class="valor"><span id="area2" class="input-header"></span></div>
                </div>
                <div class="row">
                    <label for="departamento">Departamento Académico:</label>
                    <div class="valor"><span id="departamento2" class="input-header"></span></div>
                </div>
            </div><br>   
            
            
            <center class="printCenter"><h5>Instrucciones</h5></center>
            
            <div class="container flex">
                <p class="instrucciones">1 La persona a ser evaluada deberá completar la información en
                    cantidades u horas en los campos
                    marcados en <u>color gris</u>. <br>
                    2 La Comisión Dictaminadora deberá llenar los campos marcados en color azul cielo (puntajes totales o
                    subtotales, según sea el caso). <br>
                    3 No se deberán modificar fórmulas, ni agregar o quitar renglones. <br>
                    4 Este formato deberá presentarse en forma independiente de la documentación que acrediten las
                    actividades realizadas. <b>Para la evaluación no es necesario entregar las obras completas-libros,
                    manuales, publicaciones,etc.,</b> sino entregar el documento probatorio que se indique en la Guía de
                    definiciones. <br>
                    5 La Comisión Dictaminadora no tomará en cuenta documentación que no esté contemplada dentro del
                    formato de evaluación, asimismo no se aceptará documentación presentada de forma extemporánea.
            </div>
            </div>
            <div class="datosPrimarios">
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4" for="">100</label>
                </h4>
            </div>
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <input type="hidden" id="puntajeEvaluarInput" name="puntajeEvaluar" value="0">
            <table class="table table-sm datosPrimarios">
                <thead>
                    <tr>
                        <th scope="col">Actividad</th>
                        <th class="table-ajust" scope="col">Años</th>
                        <th class="table-ajust" scope="col">Puntaje a evaluar</th>
                        <th class="table-ajust" scope="col">Puntaje de la Comisión Dictaminadora</th>
                        <th class="table-ajust" scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="margin-right: auto;"><b>1. Permanencia en las actividades de la docencia</b></td>
                        <td class="horasActv2">
                            <span id="horasActv2"></span>
                        </td>
                        <td class="puntajeEvaluar">
                            <span id="puntajeEvaluarText">0</span>
                        </td>
                        <td class="table-header comision">
                            <div class="filled">
                        @if($userType == 'dictaminador')
                            <!-- Mostrar input si es 'dictaminador' -->
                            <input type="number" step="0.01" id="comision1" name="comision1" class="table-header comision" step="any"
                            value="{{ oldValueOrDefault('comision1') }}">
                        @else
                            <!-- Mostrar span si es otro tipo de usuario -->
                            <span id="comision1" class="table-header comision"></span>
                        @endif
                             </div>
                        </td>
                        <td>
                            <div class="filled">
                        @if($userType == 'dictaminador')
                            <!-- Mostrar input si es 'dictaminador' -->
                            <input id="obs1" name="obs1" class="table-header" type="text">
                        @else
                            <!-- Mostrar span si es otro tipo de usuario -->
                            <span id="obs1" class="table-header"></span>
                        @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="datosPrimarios">
                <thead>
                    <tr>
                        <th style="font-weight: normal; text-size: 20px;" scope="col">Acreditación: </th>
                        <th style="width:60px;padding-left: 100px;">SG</th>
                        <th style="font-weight: normal; padding-left: 100px;">6.25 puntos por cada año de experiencia
                            docente cumplido. A partir de los 16 años de experiencia docente se otorgarán los 100 puntos
                        </th>
                    </tr>
                </thead>
            </table>
            @if($userType != '')
                <button type="submit" class="btn custom-btn printButtonClass" id="form2_1Button">Enviar</button>
            @else
                <span></span>
            @endif
        </form>
    </main>

    </div>
    <center>
    <footer>
        <center>
            <div id="convocatoria">
                <!-- Mostrar convocatoria -->
                @if(isset($convocatoria))
                    <div style="margin-right: -700px;">
                        <h1>Convocatoria: {{ $convocatoria->convocatoria ?? 'No disponible' }}</h1>
                    </div>
                @endif
            </div>
        </center>

        <div id="piedepagina" style="margin-left: 500px; margin-top: 10px;">

<x-form-renderer :forms="[['view' => 'form2', 'startPage' => 1, 'endPage' => 1]]" />
        </div>
    </footer>
</center>

    <script>
    document.addEventListener('DOMContentLoaded', async () => {
    const userType = @json($userType);  // Inject user type from backend to JS
    const user_identity = @json($user_identity); 
    const docenteSelect = document.getElementById('docenteSelect');

        if (docenteSelect) {
            // Cuando el usuario es dictaminador
            if (userType === 'dictaminador') {
                try {
                    const response = await fetch('/formato-evaluacion/get-docentes');
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
                            axios.get('/formato-evaluacion/get-docente-data', { params: { email } })
                                .then(response => {
                                    const data = response.data;
                                    document.getElementById('horasActv2').textContent = data.form2.horasActv2 || '0';
                                    document.getElementById('puntajeEvaluarText').textContent = data.form2.puntajeEvaluar || '0';
                                    document.querySelector('input[name="user_id"]').value = data.form2.user_id || '';
                                    document.querySelector('input[name="email"]').value = data.form2.email || '';
                                    document.querySelector('input[name="user_type"]').value = data.form2.user_type || '';

                                    // Actualizar convocatoria
                                    const convocatoriaElement = document.getElementById('convocatoria');
                                    const convocatoriaHeader = document.getElementById('convocatoria2');
                                    const periodo = document.getElementById('periodo2');
                                    const nombre = document.getElementById('nombre2');
                                    const area = document.getElementById('area2');
                                    const departamento = document.getElementById('departamento2');

                                    if (convocatoriaElement) {
                                        if (data.form1) {
                                            convocatoriaElement.textContent = data.form1.convocatoria || '';
                                            convocatoriaHeader.textContent = data.form1.convocatoria || '';
                                            periodo.textContent = data.form1.periodo || '';
                                            nombre.textContent = data.form1.nombre || '';
                                            area.textContent = data.form1.area || '';
                                            departamento.textContent = data.form1.departamento || '';
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
                    const response = await fetch('/formato-evaluacion/get-docentes');
                    
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
                            axios.get('/formato-evaluacion/get-docente-data', { params: { email } })
                                .then(response => {
                                    const data = response.data;

                                    // Actualizar convocatoria

                                                    // Verifica si la respuesta contiene los datos esperados
                                    if (data.docente) {
                                        const convocatoriaElement = document.getElementById('convocatoria');
                                        const convocatoriaHeader = document.getElementById('convocatoria2');
                                        const periodo = document.getElementById('periodo2');
                                        const nombre = document.getElementById('nombre2');
                                        const area = document.getElementById('area2');
                                        const departamento = document.getElementById('departamento2');
                                        // Mostrar la convocatoria si existe
                                        if (convocatoriaElement) {
                                            if (data.docente.convocatoria) {
                                                convocatoriaElement.textContent = data.docente.convocatoria;
                                                convocatoriaHeader.textContent = data.docente.convocatoria || '';
                                                periodo.textContent = data.docente.periodo || '';
                                                nombre.textContent = data.docente.nombre || '';
                                                area.textContent = data.docente.area || '';
                                                departamento.textContent = data.docente.departamento || '';
                                            } else {
                                                convocatoriaElement.textContent = 'Convocatoria no disponible';
                                            }
                                        }
                                    }
                                });
                            // Lógica para obtener datos de DictaminatorsResponseForm2
                            try {
                                const response = await fetch('/formato-evaluacion/get-dictaminators-responses');
                                const dictaminatorResponses = await response.json();
                                // Filtrar la entrada correspondiente al email seleccionado
                                const selectedResponseForm2 = dictaminatorResponses.form2.find(res => res.email === email);
                                if (selectedResponseForm2) {
                                    // Si se encuentra la respuesta correspondiente, asigna sus valores a los campos
                                    document.getElementById('horasActv2').textContent = selectedResponseForm2.horasActv2 || '0';
                                    document.getElementById('puntajeEvaluarText').textContent = selectedResponseForm2.puntajeEvaluar || '0';
                                    document.querySelector('input[name="user_id"]').value = selectedResponseForm2.user_id || '';
                                    document.querySelector('input[name="email"]').value = selectedResponseForm2.email || '';
                                    document.querySelector('input[name="user_type"]').value = selectedResponseForm2.user_type || '';
                                    document.querySelector('span[id="comision1"]').textContent = selectedResponseForm2.comision1 || '';
                                    document.querySelector('span[id="obs1"]').textContent = selectedResponseForm2.obs1 || '';
                                } else {
                                    // Si no se encuentra ningún dato, puedes limpiar los campos o mostrar un mensaje
                                    console.log('No se encontraron respuestas para este email.');
                                    document.getElementById('horasActv2').textContent = '0';
                                    document.getElementById('puntajeEvaluarText').textContent = '0';
                                    document.querySelector('span[id="comision1"]').textContent = '';
                                    document.querySelector('span[id="obs1"]').textContent = '';
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
            const user_identity = @json($user_identity); 
            let formData = {};
            let form = document.getElementById(formId);

            if (!form) {
                console.error(`Form with id "${formId}" not found.`);
                return;
            }

            formData['dictaminador_id'] = form.querySelector('input[name="dictaminador_id"]').value;
            formData['user_id'] = form.querySelector('input[name="user_id"]').value;
            formData['email'] = form.querySelector('input[name="email"]').value;
            formData['horasActv2'] = document.getElementById('horasActv2').textContent;
            formData['puntajeEvaluar'] = document.getElementById('puntajeEvaluarText').textContent;
            formData['comision1'] = form.querySelector('input[name="comision1"]').value;
            formData['obs1'] = form.querySelector('input[name="obs1"]').value;
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

                const responseData = await response.json();
            console.log('Response received from server:', responseData);

                if (responseData.success) {
                    showMessage('Formulario enviado', 'green');
                } else {
                    showMessage('Formulario no enviado', 'red');
                }

                let responseData2 = JSON.parse(text);
                console.log('Response received from server:', responseData2);
                 await agregarDocentes(user_identity, formData['email']);
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form2 = document.getElementById('form2');
            if (form2) {
                form2.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    await submitForm('/formato-evaluacion/store-form2', 'form2'); // Llama a la función asincrónica
                    
                });
            }
        });

    document.addEventListener("DOMContentLoaded", function () {
        // Aseguramos que las páginas físicas se calculen en el momento de impresión
        window.addEventListener('beforeprint', () => {
            const totalPagesElement = document.querySelectorAll('.total-pages');
            totalPagesElement.forEach(el => {
                el.textContent = Math.ceil(document.body.offsetHeight / window.innerHeight);
            });
        });

        // Mostrar el número actual de página en cada footer
        const footers = document.querySelectorAll('#piedepagina');
        footers.forEach((footer, index) => {
            const pageNumberElement = footer.querySelector('.page-number');
            pageNumberElement.textContent = "página "+ (index + 1) + " de 33";
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

        const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
        if (toggleDarkModeButton) {
            const widthDarkButton = window.outerWidth - 230;
            toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
        }

        toggleDarkMode();
    });

        document.addEventListener('DOMContentLoaded', function () {
            // Evento antes de imprimir
            window.addEventListener('beforeprint', function () {
                const isLandscape = window.matchMedia('(orientation: landscape)').matches;
                const isScale100 = window.matchMedia('(resolution: 96dpi)').matches; // Verifica si la escala es 100%
                const form = document.getElementById('form2');
                const footer = document.querySelector('footer');
                const main = document.querySelector('main');

                if (form && footer && isLandscape && isScale100) {
                    // Tamaño del papel en píxeles para Letter (8.5 x 11 pulgadas) a 96 DPI
                    const paperHeight = isLandscape ? 816 : 1056; // 816px (horizontal), 1056px (vertical)
                    const formRect = form.getBoundingClientRect();
                    const formStyles = window.getComputedStyle(form);
                    const formMarginBottom = parseFloat(formStyles.marginBottom);

                    // Calcula la posición del footer
                    const footerTop = formRect.bottom + formMarginBottom + 20; // Incluye margen inferior
                    if (footerTop + footer.offsetHeight <= paperHeight) {
                        // Si el footer cabe en la misma página
                        footer.style.position = 'absolute';
                        footer.style.top = `${footerTop}px`;
                    } else {
                        // Si no cabe, reduce el tamaño de letra dentro del formulario y main
                        if (main) {
                            main.style.setProperty('font-size', '9px', 'important'); // Ajusta el tamaño de letra
                        }
                        if (form) {
                            form.style.setProperty('font-size', '9px', 'important'); // Ajusta el tamaño de letra
                        }
                    }
                }
            });

            // Evento después de imprimir
            window.addEventListener('afterprint', function () {
                const footer = document.querySelector('footer');
                const main = document.querySelector('main');
                const form = document.getElementById('form2');

                if (footer) {
                    // Restaura los estilos originales del footer
                    footer.style.position = '';
                    footer.style.top = '';
                }
                if (main) {
                    main.style.fontSize = ''; // Restaura el tamaño de letra original
                }
                if (form) {
                    form.style.fontSize = ''; // Restaura el tamaño de letra original
                }
            });
        });
    </script>
</body>


</html>