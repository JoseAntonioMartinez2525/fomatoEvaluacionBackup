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
    #convocatoria, #convocatoria2 {
        font-size: 1.2rem;
        color: blue; /* Ejemplo de estilo específico para Chrome */
    }


    @html{
        font-size: 2rem;
    }

    @media screen{
       #convocatoria, #convocatoria2 {
        font-size: 1.2rem;
        color: blue; /* Ejemplo de estilo específico para Chrome */
    }
}
 }

 .table3_3_2{
    margin-top: 200px;
 }

#convocatoria2{
    font-weight: bold;
    
}

.espaciadoConvocatoria{
        margin-top: 100px;
    }


@media print {
    .print-footer { /* Estilos comunes para ambos footers en la impresión */
        display: table-footer-group !important; /* Asegura que se muestre como footer */
        position: fixed; /* Para que se pegue al final de la página */
        bottom: 0;
        width: 100%;
    }
    .first-page-footer {
        /* Estilos específicos para el footer de la primera página */
    }
    .second-page-footer {
        /* Estilos específicos para el footer de la segunda página */
    }
    /* Oculta el footer que no corresponde a la página actual */
    .first-page-footer {
        display: table-footer-group;
    }
    .second-page-footer {
        display: none;
    }
    table:nth-of-type(2) ~ table .second-page-footer { /* Selecciona el segundo footer solo cuando hay dos tablas antes */
        display: table-footer-group;
    }
    table:nth-of-type(2) ~ table .first-page-footer { /* Oculta el primer footer cuando hay dos tablas antes */
        display: none;
    }
    body {
        -webkit-print-color-adjust: exact;
    }

    .espaciadoConvocatoria{
        margin-top: 100px;
    }
}

    @media print {
    .page-footer {
        position: relative;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 12px;
        background-color: white;
        padding: 10px 0;
        border-top: 1px solid #ccc;
        page-break-after: always; /* Asegura el salto de página después del footer */
    }
    body {
        
        margin-left: 200px ;
        margin-top: -10px;
        padding: 0;
        font-size: .7rem;
        padding-bottom: 50px;
       
    }
        .footerForm3_1 {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
    }

.prevent-overlap {
    page-break-before: always;
    page-break-inside: avoid; 
}

    #convocatoria, #convocatoria2, #piedepagina1, #piedepagina2 {
        margin: 0;
        font-size: .7rem;
    }

    #piedepagina {
        margin: 0;
    }

    @page {
        size: landscape;
        margin: 20mm; /* Ajusta según sea necesario */
        counter-increment: page;
        
    }
    
    @page:first {
  counter-reset: page 2; /* Initialize the counter to 2 for the first page */
  counter-increment: page;
}


    .page-number-display {
        display: block;
        text-align: center;
        font-size: 12px;
        position: fixed;
        bottom: 10px;
        left: 0;
        width: 100%;
        z-index: 1000;
    }
    
    
}

.page-footer.hidden-footer {
    display: none !important;
}


@media print {
    hidden-footer {
        display: none !important;
    }

    /* Prevent page breaks within table rows */
    table tr {
        page-break-inside: avoid;
    }

    .table-wrap{
      height: 100px; 
      page-break-inside: avoid; 
    }


    /* Página 4 */
/* Mostrar el footer correcto según la página */
    .page-break[data-page="6"] .first-page-footer {
        display: table-footer-group !important;
    }

    .page-break[data-page="7"] .second-page-footer {
        display: table-footer-group !important;
    }

    .page-number:before {
  content: "Página " counter(page) " de 33";
}

.secretaria-style {
    font-weight: bold;
    font-size: 14px;
    margin-top: 10px;
    text-align: left;
    
}

.secretaria-style #piedepagina1 {
    float: right;
    display: inline-block;
    margin-left: 5px;
    font-weight: normal; /* Opcional, si quieres menos énfasis */
    color: #000;
}

.dictaminador-style {
    font-weight: bold;
    font-size: 16px;
    margin-top: 10px;
    text-align: center;
}

.dictaminador-style#piedepagina2 {
    margin-left: 800px;
    margin-top: 10px;
    font-weight: normal!important;
}

/* Estilo para secretaria o userType vacío */
.secretaria-style#piedepagina2 {
    float: right;
    display: inline-block;
    margin-left: 5px;
    font-weight: normal; /* Opcional, si quieres menos énfasis */
    color: #000;
}
}

body.dark-mode [id^="rc"], body.dark-mode [class^="obs3_3_"]{
    color: black
}

body.dark-mode .comIncisoA, body.dark-mode .comIncisoB, body.dark-mode .comIncisoC,
 body.dark-mode .comIncisoD{
    color: black;
}

body.dark-mode td.cantidad{
    color:white;
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
        <!--Form for Part 3_3 -->
        <form id="form3_3" method="POST" onsubmit="event.preventDefault(); submitForm('/formato-evaluacion/store-form33', 'form3_3');">
            @csrf
            <input type="hidden" name="dictaminador_email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="user_id" value="">
            <input type="hidden" name="email" value="">
            <input type="hidden" name="user_type" value="">
            <div>
                <!-- Actividad 3.3 Publicaciones relacionadas con la docencia -->
                <h4>Puntaje máximo
                    <label class="bg-black text-white px-4 mt-3" for="">100</label>
                </h4>
            </div>
<table class="table table-sm">
    <x-table-header />
    <tbody class="page-break" data-page="6">
        <tr>
            <td class="seccion3_3" colspan="5">3.3 Publicaciones relacionadas con la docencia</td>
            <td id="score3_3">0</td>
            <td class="comision3_3" id="comision3_3"style="background-color: #ffcc6d; text-align: center; border: none; font-weight: bold;">0</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="incisos">Incisos</td>
            <td class="obra">Obra</td>
            <td>Actividad</td>
            <td>Puntaje</td>
            <td class="cantidad">Cantidad</td>
            <td>SubTotal</td>
        </tr>
        <!-- Primera tabla: Incisos a) y b) -->
        <tr>
            <td>a)</td>
            <td>Libro de texto con editorial de reconocido prestigio</td>
            <td>Autor(a)</td>
            <td>
                <center><b>100</b></center>
            </td>
            <td class="elabInput"><span class="rc1" id="rc1"></span></td>
            <td class="stotal1" id="stotal1"></td>
            <td class="comision actv">
                @if($userType == 'dictaminador')
                    <input type="number" step="0.01" class="comIncisoA" id="comIncisoA" name="comIncisoA" oninput="onActv3Comision3()"
                        value="{{ oldValueOrDefault('comIncisoA') }}">
                @else
                    <span name="comIncisoA" class="comIncisoA"></span>
                @endif
            </td>
            <td>
                @if($userType == 'dictaminador')
                    <input class="obs3_3_1" type="text">
                @else
                    <span class="obs3_3_1"></span>
                @endif
            </td>
        </tr>
        <tr>
            <td>b)</td>
            <td>1. Paquete didáctico, 2. Manual de operaciones</td>
            <td>Autor(a)</td>
            <td>
                <center><b>50</b></center>
            </td>
            <td class="elabInput"><span class="rc2" id="rc2"></span></td>
            <td class="stotal2" id="stotal2"></td>
            <td class="comision actv">
                @if($userType == 'dictaminador')
                    <input class="comIncisoB" id="comIncisoB" type="number" step="0.01" oninput="onActv3Comision3()"
                        value="{{ oldValueOrDefault('comIncisoB') }}">
                @else
                    <span class="comIncisoB"></span>
                @endif
            </td>
            <td>
                @if($userType == 'dictaminador')
                    <input class="obs3_3_2" type="text">
                @else
                    <span class="obs3_3_2"></span>
                @endif
            </td>
        </tr>
    </tbody>
</table>
<div class="espaciadoConvocatoria">
    <div id="convocatoria" colspan="8"
        class="{{ $userType == 'dictaminador' ? 'dictaminador-style' : 'secretaria-style' }}">
        @if(isset($convocatoria))
            @if($userType == 'dictaminador')
                <span style="margin-right: 700px; display: inline-block;">
                    <h1>Convocatoria: </h1>
                </span>
            @elseif($userType == '')
                <span style="margin-right: 60px; margin-left: 100px; display:nonek;padding-right: 12px; text-align:left;">
                    <h1>Convocatoria: </h1>
                </span>
                <span id="piedepagina1" style="display: none; margin-left: 20px;">
                    Página 3 de 33
                </span>
            @endif
        @endif
    </div>
<div colspan="8">
    @if($userType == 'dictaminador')
        <span id="piedepagina1" style="display: none;margin-left:800px;">Página 6 de 33</span>
    @endif
</div>
</div><br><br>

<table class="table table-sm table3_3_2">
    <x-table-header />
    <tbody class="page-break" data-page="7">
        <tr>
            <td class="seccion3_3" colspan="5">3.3 Publicaciones relacionadas con la docencia</td>
            <td id="score3_3_copy">0</td>
            <td class="comision3_3_copy" id="comision3_3_copy" style="background-color: #ffcc6d; text-align: center; border: none; font-weight: bold;">0</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td class="incisos">Incisos</td>
            <td class="obra">Obra</td>
            <td>Actividad</td>
            <td>Puntaje</td>
            <td class="cantidad">Cantidad</td>
            <td>SubTotal</td>
        </tr>
        <!-- Segunda tabla: Incisos c) y d) -->
        <tr>
            <td>c)</td>
            <td>1. Capítulo de libro, 2. Elaboración de Manuales de laboratorio...</td>
            <td>Autor(a)</td>
            <td>
                <center><b>30</b></center>
            </td>
            <td class="elabInput"><span class="rc3" id="rc3"></span></td>
            <td class="stotal3" id="stotal3"></td>
            <td class="comision actv">
                @if($userType == 'dictaminador')
                    <input class="comIncisoC" id="comIncisoC" type="number" step="0.01" oninput="onActv3Comision3()"
                        value="{{ oldValueOrDefault('comIncisoC') }}">
                @else
                    <span class="comIncisoC"></span>
                @endif
            </td>
            <td>
                @if($userType == 'dictaminador')
                    <input class="obs3_3_3" type="text">
                @else
                    <span class="obs3_3_3"></span>
                @endif
            </td>
        </tr>
        <tr>
            <td>d)</td>
            <td>1. Traducción de libro, 2. Traducción de material de apoyo didáctico...</td>
            <td>Autor(a)</td>
            <td>
                <center><b>25</b></center>
            </td>
            <td class="elabInput"><span class="rc4" id="rc4"></span></td>
            <td class="stotal4" id="stotal4"></td>
            <td class="comision actv">
                @if($userType == 'dictaminador')
                    <input class="comIncisoD" id="comIncisoD" type="number" step="0.01" oninput="onActv3Comision3()"
                        value="{{ oldValueOrDefault('comIncisoD') }}">
                @else
                    <span class="comIncisoD"></span>
                @endif
            </td>
            <td>
                @if($userType == 'dictaminador')
                    <input class="obs3_3_4" type="text">
                @else
                    <span class="obs3_3_4"></span>
                @endif
            </td>
        </tr>
    </tbody>
</table>
<!--Tabla informativa Acreditacion Actividad 3.3-->
<table>
    <thead>
        <tr>
            <th class="acreditacion" scope="col">Acreditacion: </th>

            <th class="descripcionCAAC"><b>CAAC, Instancia que la otorga</b></th>
        </tr>
    </thead>
</table>
    @if ($userType != '')
        <button id="btn3_3" type="submit" class="btn custom-btn printButtonClass">Enviar</button>
    @endif
<div class="espaciadoConvocatoria">
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
    Página 7 de 33
</div>

</div>            
</form>
    </main>
    <script>


    window.onload = function () {

        function preventOverlap() {
            const footerHeight = document.querySelector('footer')?.offsetHeight || 0;
            const elements = document.querySelectorAll('.prevent-overlap');

            elements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                if (rect.bottom > viewportHeight - footerHeight) {
                    element.style.pageBreakBefore = "always";
                }
            });
        }

        preventOverlap();

        // Actualizar la paginación antes de imprimir
        // window.addEventListener('beforeprint', updatePagination);
    };

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
                                console.log("Valor de score3_3:", data.form3_3?.score3_3);
                                document.getElementById('score3_3').textContent = data.form3_3.score3_3 ?? '0';
                                document.getElementById('score3_3_copy').textContent = data.form3_3.score3_3 ?? '0';
                                document.getElementById('rc1').textContent = data.form3_3.rc1 ?? '0';
                                document.getElementById('rc2').textContent = data.form3_3.rc2 ?? '0';
                                document.getElementById('rc3').textContent = data.form3_3.rc3 ?? '0';
                                document.getElementById('rc4').textContent = data.form3_3.rc4 ?? '0';
                                document.getElementById('stotal1').textContent = data.form3_3.stotal1 ?? '0';
                                document.getElementById('stotal2').textContent = data.form3_3.stotal2 ?? '0';
                                document.getElementById('stotal3').textContent = data.form3_3.stotal3 ?? '0';
                                document.getElementById('stotal4').textContent = data.form3_3.stotal4 ?? '0';

                                // Populate hidden inputs
                                document.querySelector('input[name="user_id"]').value = data.form3_3.user_id || '';
                                document.querySelector('input[name="email"]').value = data.form3_3.email || '';
                                document.querySelector('input[name="user_type"]').value = data.form3_3.user_type || '';

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
                                    const convocatoriaElement2 = document.getElementById('convocatoria2');

                                    // Mostrar la convocatoria si existe
                                    if (convocatoriaElement) {
                                        if (data.docente.convocatoria) {

                                            convocatoriaElement.textContent = data.docente.convocatoria;
                                            convocatoriaElement2.textContent = data.docente.convocatoria;
                                            const existingSpan = convocatoriaElement.querySelector('span#piedepagina1');

                                            if (!existingSpan) {
                                                const piedepaginaSpan = document.createElement('span');
                                                piedepaginaSpan.id = 'piedepagina1';
                                                piedepaginaSpan.textContent = ' Página 6 de 33';
                                                convocatoriaElement.appendChild(piedepaginaSpan);
                                            }
                                            convocatoriaElement.firstChild.textContent = data.docente.convocatoria;

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
                            const selectedResponseForm3_3 = dictaminatorResponses.form3_3.find(res => res.email === email);
                            if (selectedResponseForm3_3) {

                                document.querySelector('input[name="dictaminador_id"]').value = selectedResponseForm3_3.dictaminador_id || '0';
                                document.querySelector('input[name="user_id"]').value = selectedResponseForm3_3.user_id || '';
                                document.querySelector('input[name="email"]').value = selectedResponseForm3_3.email || '';
                                document.querySelector('input[name="user_type"]').value = selectedResponseForm3_3.user_type || '';

                                document.getElementById('score3_3').textContent = selectedResponseForm3_3?.score3_3 ?? '0';
                                document.getElementById('score3_3_copy').textContent = selectedResponseForm3_3?.score3_3 ?? '0';
                                document.getElementById('rc1').textContent = selectedResponseForm3_3?.rc1 ?? '0';
                                document.getElementById('rc2').textContent = selectedResponseForm3_3?.rc2 ?? '0';
                                document.getElementById('rc3').textContent = selectedResponseForm3_3?.rc3 ?? '0';
                                document.getElementById('rc4').textContent = selectedResponseForm3_3?.rc4 ?? '0';
                                document.getElementById('stotal1').textContent = selectedResponseForm3_3?.stotal1 ?? '0';
                                document.getElementById('stotal2').textContent = selectedResponseForm3_3?.stotal2 ?? '0';
                                document.getElementById('stotal3').textContent = selectedResponseForm3_3?.stotal3 ?? '0';
                                document.getElementById('stotal4').textContent = selectedResponseForm3_3?.stotal4 ?? '0';
                                document.querySelector('.comision3_3').textContent = selectedResponseForm3_3?.comision3_3 ?? '0';
                                document.querySelector('.comision3_3_copy').textContent = selectedResponseForm3_3?.comision3_3 ?? '0';


                                document.querySelector('span[class="comIncisoA"]').textContent = selectedResponseForm3_3.comIncisoA ?? '0';
                                document.querySelector('span[class="comIncisoB"]').textContent = selectedResponseForm3_3.comIncisoB ?? '0';
                                document.querySelector('span[class="comIncisoC"]').textContent = selectedResponseForm3_3.comIncisoC ?? '0';
                                document.querySelector('span[class="comIncisoD"]').textContent = selectedResponseForm3_3.comIncisoD ?? '0';
                                document.querySelector('span[class="obs3_3_1"]').textContent = selectedResponseForm3_3.obs3_3_1 ?? '';
                                document.querySelector('span[class="obs3_3_2"]').textContent = selectedResponseForm3_3.obs3_3_2 ?? '';
                                document.querySelector('span[class="obs3_3_3"]').textContent = selectedResponseForm3_3.obs3_3_3 ?? '';
                                document.querySelector('span[class="obs3_3_4"]').textContent = selectedResponseForm3_3.obs3_3_4 ?? '';


                            } else {

                                console.error('No form3_3 data found for the selected dictaminador.');
                                console.log("Ejecutando el bloque else, asignando valores por defecto...");

                                // Reset input values if no data found
                                const dictaminadorInput = document.querySelector('input[name="dictaminador_id"]');
                                if (dictaminadorInput) dictaminadorInput.value = '0';

                                const userIdInput = document.querySelector('input[name="user_id"]');
                                if (userIdInput) userIdInput.value = '0';

                                const emailInput = document.querySelector('input[name="email"]');
                                if (emailInput) emailInput.value = '';

                                const userTypeInput = document.querySelector('input[name="user_type"]');
                                if (userTypeInput) userTypeInput.value = '';

                                // Aplicación de valores
                                setValue('#score3_3', '0');
                                setValue('#score3_3_copy', '0');
                                setValue('#rc1', '0');
                                setValue('#rc2', '0');
                                setValue('#rc3', '0');
                                setValue('#rc4', '0');
                                setValue('#stotal1', '0');
                                setValue('#stotal2', '0');
                                setValue('#stotal3', '0');
                                setValue('#stotal4', '0');
                                setValue('.comision3_3', '0');
                                setValue('.comision3_3_copy', '0');
                                setValue('span[name="comIncisoA"]', '0');
                                setValue('.comIncisoB', '0');
                                setValue('.comIncisoC', '0');
                                setValue('.comIncisoD', '0');
                                setValue('.obs3_3_1', '');
                                setValue('.obs3_3_2', '');
                                setValue('.obs3_3_3', '');
                                setValue('.obs3_3_4', '');


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

    const pages = document.querySelectorAll(".page-break");
    const isPrinting = window.matchMedia('print').matches;

    if (isPrinting) {
        const firstFooter = document.querySelector('.first-page-footer');
        const secondFooter = document.querySelector('.second-page-footer');

        // Ocultar/mostrar los pies de página según el contenido visible
        pages.forEach((page) => {
            if (page.dataset.page === "6") {
                firstFooter.style.display = 'table-footer-group';
                secondFooter.style.display = 'none';
            } else if (page.dataset.page === "7") {
                firstFooter.style.display = 'none';
                secondFooter.style.display = 'table-footer-group';
            }
        });
    }

    window.addEventListener('beforeprint', () => {
        const pages = document.querySelectorAll(".page-break");

        pages.forEach(page => {
            const pageNumber = page.getAttribute('data-page');
            const firstFooter = page.querySelector('.first-page-footer');
            const secondFooter = page.querySelector('.second-page-footer');

            if (firstFooter) {
                firstFooter.style.display = pageNumber === '6' ? 'table-footer-group' : 'none';
            }

            if (secondFooter) {
                secondFooter.style.display = pageNumber === '7' ? 'table-footer-group' : 'none';
            }
        });
    });
    
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
            formData['rc1'] = document.getElementById('rc1').textContent;
            formData['comIncisoA'] = document.getElementById('comIncisoA').value; // Ensure input value is fetched
            formData['rc2'] = document.getElementById('rc2').textContent;
            formData['rc3'] = document.getElementById('rc3').textContent;
            formData['comIncisoB'] = document.getElementById('comIncisoB').value; // Ensure input value is fetched
            formData['rc4'] = document.getElementById('rc4').textContent;
            formData['stotal1'] = document.getElementById('stotal1').textContent;
            formData['comIncisoC'] = document.getElementById('comIncisoC').value; // Ensure input value is fetched
            formData['stotal2'] = document.getElementById('stotal2').textContent;
            formData['stotal3'] = document.getElementById('stotal3').textContent;
            formData['comIncisoD'] = document.getElementById('comIncisoD').value; // Ensure input value is fetched
            formData['stotal4'] = document.getElementById('stotal4').textContent;
            formData['comIncisoA'] = form.querySelector('input[id="comIncisoA"]').value;
            formData['comIncisoB'] = form.querySelector('input[id="comIncisoB"]').value;
            formData['comIncisoC'] = form.querySelector('input[id="comIncisoC"]').value;
            formData['comIncisoD'] = form.querySelector('input[id="comIncisoD"]').value;
            formData['score3_3'] = document.getElementById('score3_3').textContent;
            formData['comision3_3'] = document.querySelector('.comision3_3').textContent;

            // Observations
            formData['obs3_3_1'] = form.querySelector('input[class="obs3_3_1"]').textContent;
            formData['obs3_3_2'] = form.querySelector('input[class="obs3_3_2"]').textContent;
            formData['obs3_3_3'] = form.querySelector('input[class="obs3_3_3"]').textContent;
            formData['obs3_3_4'] = form.querySelector('input[class="obs3_3_4"]').textContent;

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

                // Mensaje al usuario
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

        // Función para asignar valores solo si el elemento existe
            function setValue(selector, value) {
                const element = document.querySelector(selector);
                if (element) {
                    if (element.tagName === 'INPUT' || element.tagName === 'SELECT') {
                        element.value = value;
                    } else {
                        element.textContent = value;
                    }
                } else {
                    console.warn(`Elemento no encontrado: ${selector}`);
                }
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