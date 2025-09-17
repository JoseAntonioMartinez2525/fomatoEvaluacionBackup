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

        @media print {

            footer {
                position: fixed;
                font-size: .9rem;
                bottom: 0;
                left: 0;
                width: 100%;
                text-align: center;
                font-size: 10px;
                background-color: white;
                /* Para asegurar que el footer no interfiera visualmente */
                z-index: 10;
                padding: 5px 0;
                border-top: 1px solid #ccc;
            }

            footer::after {
                position: fixed;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                font-size: 12px;
                background: white;
                padding: 5px;
                z-index: 10;
            }

            #convocatoria,
            #convocatoria2,
            #piedepagina1,
            #piedepagina2 {
                margin: 0;
                font-size: .6rem;
            }


            .page-number:before {
                content: "Página " counter(page) " de 33";
            }

            .secretaria-style {
                font-weight: normal;
                font-size: 14px;
                margin-top: 10px;
                text-align: left;
            }

            .secretaria-style #piedepagina1 {
                display: flex;
                justify-content: flex-end;
                font-weight: normal;
                /* Opcional, si quieres menos énfasis */
                color: #000;
            }

            .dictaminador-style {
                font-weight: bold;
                font-size: 16px;
                margin-top: 10px;
                text-align: center;
            }

            .dictaminador-style#piedepagina2 {
                display: flex;
                justify-content: flex-end;
                margin-top: 10px;
                font-weight: normal !important;
            }

            /* Estilo para secretaria o userType vacío */
            .secretaria-style#piedepagina2 {
                display: flex;
                justify-content: flex-end;
                margin-top: 0;
                font-weight: normal !important;
                display: inline-block;
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

    <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass"><i class="fa-solid fa-moon"></i>&nbspModo
        Obscuro</button>

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
        <form id="form3_12" method="POST" onsubmit="event.preventDefault(); submitForm('/store-form312', 'form3_12');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <!--3.12 Trabajos dirigidos para la titulación de estudiantes-->
            <h4>Puntaje máximo
                <label class="bg-black text-white px-4 mt-3" for="">150</label>
            </h4>
            <table class="table table-sm tutorias">
                <x-sub-headers-form3_12 :componentIndex="0" />
                <tbody data-page="18">
                    <!--3_12 Publicaciones de investigación incisos-->
                    <tr>
                        <td>a)</td>
                        <td>Autor(a) o coautor(a) de libros, técnicos, científicos y humanísticos</td>
                        <td>--</td>
                        <td>--</td>
                        <td id="puntajeCientificos">100</td>
                        <td id="cantCientifico"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalCientificos"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionCientificos" name="comisionCientificos"
                                    value="{{ oldValueOrDefault('comisionCientificos') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionCientificos" name="comisionCientificos" class="form3_19_dark"></span>
                            @endif                         
                        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsCientificos">
                            @else
                                <span id="obsCientificos" name="obsCientificos" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>b)</td>
                        <td>Autor(a) o coautor(a) de libros de divulgación</td>
                        <td>--</td>
                        <td>--</td>
                        <td id="puntajeDivulgacion">50</td>
                        <td id="cantDivulgacion"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalDivulgacion"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionDivulgacion" name="comisionDivulgacion"
                                    value="{{ oldValueOrDefault('comisionDivulgacion') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionDivulgacion" name="comisionDivulgacion" class="form3_19_dark"></span>
                            @endif                          </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsDivulgacion">
                            @else
                                <span id="obsDivulgacion" name="obsDivulgacion" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>c)</td>
                        <td>Traducción de libros</td>
                        <td>--</td>
                        <td>--</td>
                        <td id="puntajeTraduccion">40</td>
                        <td id="cantTraduccion"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalTraduccion"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionTraduccion" name="comisionTraduccion"
                                    value="{{ oldValueOrDefault('comisionTraduccion') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionTraduccion" name="comisionTraduccion" class="form3_19_dark"></span>
                            @endif  
 
                     </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsTraduccion">
                            @else
                                <span id="obsTraduccion" name="obsTraduccion" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>d)</td>
                        <td>Autor(a) o coautor(a) de artículos</td>
                        <td>Con Arbitraje</td>
                        <td>Internacional</td>
                        <td id="puntajeArbitrajeInt">60</td>
                        <td id="cantArbitrajeInt"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalArbitrajeInt"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionArbitrajeInt" name="comisionArbitrajeInt"
                                    value="{{ oldValueOrDefault('comisionArbitrajeInt') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionArbitrajeInt" name="comisionArbitrajeInt" class="form3_19_dark"></span>
                            @endif  
  
                    </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsArbitrajeInt">
                            @else
                                <span id="obsArbitrajeInt" name="obsArbitrajeInt" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>e)</td>
                        <td>Autor(a) o coautor(a) de artículos</td>
                        <td>Con Arbitraje</td>
                        <td>Nacional</td>
                        <td id="puntajeArbitrajeNac">30</td>
                        <td id="cantArbitrajeNac"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalArbitrajeNac"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionArbitrajeNac" name="comisionArbitrajeNac"
                                    value="{{ oldValueOrDefault('comisionArbitrajeNac') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionArbitrajeNac" name="comisionArbitrajeNac" class="form3_19_dark"></span>
                            @endif  
 
                     </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsArbitrajeNac">
                            @else
                                <span id="obsArbitrajeNac" name="obsArbitrajeNac" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- convocatoria -->
            <div style="display: flex; justify-content: space-between;padding-top: 80px;">
                <div id="convocatoria">
                    <!-- Mostrar convocatoria -->
                    @if(isset($convocatoria))
                        <div style="margin-right: -500px;">
                            <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                        </div>
                    @endif
                </div>
                <div id="piedepagina1"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === '' ? 'secretaria-style' : '') }}">
                    Página 18 de 33
                </div>
            </div><br><br>

            <!--Tabla 2-->
            <table class="table table-sm tutorias">
                <x-sub-headers-form3_12 :componentIndex="1" />
                <tbody data-page="19">
                    <tr>
                        <td>f)</td>
                        <td>Autor(a) o coautor(a) de artículos</td>
                        <td>Sin Arbitraje</td>
                        <td>Internacional</td>
                        <td id="puntajeSinInt">15</td>
                        <td id="cantSinInt"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalSinInt"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionSinInt" name="comisionSinInt"
                                    value="{{ oldValueOrDefault('comisionSinInt') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionSinInt" name="comisionSinInt" class="form3_19_dark"></span>
                            @endif  
      
                </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsSinInt">
                            @else
                                <span id="obsSinInt" name="obsSinInt" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>g)</td>
                        <td>Autor(a) o coautor(a) de artículos</td>
                        <td>Sin Arbitraje</td>
                        <td>Nacional</td>
                        <td id="puntajeSinNac">10</td>
                        <td id="cantSinNac"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalSinNac"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionSinNac" name="comisionSinNac"
                                    value="{{ oldValueOrDefault('comisionSinNac') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionSinNac" name="comisionSinNac" class="form3_19_dark"></span>
                            @endif  
              
        </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsSinNac">
                            @else
                                <span id="obsSinNac" name="obsSinNac" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>h)</td>
                        <td>Capítulo de libro especializado</td>
                        <td>Autor(a) o coautor (a) de capítulo de libro internacional o nacional</td>
                        <td>--</td>
                        <td id="puntajeAutor">25</td>
                        <td id="cantAutor"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalAutor"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionAutor" name="comisionAutor"
                                    value="{{ oldValueOrDefault('comisionAutor') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionAutor" name="comisionAutor" class="form3_19_dark"></span>
                            @endif  
        
              </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsAutor">
                            @else
                                <span id="obsAutor" name="obsAutor" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>i)</td>
                        <td>Capítulo de libro especializado</td>
                        <td>Editor(a) o coeditor(a) de libro</td>
                        <td>--</td>
                        <td id="puntajeEditor">25</td>
                        <td id="cantEditor"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalEditor"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionEditor" name="comisionEditor"
                                    value="{{ oldValueOrDefault('comisionEditor') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionEditor" name="comisionEditor" class="form3_19_dark"></span>
                            @endif  
   
                   </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsEditor">
                            @else
                                <span id="obsEditor" name="obsEditor" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>j)</td>
                        <td>Sitio web</td>
                        <td>Diseño de sitio web</td>
                        <td>--</td>
                        <td id="puntajeWeb">20</td>
                        <td id="cantWeb"></td>
                        <td></td>
                        <td></td>
                        <td id="subtotalWeb"></td>
                        <td class="rightSelect">
                            @if ($userType == 'dictaminador')
                                <input type="number" step="0.01" id="comisionWeb" name="comisionWeb"
                                    value="{{ oldValueOrDefault('comisionWeb') }}" oninput="onActv3Comision3_12()">
                            @else
                                <span id="comisionWeb" name="comisionWeb" class="form3_19_dark"></span>
                            @endif  
          
            </td>
                        <td>
                            @if ($userType == 'dictaminador')
                                <input class="table-header" type="text" id="obsWeb">
                            @else
                                <span id="obsWeb" name="obsWeb" class="form3_19_dark"></span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--Tabla informativa Acreditacion Actividad 3.12-->
            <table>
                <thead>
                    <tr>
                        <th class="acreditacion" scope="col">Acreditacion: </th>

                        <th class="descripcion"><b>Instancia que la otorga</b> </th>
                        <th>
                            @if ($userType != '')
                                <button id="btn3_12" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
                            @endif
                        </th>
                    </tr>
                </thead>
            </table>
            <div style="display: flex; justify-content: space-between;padding-top: 150px;">
                <div id="convocatoria2">
                    <!-- Mostrar convocatoria -->
                    @if(isset($convocatoria))

                        <div style="margin-right: -700px;">
                            <h1>Convocatoria: {{ $convocatoria->convocatoria }}</h1>
                        </div>
                    @endif
                </div>

                <div id="piedepagina2"
                    class="{{ $userType === 'dictaminador' ? 'dictaminador-style' : ($userType === '' ? 'secretaria-style' : '') }}">
                    Página 19 de 33
                </div>
            </div><br><br>
        </form>
    </main>
    <script>
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
        let cant3_12 = ['cantCientifico', 'cantDivulgacion', 'cantTraduccion', 'cantArbitrajeInt', 'cantArbitrajeNac', 'cantSinInt', 'cantSinNac', 'cantAutor', 'cantEditor', 'cantWeb'];
        let subtotal3_12 = ['subtotalCientificos', 'subtotalDivulgacion', 'subtotalTraduccion', 'subtotalArbitrajeInt', 'subtotalArbitrajeNac', 'subtotalSinInt', 'subtotalSinNac', 'subtotalAutor', 'subtotalEditor', 'subtotalWeb'];
        let comision3_12 = ['comisionCientificos', 'comisionDivulgacion', 'comisionTraduccion', 'comisionArbitrajeInt', 'comisionArbitrajeNac', 'comisionSinInt', 'comisionSinNac', 'comisionAutor', 'comisionEditor', 'comisionWeb'];
        let obs3_12 = ['obsCientificos', 'obsDivulgacion', 'obsTraduccion', 'obsArbitrajeInt', 'obsArbitrajeNac', 'obsSinInt', 'obsSinNac', 'obsAutor', 'obsEditor', 'obsWeb'];

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

                                        const scoreElements = document.querySelectorAll('.score3_12');
                                        scoreElements.forEach(element => {
                                            element.textContent = data.form3_12.score3_12 || '0';
                                        });

                                        // Cantidades
                                        document.getElementById('cantCientifico').textContent = data.form3_12.cantCientifico || '0';
                                        document.getElementById('cantDivulgacion').textContent = data.form3_12.cantDivulgacion || '0';
                                        document.getElementById('cantTraduccion').textContent = data.form3_12.cantTraduccion || '0';
                                        document.getElementById('cantArbitrajeInt').textContent = data.form3_12.cantArbitrajeInt || '0';
                                        document.getElementById('cantArbitrajeNac').textContent = data.form3_12.cantArbitrajeNac || '0';
                                        document.getElementById('cantSinInt').textContent = data.form3_12.cantSinInt || '0';
                                        document.getElementById('cantSinNac').textContent = data.form3_12.cantSinNac || '0';
                                        document.getElementById('cantAutor').textContent = data.form3_12.cantAutor || '0';
                                        document.getElementById('cantEditor').textContent = data.form3_12.cantEditor || '0';
                                        document.getElementById('cantWeb').textContent = data.form3_12.cantWeb || '0';

                                        // Subtotales
                                        document.getElementById('subtotalCientificos').textContent = data.form3_12.subtotalCientificos || '0';
                                        document.getElementById('subtotalDivulgacion').textContent = data.form3_12.subtotalDivulgacion || '0';
                                        document.getElementById('subtotalTraduccion').textContent = data.form3_12.subtotalTraduccion || '0';
                                        document.getElementById('subtotalArbitrajeInt').textContent = data.form3_12.subtotalArbitrajeInt || '0';
                                        document.getElementById('subtotalArbitrajeNac').textContent = data.form3_12.subtotalArbitrajeNac || '0';
                                        document.getElementById('subtotalSinInt').textContent = data.form3_12.subtotalSinInt || '0';
                                        document.getElementById('subtotalSinNac').textContent = data.form3_12.subtotalSinNac || '0';
                                        document.getElementById('subtotalAutor').textContent = data.form3_12.subtotalAutor || '0';
                                        document.getElementById('subtotalEditor').textContent = data.form3_12.subtotalEditor || '0';
                                        document.getElementById('subtotalWeb').textContent = data.form3_12.subtotalWeb || '0';


                                        // Populate hidden inputs
                                        document.querySelector('input[name="user_id"]').value = data.form3_12.user_id || '';
                                        document.querySelector('input[name="email"]').value = data.form3_12.email || '';
                                        document.querySelector('input[name="user_type"]').value = data.form3_12.user_type || '';

                                        // Actualizar convocatoria
                                        const convocatoriaElement = document.getElementById('convocatoria');
                                        const convocatoriaElement2 = document.getElementById('convocatoria2');

                                        if (convocatoriaElement) {
                                            if (data.form1) {
                                                convocatoriaElement.textContent = data.form1.convocatoria || '';
                                                convocatoriaElement2.textContent = data.form1.convocatoria || '';
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
                                            const convocatoriaElement2 = document.getElementById('convocatoria2');
                                            // Mostrar la convocatoria si existe
                                            if (convocatoriaElement) {
                                                if (data.docente.convocatoria) {
                                                    convocatoriaElement.textContent = data.docente.convocatoria;
                                                    convocatoriaElement2.textContent = data.docente.convocatoria;
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
                                    const selectedResponseForm3_12 = dictaminatorResponses.form3_12.find(res => res.email === email);
                                    if (selectedResponseForm3_12) {

                                        document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_12.dictaminador_id ?? '0';
                                        document.querySelector('input[name="user_id"]').value = selectedResponseForm3_12.user_id ?? '';
                                        document.querySelector('input[name="email"]').value = selectedResponseForm3_12.email ?? '';
                                        document.querySelector('input[name="user_type"]').value = selectedResponseForm3_12.user_type ?? '';

                                        // Cantidades
                                        document.getElementById('cantCientifico').textContent = selectedResponseForm3_12.cantCientifico ?? '0';
                                        document.getElementById('cantDivulgacion').textContent = selectedResponseForm3_12.cantDivulgacion ?? '0';
                                        document.getElementById('cantTraduccion').textContent = selectedResponseForm3_12.cantTraduccion ?? '0';
                                        document.getElementById('cantArbitrajeInt').textContent = selectedResponseForm3_12.cantArbitrajeInt ?? '0';
                                        document.getElementById('cantArbitrajeNac').textContent = selectedResponseForm3_12.cantArbitrajeNac ?? '0';
                                        document.getElementById('cantSinInt').textContent = selectedResponseForm3_12.cantSinInt ?? '0';
                                        document.getElementById('cantSinNac').textContent = selectedResponseForm3_12.cantSinNac ?? '0';
                                        document.getElementById('cantAutor').textContent = selectedResponseForm3_12.cantAutor ?? '0';
                                        document.getElementById('cantEditor').textContent = selectedResponseForm3_12.cantEditor ?? '0';
                                        document.getElementById('cantWeb').textContent = selectedResponseForm3_12.cantWeb ?? '0';

                                        // Subtotales
                                        document.getElementById('subtotalCientificos').textContent = selectedResponseForm3_12.subtotalCientificos ?? '0';
                                        document.getElementById('subtotalDivulgacion').textContent = selectedResponseForm3_12.subtotalDivulgacion ?? '0';
                                        document.getElementById('subtotalTraduccion').textContent = selectedResponseForm3_12.subtotalTraduccion ?? '0';
                                        document.getElementById('subtotalArbitrajeInt').textContent = selectedResponseForm3_12.subtotalArbitrajeInt ?? '0';
                                        document.getElementById('subtotalArbitrajeNac').textContent = selectedResponseForm3_12.subtotalArbitrajeNac ?? '0';
                                        document.getElementById('subtotalSinInt').textContent = selectedResponseForm3_12.subtotalSinInt ?? '0';
                                        document.getElementById('subtotalSinNac').textContent = selectedResponseForm3_12.subtotalSinNac ?? '0';
                                        document.getElementById('subtotalAutor').textContent = selectedResponseForm3_12.subtotalAutor ?? '0';
                                        document.getElementById('subtotalEditor').textContent = selectedResponseForm3_12.subtotalEditor ?? '0';
                                        document.getElementById('subtotalWeb').textContent = selectedResponseForm3_12.subtotalWeb ?? '0';

                                        // Comisiones
                                        document.querySelector('#comisionCientificos').textContent = selectedResponseForm3_12.comisionCientificos ?? '0';
                                        document.querySelector('#comisionDivulgacion').textContent = selectedResponseForm3_12.comisionDivulgacion ?? '0';
                                        document.querySelector('#comisionTraduccion').textContent = selectedResponseForm3_12.comisionTraduccion ?? '0';
                                        document.querySelector('#comisionArbitrajeInt').textContent = selectedResponseForm3_12.comisionArbitrajeInt ?? '0';
                                        document.querySelector('#comisionArbitrajeNac').textContent = selectedResponseForm3_12.comisionArbitrajeNac ?? '0';
                                        document.querySelector('#comisionSinInt').textContent = selectedResponseForm3_12.comisionSinInt ?? '0';
                                        document.querySelector('#comisionSinNac').textContent = selectedResponseForm3_12.comisionSinNac ?? '0';
                                        document.querySelector('#comisionAutor').textContent = selectedResponseForm3_12.comisionAutor ?? '0';
                                        document.querySelector('#comisionEditor').textContent = selectedResponseForm3_12.comisionEditor ?? '0';
                                        document.querySelector('#comisionWeb').textContent = selectedResponseForm3_12.comisionWeb ?? '0';

                                        // Observaciones
                                        document.querySelector('#obsCientificos').textContent = selectedResponseForm3_12.obsCientificos ?? '';
                                        document.querySelector('#obsDivulgacion').textContent = selectedResponseForm3_12.obsDivulgacion ?? '';
                                        document.querySelector('#obsTraduccion').textContent = selectedResponseForm3_12.obsTraduccion ?? '';
                                        document.querySelector('#obsArbitrajeInt').textContent = selectedResponseForm3_12.obsArbitrajeInt ?? '';
                                        document.querySelector('#obsArbitrajeNac').textContent = selectedResponseForm3_12.obsArbitrajeNac ?? '';
                                        document.querySelector('#obsSinInt').textContent = selectedResponseForm3_12.obsSinInt ?? '';
                                        document.querySelector('#obsSinNac').textContent = selectedResponseForm3_12.obsSinNac ?? '';
                                        document.querySelector('#obsAutor').textContent = selectedResponseForm3_12.obsAutor ?? '';
                                        document.querySelector('#obsEditor').textContent = selectedResponseForm3_12.obsEditor ?? '';
                                        document.querySelector('#obsWeb').textContent = selectedResponseForm3_12.obsWeb ?? '';

                                        // Update all elements with the class 'score3_12'
                                        const scoreElements = document.querySelectorAll('.score3_12');
                                        scoreElements.forEach(element => {
                                            element.textContent = selectedResponseForm3_12.score3_12 || '0';
                                        });

                                        // Update all elements with the class 'comision3_12'
                                        const comisionElements = document.querySelectorAll('.comision3_12');
                                        comisionElements.forEach(element => {
                                            element.textContent = selectedResponseForm3_12.comision3_12 || '0';
                                        });

                                    }else {
    console.error('No form3_12 data found for the selected dictaminador.');

    // Reset input values
    setValue('dictaminador_id', '0');
    setValue('user_id', '0');
    setValue('email', '');
    setValue('user_type', '');

    setValue('score3_12', '0');
    setValue('comision3_12', '0');

    // Reset cantidad values
    cant3_12.forEach(cantidad => setValue(cantidad, '0'));

    // Reset subtotal values
    subtotal3_12.forEach(subtotal => setValue(subtotal, '0'));

    // Reset comision values
    comision3_12.forEach(comision => setValue(comision, '0'));

    // Reset observation values
    obs3_12.forEach(obs => setValue(obs, ''));
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

            // Puntajes
            for (let i = 0; i < cant3_12.length; i++) {
                formData[cant3_12[i]] = document.getElementById(cant3_12[i])?.textContent || '';
            }

            // Subtotales
            for (let j = 0; j < subtotal3_12.length; j++) {
                formData[subtotal3_12[j]] = document.getElementById(subtotal3_12[j])?.textContent || '';
            }

            // Comisiones
            for (let k = 0; k < comision3_12.length; k++) {
                formData[comision3_12[k]] = form.querySelector(`input[id="${comision3_12[k]}"]`)?.value || '';
            }

            // Observaciones
            for (let l = 0; l < obs3_12.length; l++) {
                formData[obs3_12[l]] = form.querySelector(`input[id="${obs3_12[l]}"]`)?.value || '';
            }

            formData['score3_12'] = document.querySelector('.score3_12').textContent;
            
            const comisionElement = document.querySelector('.comision3_12');
            formData['comision3_12'] = comisionElement ? comisionElement.textContent : '0';

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