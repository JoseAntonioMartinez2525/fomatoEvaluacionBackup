@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$user = Auth::user();
$userType = $user->user_type;
$user_email = $user->email;
$user_identity = $user->id; 

$docenteConfig = [
    'searchInputId' => 'docenteSearch',
    'suggestionsBoxId' => 'docenteSuggestions',
    'hiddenEmailId' => 'selectedDocenteEmail',
    'docenteDataEndpoint' => '/formato-evaluacion/get-docente-data',
    'skipAutoFetch' => true, // Evita que el script de autocompletado global intente cargar datos.
    'docentesEndpoint' => '/formato-evaluacion/get-docentes',

];

// Si se recibe un email desde la URL, se lo pasamos a la configuración del autocompletado.
if (isset($teacherEmailFromUrl) && $teacherEmailFromUrl) {
    $docenteConfig['preselectedEmail'] = $teacherEmailFromUrl;
}
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @include('partials.docente-autocomplete', ['config' => $docenteConfig])
    <script src="{{ asset('js/resumen_comision.js') }}"></script>
</head>
<style>
    body.chrome @media print {
    .convocatoria {
        font-size: 1.2rem;
        color: blue; /* Ejemplo de estilo específico para Chrome */
    }
}
    #nivelLabel{
    padding-right: 190px;
}

 #minimaCalidad{
    padding-left: 120px;
 }

#minimaTotal{
    padding-left: 120px;
}

.evaluadores{
    background-color: rgb(232, 240, 254); 
    width: 300px;
}

    .piedepagina {
        margin: 0;
        display: none;
    }

    @media print{
            page-break-after: auto; /* La última página no necesita salto extra */

            .piedepagina{
                display: block;
        margin: 0;
         page-break-inside: avoid; /* Evitar saltos dentro del pie de página */
            }
}

@media screen {
    .print-only,
    [data-print-footer] {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        max-height: 0 !important;
        overflow: hidden !important;
    }

    #convocatoria2, #piedepagina2{
        display: none !important;
        visibility: hidden !important;
    }
}

@media print {
    .print-only,
    [data-print-footer] {
        display: table-footer-group !important;
        visibility: visible !important;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }

    #convocatoria2{
        display: table-footer-group !important;
        visibility: visible !important;
    }
}

.message-container {
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f8f9fa;
    color: #333;
    text-align: center;
    width: fit-content;
    position: relative;
    left: 50%;
    transform: translateX(-50%);
}

body.dark-mode [class^="personaEvaluadora"] {
    color: black;
    font-weight: bold;
}

body.dark-mode img.imgFirma{
    background-color: transparent;
    filter: invert(0.92) brightness(2);;
}

#resumenContainer {
    width: 100%;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    font-size: 14px;
  }

  #resumenTable {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
  }

  /* No mostrar bordes: transparentes; usamos padding y separación para lectura */
  #resumenTable td,
  #resumenTable th {
    border: 1px solid transparent;
    padding: 8px 10px;
    vertical-align: middle;
  }

  /* Encabezados */
  #resumenTable thead th {
    text-align: left;
    font-weight: 700;
    padding-bottom: 12px;
  }

  /* Primera columna (actividad) alineada a la izquierda */
  #resumenTable tbody td.activity {
    text-align: left;
    padding-left: 6px;
    white-space: normal;
  }

  /* Columnas numéricas (puntaje máximo y comisiones) alineadas al centro */
  #resumenTable tbody td.maxpoints,
  #resumenTable tbody td.comision {
    text-align: center;
    white-space: nowrap;
  }

  /* Color de fondo para celdas "puntaje otorgado" evaluadas */
  .comision.resaltado {
    background-color: #f6c667;
    border-radius: 4px;
  }

  /* Negrita para subtotales/títulos de sección */
  .negrita {
    font-weight: 700;
  }

  /* Centrados especiales */
  .centrado {
    text-align: center;
  }

  /* Espacio para firmas / pdf */
  #pdfButtonContainer {
    margin-top: 16px;
    text-align: right;
  }

  body.light-mode .btn.custom-btn {
    display: inline-block;
    background-color: #367ea8!important;
    color: white!important;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
  }

  body.dark-mode .btn.custom-btn{
        background-color: #23516b!important;
  }
  /* convocatoria resumen_comision blade */
    span#convocatoria-title {
        margin-inline-start: 10rem;
    }

    .form-select{
        width: 70%!important;
    }

</style>
<body class="bg-gray-50 text-black/50">

    <div class="relative min-h-screen flex flex-col items-center justify-center">
    @if (Route::has('login'))
                @if (Auth::check())
                    <x-nav-menu :user="Auth::user()" />
                @endif

            <x-general-header />
                <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo Obscuro</button>

            <div class="container mt-4" id="seleccionDocente">
            @if($userType !== 'docente' && empty($teacherEmailFromUrl))
            {{-- Componente de búsqueda de docente --}}
            <x-docente-search />
            @endif
            </div>
            <!-- Loader oculto por defecto -->
            <div id="loader" style="display: none; text-align: center; padding: 2rem;">
                {{-- <img src="{{ asset('loader.gif') }}" alt="Cargando..."> --}}
                <i class="fa-solid fa-spinner fa-spin-pulse" style="color: #367ea8; font-size: 3rem;"></i>
            </div>

            <main class="container" id="formContainer" style="display: none;">
                
            <form id="form4" method="POST" enctype="multipart/form-data"
            onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-resume', 'form4');">
            @csrf
            <div>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
            <input type="hidden" name="user_type" value="{{ Auth::user()->user_type }}">
            <center>
            <h2 id="resumen">Resumen</h2>
            <h4>A ser llenado por la Comisión del PEDPD</h4>
            </center>
            <table class="resumenTabla">
            <thead>
            <tr>
            <th id="actv">Actividad</th>
            <th id="pMaximo">Puntaje máximo</th>
            <th id="pComision">Puntaje otorgado Comisión PEDPD</th>
            </tr>
            </thead>
            <tbody id="data">
            <!-- Aquí se llenarán los datos del dictaminador con JavaScript -->
            </tbody>

            </table>
            <table>
            <tbody>
            <thead>
                {{-- posible boton para enviar las respúestas consolidadas de las comisiones --}}
            {{-- <tr>
                <td style="padding-left: 600px;">
                    @if(Auth::user()->user_type === 'dictaminador')
                        <button type="submit" id="submitButton" class="btn custom-btn buttonSignature2">Enviar</button>
                    @endif
                </td>
            </tr> --}}
            </thead>
            </tbody>
            </table>
                </div>

            </form>

<form id="form5" method="GET" onsubmit="event.preventDefault(); fetchSignatures('form5');">
    @csrf
    <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="email" id="email" value="{{ auth()->user()->email }}">
    <input type="hidden" name="user_type" id="user_type" value="{{ auth()->user()->user_type }}">
    <input type="hidden" name="dictaminador_id" value="{{ $user_identity ?? '' }}">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre del Evaluador</th>
                <th>Firma</th>
            </tr>
        </thead>
        <tbody id="signaturesBody">
            @if($userType === 'dictaminador')
                @php $signature = auth()->user()->dictaminadorSignature; @endphp
                @if($signature)
                    <tr>
                        <td>{{ $signature->evaluator_name }}</td>
                        <td>
                            <img src="data:{{ $signature->mime }};base64,{{ $signature->signature_image }}" 
                                 alt="Firma" style="max-width: 150px; display: block;">
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="2">No se ha registrado ninguna firma.</td>
                    </tr>
                @endif
            @elseif($userType === 'secretaria')
                {{-- Para secretaria, tbody se llenará con JS --}}

            @endif
        </tbody>
    </table>


</form>

    @endif
<br>
    
        <footer id="footerForm3_4">
        
                <div id="convocatoria">
                    <div>
                        {{-- This will be filled by JavaScript --}}
                        <span id="convocatoria-title"></span>
                    </div>
                </div>

        </footer>
   
        </main>

    </div>

    <div>

    </div>
    </div>
    </div>
    </div>

    <script>

        $(".files").change(function() {
        filename = this.files[0].name;
        console.log(filename);
        });

    window.addEventListener('beforeprint', () => {
        const printElements = document.querySelectorAll('.print-only');
        printElements.forEach(el => {
            el.style.display = 'table-footer-group';
            el.style.visibility = 'visible';
        });
    });

    window.addEventListener('afterprint', () => {
        const printElements = document.querySelectorAll('.print-only');
        printElements.forEach(el => {
            el.style.display = 'none';
            el.style.visibility = 'hidden';
        });
    });

        window.onload = function () {
            const footerHeight = document.querySelector('footer').offsetHeight;
            const elements = document.querySelectorAll('.prevent-overlap');

            elements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                // Verifica si el elemento está demasiado cerca del footer
                if (rect.bottom > viewportHeight - footerHeight) {
                    element.style.pageBreakBefore = "always";
                }
            });

        };


        function handleClick(event) {
            var currentTarget = event.currentTarget;
            // Use the event data here. 
            console.log('Button clicked: ' + currentTarget.getAttribute('data-id'));
        } document.addEventListener('DOMContentLoaded', onload);

        function actualizarResultados(sumaComision3, totalLogrado) {
                const minimaCalidad = evaluarCalidad(sumaComision3);
                const minimaTotal = evaluarTotal(totalLogrado);

                // Actualizar el DOM con los valores calculados
                document.getElementById('minimaCalidad').textContent = minimaCalidad;
                document.getElementById('minimaTotal').textContent = minimaTotal;
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

      
// ======== config.js ========
const labels = [                                        //======== posicion ========
    '1. Permanencia en las actividades de la docencia',             //0
    '1.1 Años de experiencia docente en la institución',
    '2. Dedicación en el desempeño docente',
    '2.1 Carga de trabajo docente frente a grupo',
    '3. Calidad en la docencia',
    '3.1 Participación en actividades de diseño curricular',
    '3.2 Calidad del desempeño docente evaluada por los estudiantes',
    '3.3 Publicaciones relacionadas con la docencia',
    '3.4 Distinciones académicas recibidas por el docente',
    '3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD y por CAAC',
    '3.6 Capacitación y actualización pedagógica recibida',         //10
    '3.7 Cursos de actualización disciplinaria recibidos dentro de su área de conocimiento',
    '3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de educación, continua o de formación y capacitación docente',
    '3.8.1 RSU',
    'Subtotal',                                                     //14
    'Tutorias',
    '3.9 Trabajos dirigidos para la titulación de estudiantes',
    '3.10 Tutorías a estudiantes',
    '3.11 Asesoría a estudiantes',
    'Subtotal',
    'Investigación',                                                //20
    '3.12 Publicaciones de investigación relacionadas con el contenido de los PE que imparte el docente',
    '3.13 Proyectos académicos de investigación',
    '3.14 Participación como ponente en congresos o eventos académicos del área de conocimiento o afines del docente',
    '3.15 Registro de patentes y productos de investigación tecnológica y educativa',
    '3.16 Actividades de arbitraje, revisión, corrección y edición',
    'Subtotal',
    'Cuerpos colegiados',
    '3.17 Proyectos académicos de extensión y difusión',
    '3.18 Organización de congresos o eventos institucionales del área de conocimiento del Docente',
    '3.19 Participación en cuerpos colegiados',
    'Subtotal',
    'Total logrado en la evaluación',                              //32
    '1. Permanencia en las actividades de la docencia',            //33
    '2. Dedicación en el desempeño docente',                       //34
    '3. Calidad en la docencia',                                   //35
    'Total de puntaje obtenido en la evaluación',                  //36
    'Nivel obtenido de acuerdo al artículo 10 del Reglamento',     //37
    'Mínima de Calidad',                                           //38
    'Mínima Total'                                                 //39
];

const values = [
    100, 100, 200, 200, 700, 60, 50, 100, 60, 75, 40, 40, 40, 40, null, null,
    200, 115, 95, null, null, 150, 130, 40, 60, 30, null, null, 50, 40, 40, null,
    null, 100, 200, 700, null, null, null, null
];

// ======== api.js ========
async function fetchDocentes() {
    const res = await fetch('/formato-evaluacion/get-docentes');
    if (!res.ok) throw new Error('Error al obtener docentes');
    return await res.json();
}

async function fetchDocenteData(email) {
    const res = await axios.get('/formato-evaluacion/get-docente-data', { params: { email } });
    return res.data;
}

async function fetchUserId(email) {
    const res = await fetch(`/formato-evaluacion/get-user-id?email=${email}`);
    const data = await res.json();
    return data.user_id;
}

async function fetchDictaminatorResponses(userId) {
    const res = await fetch(`/formato-evaluacion/get-dictaminators-responses?user_id=${userId}`);
    if (!res.ok) throw new Error('Error obteniendo comisiones');
    return await res.json();
}

// ======== utils.js ========
function calcularSubtotales(comisiones) {
    const subtotales = [
        { range: [5, 13], position: 14 },
        { range: [16, 18], position: 19 },
        { range: [21, 25], position: 26 },
        { range: [28, 30], position: 31 }
    ];

    subtotales.forEach(({ range, position }) => {
        let subtotal = 0;
        for (let i = range[0]; i <= range[1]; i++) {
            subtotal += parseFloat(comisiones[i]) || 0;
        }
        comisiones[position] = subtotal;
    });

    const sumaComision3 = Math.min(
        parseFloat(comisiones[14]) +
        parseFloat(comisiones[19]) +
        parseFloat(comisiones[26]) +
        parseFloat(comisiones[31]),
        700
    );

    comisiones[4] = parseFloat(sumaComision3).toFixed(2);
    comisiones[33] = comisiones[0]; // Permanencia
    comisiones[34] = comisiones[2]; // Dedicación
    comisiones[35] = comisiones[4]; // Calidad

    let total = parseFloat(comisiones[1]) + parseFloat(comisiones[3]) + parseFloat(comisiones[4]);
    total = Math.min(total, 1000).toFixed(2);
    comisiones[32] = total;
    comisiones[36] = total; // Total de puntaje obtenido en la evaluación

    // ✅ Aquí asignamos los índices 38 y 39
    const minimaCalidad = evaluarCalidad(parseFloat(comisiones[4]));
    const minimaTotal = evaluarTotal(parseFloat(comisiones[36]));

    comisiones[38] = minimaCalidad;
    comisiones[39] = minimaTotal;

    return { sumaComision3, total };
}


function renderTabla(labels, values, comisiones, dataContainer) {
    dataContainer.innerHTML = '';

    labels.forEach((label, i) => {
        const row = document.createElement('tr');
        const labelCell = document.createElement('td');
        const valueCell = document.createElement('td');
        const comisionCell = document.createElement('td');

        labelCell.textContent = label;
        valueCell.textContent = values[i] ?? '';

        const encabezadosSinValor = [15, 20, 27, 37];
        // 🧩 Lógica para mostrar correctamente valores numéricos y de texto
        if (i === 38 || i === 39) {
            // Mostrar texto (como "III", "V")
            comisionCell.textContent = comisiones[i] || '';
            comisionCell.style.fontWeight = 'bold';
            comisionCell.style.backgroundColor = 'transparent';
            
        } else if (encabezadosSinValor.includes(i)) {
            // Subtítulos (mostrar vacío)
            comisionCell.textContent = '';
        } else if (
            comisiones[i] !== undefined &&
            comisiones[i] !== null &&
            comisiones[i] !== '' &&
            !isNaN(parseFloat(comisiones[i]))
        ) {
            comisionCell.textContent = parseFloat(comisiones[i]).toFixed(2);
        } else {
            comisionCell.textContent = '';
        }

        // 🟨 Color de fondo solo para los índices numéricos relevantes
        if (![0, 2, 4, 14, 15, 19, 20, 26, 27, 31, 32, 36, 37, 38, 39].includes(i)) {
            comisionCell.style.backgroundColor = '#f6c667';
        }

        // 🔹 Negrita para encabezados y totales
        if ([0, 2, 4, 14, 19, 26, 31, 36, 37, 38, 39].includes(i)) {
            comisionCell.style.fontWeight = 'bold';
        }

        // 🔹 Estilo para subtítulos
        if (['Subtotal', 'Tutorias', 'Investigación', 'Cuerpos colegiados', 'Total logrado en la evaluación', 'Total de puntaje obtenido en la evaluación', 'Nivel obtenido de acuerdo al artículo 10 del Reglamento'].includes(label)) {
            labelCell.style.fontWeight = 'bold';
            labelCell.style.textAlign = 'center';
        }

        comisionCell.style.textAlign = 'center';
        row.append(labelCell, valueCell, comisionCell);
        dataContainer.appendChild(row);
    });
}

// ======== main.js ========
document.addEventListener('DOMContentLoaded', async () => {
    const docenteSearch = document.getElementById('docenteSearch');
    const dataContainer = document.getElementById('data');
    const formContainer = document.getElementById('formContainer');
    const userType = @json($userType);
    const teacherEmailFromUrl = @json($teacherEmailFromUrl ?? '');
    
    async function loadDocenteData(email) {
         if (!email) return;

        const loader = document.getElementById('loader');
        if (loader) loader.style.display = 'block';

        formContainer.style.display = 'none';
        dataContainer.innerHTML = '';

        try {
            const data = await fetchDocenteData(email);
            const userId = await fetchUserId(email);
            const dictaminatorData = await fetchDictaminatorResponses(userId);

            let comisiones = Array(41).fill('0');
            comisiones[0] = data.form2?.comision1 || '0';
            comisiones[1] = data.form2?.comision1 || '0';
            comisiones[2] = data.form2_2?.actv2Comision || '0';
            comisiones[3] = data.form2_2?.actv2Comision || '0';

            // llenar secciones 3.x
            comisiones[5] = data.form3_1?.actv3Comision || '0';
            comisiones[6] = data.form3_2?.comision3_2 || '0';
            comisiones[7] = data.form3_3?.comision3_3 || '0';
            comisiones[8] = data.form3_4?.comision3_4 || '0';
            comisiones[9] = data.form3_5?.comision3_5 || '0';
            comisiones[10] = data.form3_6?.comision3_6 || '0';
            comisiones[11] = data.form3_7?.comision3_7 || '0';
            comisiones[12] = data.form3_8?.comision3_8 || '0';
            comisiones[13] = data.form3_8_1?.comision3_8_1 || '0';
            comisiones[16] = data.form3_9?.comision3_9 || '0';
            comisiones[17] = data.form3_10?.comision3_10 || '0';
            comisiones[18] = data.form3_11?.comision3_11 || '0';
            comisiones[21] = data.form3_12?.comision3_12 || '0';
            comisiones[22] = data.form3_13?.comision3_13 || '0';
            comisiones[23] = data.form3_14?.comision3_14 || '0';
            comisiones[24] = data.form3_15?.comision3_15 || '0';
            comisiones[25] = data.form3_16?.comision3_16 || '0';
            comisiones[28] = data.form3_17?.comision3_17 || '0';
            comisiones[29] = data.form3_18?.comision3_18 || '0';
            comisiones[30] = data.form3_19?.comision3_19 || '0';

            const { sumaComision3, total } = calcularSubtotales(comisiones);


            document.getElementById('convocatoria-title').textContent = `Convocatoria: ${data.docente.convocatoria || 'No asignada'}`;
            renderTabla(labels, values, comisiones, dataContainer);
            formContainer.style.display = 'block';

            console.log('Comisiones calculadas:', comisiones);
            console.log('Total logrado:', total);

        } catch (error) {
            formContainer.style.display = 'none'; // Hide on error
            // On error, clear the button container
            const pdfButtonContainer = document.getElementById('pdfButtonContainer');
            if (pdfButtonContainer) {
                pdfButtonContainer.innerHTML = '';
            }
            console.error('Error procesando docente', error);
        } finally {
            if (loader) loader.style.display = 'none';
        }

        // Llamada a fetchSignatures pasando el email seleccionado
        const firmas = await fetchSignatures(email);

        // After all data is fetched and rendered, create the PDF button for 'secretaria'
        if (userType === 'secretaria' && email) {
            const pdfButtonContainer = document.getElementById('pdfButtonContainer');
            // Clear previous button
            pdfButtonContainer.innerHTML = ''; 

            // Create and append the new button
            const pdfLink = document.createElement('a');
            pdfLink.href = `{{ route('reporte_pdf') }}?email=${encodeURIComponent(email)}`;
            pdfLink.target = '_blank';
            pdfLink.className = 'btn custom-btn';
            pdfLink.innerHTML = `<i class="fa-solid fa-file-pdf"></i>&nbsp; Generar Reporte PDF`;
            pdfButtonContainer.appendChild(pdfLink);
        }
    }

    if (teacherEmailFromUrl) {
        loadDocenteData(teacherEmailFromUrl);
    }
    
    // Escuchar el evento personalizado 'docenteSelected' que dispara el componente de autocompletado
    document.addEventListener('docenteSelected', async (event) => {
        const docente = event.detail;
        const email = docente.email;

        await loadDocenteData(email);
    });
});


    //Enviar formulario
    async function submitForm(url, formId, user_id, email) {
        const form = document.getElementById(formId);
        let dataValues = new FormData(form);
        //let dictaminadorId = document.querySelector('input[name="dictaminador_id"]').value;
        
        if (!form) {
            console.error(`Form with id "${formId}" not found.`);
            return;
        }


        const reportLink = document.getElementById('reportLink');
        if (reportLink) {
            reportLink.classList.remove('d-none');
        } else {
            console.error('Element with id "reportLink" not found.');
        }
        
        //Obtener los nombres de los evaluadores y agregarlos a los datos del formulario
        const evaluatorNames = getEvaluatorNames();
        evaluatorNames.forEach((name, index) => {
            dataValues.append(`evaluator_name_${index + 1}`, name);
        });

        // Agregar los campos comunes
        let commonData = getCommodataValues(form);
        for (let key in commonData) {
            dataValues.append(key, commonData[key]);
        }

        if (!user_id || !email) {
            console.error('user_id or email is undefined');
            return;
        }

    dataValues.set('user_id', user_id); // Assuming 'id' contains the user ID
    dataValues.set('email', email);

        //dataValues.append('dictaminador_id', dictaminadorId);
        try {
            let response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: dataValues,
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

            // Si el envío es exitoso, recarga las firmas
            await loadSignatures();


        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
        
    }

window.submitForm = submitForm;


    async function fetchData(url, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const fullUrl = `${url}?${queryString}`;

        try {
            const response = await fetch(fullUrl);

            if (!response.ok) {
                throw new Error(`Request failed with status code ${response.status}`);
            }

            const data = await response.json();
            console.log('Data:', data); // Verificar los datos
            return data;
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error.message);
        }
    }

    async function loadSignatures() {
        const userId = document.getElementById('app').getAttribute('data-user-id');
        const email = document.getElementById('app').getAttribute('data-user-email');
        const userType = document.getElementById('app').getAttribute('data-user-type');

        let data = await fetchData('/formato-evaluacion/get-evaluator-signature', {
            user_id: userId,
            email: email,
            user_type: userType
        });

        if (data) {
            // Si las URLs de las firmas están disponibles, las mostramos
            console.log('Datos de firma recibidos:', data);

            // Verificar si los elementos imgFirma existen antes de asignarles src
            let imgFirma1 = document.getElementById('signature_path');
            let imgFirma2 = document.getElementById('signature_path_2');
            let imgFirma3 = document.getElementById('signature_path_3');

            if (data.signature_path && imgFirma1) {
                imgFirma1.src = data.signature_path;
                imgFirma1.style.display = 'block';
                imgFirma1.style.maxWidth = '200px';
                imgFirma1.style.height = '100px';
            }
            if (data.signature_path_2 && imgFirma2) {
                imgFirma2.src = data.signature_path_2;
                imgFirma2.style.display = 'block';
                imgFirma2.style.maxWidth = '200px';
                imgFirma2.style.height = '100px';
            }
            if (data.signature_path_3 && imgFirma3) {
                imgFirma3.src = data.signature_path_3;
                imgFirma3.style.display = 'block';
                imgFirma3.style.maxWidth = '200px';
                imgFirma3.style.height = '100px';
            }
        } else {
            console.error('Error: Signature data not found.');
        }
    }

    function getCommodataValues(form) {
        const data = {};

        data['user_id'] = form.querySelector('input[name="user_id"]').value;
        data['email'] = form.querySelector('input[name="email"]').value;
        data['user_type'] = form.querySelector('input[name="user_type"]').value;
        console.log('user_type value: ',data['user_type']);
        return data;
        }
       
    function getEvaluatorNames() {
            const evaluators = document.querySelectorAll('.personaEvaluadora, .personaEvaluadora2, .personaEvaluadora3');
            return Array.from(evaluators).map(evaluator => evaluator.textContent.trim());
        }
    document.addEventListener('DOMContentLoaded', function () {
            const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
            if (toggleDarkModeButton) {
                const widthDarkButton = window.innerWidth - 570;
                toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
            }
            toggleDarkMode();
        });

        function actualizarResultados(sumaComision3, totalLogrado) {
        const minimaCalidad = evaluarCalidad(sumaComision3);
        const minimaTotal = evaluarTotal(totalLogrado);

        // Actualizar el DOM con los valores calculados
        document.getElementById('minimaCalidad').textContent = minimaCalidad;
        document.getElementById('minimaTotal').textContent = minimaTotal;
    }

async function fetchSignatures(email) {
    try {
        const response = await fetch("{{ route('get.signatures') }}?email=" + encodeURIComponent(email));
        if (!response.ok) throw new Error('No se pudieron cargar las firmas');
        const data = await response.json();

        const tbody = document.getElementById('signaturesBody');
        tbody.innerHTML = '';

        if (Array.isArray(data) && data.length > 0) {
            // Use a Map to store unique signatures by evaluator name
            const uniqueSignatures = new Map();
            data.forEach(firma => {
                if (firma.evaluator_name && !uniqueSignatures.has(firma.evaluator_name)) {
                    uniqueSignatures.set(firma.evaluator_name, firma);
                }
            });

            // Iterate over the unique signatures and render them
            uniqueSignatures.forEach(firma => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${firma.evaluator_name ?? 'Sin nombre'}</td>
                    <td style="background-color: #d6fff7;">
                        ${firma.signature_image 
                            ? `<img src="data:${firma.mime};base64,${firma.signature_image}" 
                                     style="max-width: 150px; display:block;">`
                            : 'Sin firma'}
                    </td>
                `;
                tbody.appendChild(tr);
                
            });
        } else if (data.message) {
            tbody.innerHTML = `<tr><td colspan="2">${data.message}</td></tr>`;
        } else {
            tbody.innerHTML = '<tr><td colspan="2">No se encontraron dictaminadores para este docente.</td></tr>';
        }
    } catch (error) {
        console.error(error);
        document.getElementById('signaturesBody').innerHTML =
            '<tr><td colspan="2">Error al cargar firmas</td></tr>';
    }
}






    
    </script>

<div id="app" data-user-id="{{ auth()->user()->id }}" data-user-email="{{ auth()->user()->email }}" data-user-type="{{ auth()->user()->user_type }}" style="display: none;"></div></div>
<div id="reportLink" class="d-none">
    <!-- Contenido del enlace de reporte -->
</div>
<div id="messageContainer" class="message-container" style="display: none;"></div>
{{-- Footer dinámico para Snappy/wkhtmltopdf --}}
{{-- ...contenido del PDF... --}}
<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial", "normal");
            $size = 10;
            $convocatoria = "' . addslashes($convocatoria) . '";
            $pagina_inicio = ' . intval($pagina_inicio) . ';
            $pagina_total = ' . intval($pagina_total) . ';
            $y = 820; // posición vertical del footer
            $pdf->text(40, $y, "Programa de estímulos al desempeño del Personal docente: " . $convocatoria, $font, $size);
            $pdf->text(500, $y, "Página " . ($PAGE_NUM + $pagina_inicio - 1) . " de " . $pagina_total, $font, $size);
        ');
    }
</script>

<div id="pdfButtonContainer" style="text-align: center; margin-top: 40px;"></div>
</body>

</html>