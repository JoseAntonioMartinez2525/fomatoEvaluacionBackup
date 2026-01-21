@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$formType = request()->query('formType');
$formName = request()->query('formName');
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
use App\Models\DynamicForm; // Ensure to include the model
use App\Models\DictaminadorSignature;
use App\Models\User;

$forms = DynamicForm::all(); // Fetch all forms from the database
$existingFormNames = [];

// Obtener dictaminadores registrados que coinciden con la configuración
$allowedEmails = config('dictaminadores.emails', []);
$allowedNames = config('dictaminadores.nombres', []);
$registeredDictaminators = User::whereIn('email', $allowedEmails)->get();

// Obtener firmas existentes para saber quién ya tiene firma cargada
$registeredUserIds = $registeredDictaminators->pluck('id');
$existingSignatures = DictaminadorSignature::whereIn('user_id', $registeredUserIds)->pluck('user_id')->toArray();

// Combinar información para el listado completo
$allDictaminadores = [];
foreach ($allowedEmails as $index => $email) {
    $user = $registeredDictaminators->firstWhere('email', $email);
    $allDictaminadores[] = (object) [
        'email' => $email,
        'name' => $allowedNames[$index] ?? 'N/A',
        'user' => $user,
        'has_signature' => $user && in_array($user->id, $existingSignatures)
    ];
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
    <style>
        @media print {
            .footer-number::after {
                content: "1";
            }
        }

        .table-responsive {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.375rem 0.75rem;
        }
        .dataAcreditacion{
            font-weight: bold;
        }
        .puntajeValues{
            text-align: right;
        }
        #PrimerValorNumerico{
            text-align: center;
        }

        #puntajeComisionValues, #observacionesNForm{
            background-color: #d6fff7;
            
        }

        body.dark-mode #puntajeComisionValues, body.dark-mode #observacionesNForm{
            color: black;
        }
        /* Botón de modo oscuro */
        .dark-mode-button {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        /* Hierarchy Layout Styles */
        .hierarchy-container {
            display: flex;
            gap: 30px;
            padding: 20px;
            align-items: flex-start;
        }

        .hierarchy-column-left {
            flex: 0 0 auto;
            min-width: 300px;
        }

        .hierarchy-column-right {
            flex: 1;
            position: relative;
        }

        .hierarchy-level-1 {
            margin-bottom: 15px;
        }

        .hierarchy-level-1 .hierarchy-button {
            width: 100%;
            text-align: left;
            font-weight: 500;
            background-color: #528fb3;
            color: white;
            border-color: #528fb3;
        }

        .hierarchy-level-1 .hierarchy-button:hover {
            background-color: #4280a3;
            border-color: #4280a3;
            color: white;
        }

        .hierarchy-level-1 .hierarchy-button.category-header {
            background-color: #528fb3;
            color: white;
            border-color: #528fb3;
            pointer-events: none;
            opacity: 0.9;
            position: relative;
        }

        /* Horizontal line connecting category 3 to bracket */
        .hierarchy-level-1 .hierarchy-button.category-header::after {
            content: '';
            position: absolute;
            right: -30px;
            top: 50%;
            width: 30px;
            height: 3px;
            background-color: #528fb3;
            transform: translateY(-50%);
        }

        .bracket-container {
            position: relative;
            padding-left: 40px;
        }

        .bracket-container::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 25px;
            border-left: 3px solid #528fb3;
            border-top: 3px solid #528fb3;
            border-bottom: 3px solid #528fb3;
            border-radius: 15px 0 0 15px;
        }

        .bracket-item {
            margin-bottom: 12px;
        }

        .bracket-item .hierarchy-button {
            width: 100%;
            text-align: left;
            background-color: #528fb3;
            color: white;
            border-color: #528fb3;
        }

        .bracket-item .hierarchy-button:hover {
            background-color: #4280a3;
            border-color: #4280a3;
            color: white;
        }

        /* Dark mode adjustments */
        body.dark-mode .hierarchy-level-1 .hierarchy-button,
        body.dark-mode .bracket-item .hierarchy-button {
            background-color: #22426d;
            border-color: #22426d;
        }

        body.dark-mode .hierarchy-level-1 .hierarchy-button:hover,
        body.dark-mode .bracket-item .hierarchy-button:hover {
            background-color: #1a3555;
            border-color: #1a3555;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .hierarchy-container {
                flex-direction: column;
                gap: 20px;
            }

            .hierarchy-column-left {
                min-width: 100%;
            }

            .hierarchy-column-right {
                width: 100%;
            }

            /* Hide connecting line on mobile */
            .hierarchy-level-1 .hierarchy-button.category-header::after {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .bracket-container {
                padding-left: 25px;
            }

            .bracket-container::before {
                width: 15px;
                border-width: 2px;
                border-radius: 10px 0 0 10px;
            }
        }

        @media (max-width: 576px) {
            .hierarchy-container {
                padding: 10px;
            }

            .bracket-container {
                padding-left: 20px;
            }
        }

        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            transition: all .3s ease;
        }

        #cardBody1.dark-mode{
            color: white;
            background-color:#22426d;
        }


    </style>
    
</head>

<body class="font-sans antialiased">
    <x-general-header />
        <!-- Botón de modo oscuro (fuera del flujo normal) -->
        <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass dark-mode-button">
            <i class="fa-solid fa-moon"></i>&nbspModo Obscuro
        </button>
    <div class="bg-gray-50 text-black/50">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            @if (Route::has('login'))
                @if (Auth::check() && Auth::user()->user_type === 'secretaria')
                    <x-nav-menu :user="Auth::user()">
                        <div>
                            <!--Funcionalidad en caso de que se requiera un nuevo formulario
                            <ul style="list-style: none;">
                                <li class="nav-item">
                                    <a class="nav-link active enlaceSN" style="width: 300px;" href="{{route('dynamic_forms')}}"
                                        title="Ingresar nuevo formulario"><i class="fa-solid fa-folder-plus"></i>&nbspIngresar
                                        nuevo</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active enlaceSN" style="width: 300px;"
                                        href="{{route('edit_delete_form')}}" title="Editar ó eliminar formulario"><i
                                            class="fa-solid fa-user-pen"></i>&nbspEditar/Eliminar</a>
                                </li>
                            </ul>
                            -->
                        </div>
                    </x-nav-menu>
                @endif
                    <br>
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2"></div>

                    <nav class="-mx-3 flex flex-1 justify-end"></nav>

                    <div class="container mt-4 printButtonClass"> 
                        <div class="row g-4 mb-4">
                            <!-- Card 1: Dictaminadores Registrados (Collapse) -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm hover-card" style="cursor: pointer; border-left: 5px solid #198754;" data-bs-toggle="collapse" data-bs-target="#collapseDictaminadores" aria-expanded="false" aria-controls="collapseDictaminadores">
                                    <div class="card-body d-flex align-items-center p-4">
                                        <div class="bg-light rounded-circle p-3 me-3">
                                            <i class="fa-solid fa-user-tie fa-2x" style="color: #198754;"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title text-dark mb-1">Dictaminadores Registrados</h5>
                                            <p class="card-text text-muted small mb-0">Ver usuarios registrados.</p>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fa-solid fa-chevron-down text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Cargar Firmas (Collapse) -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm hover-card" style="cursor: pointer; border-left: 5px solid #ffc107;" data-bs-toggle="collapse" data-bs-target="#collapseFirmas" aria-expanded="false" aria-controls="collapseFirmas">
                                    <div class="card-body d-flex align-items-center p-4">
                                        <div class="bg-light rounded-circle p-3 me-3">
                                            <i class="fa-solid fa-file-signature fa-2x" style="color: #ffc107;"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title text-dark mb-1">Cargar Firmas</h5>
                                            <p class="card-text text-muted small mb-0">Gestionar firmas faltantes.</p>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fa-solid fa-chevron-down text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Docentes Asignados (Redirección) -->
                            <div class="col-md-4">
                                <a href="{{ route('docente.forms.index') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow-sm hover-card" style="border-left: 5px solid #528fb3;">
                                        <div class="card-body d-flex align-items-center p-4">
                                            <div class="bg-light rounded-circle p-3 me-3">
                                                <i class="fa-solid fa-chalkboard-user fa-2x" style="color: #528fb3;"></i>
                                            </div>
                                            <div>
                                                <h5 class="card-title text-dark mb-1">Docentes Asignados</h5>
                                                <p class="card-text text-muted small mb-0">Ir al listado de evaluaciones.</p>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fa-solid fa-arrow-right text-secondary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Card 4: Periodos de evaluacion -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm hover-card" style="cursor: pointer; border-left: 5px solid #077bff;" data-bs-toggle="collapse" data-bs-target="#collapsePeriodos" aria-expanded="false" aria-controls="collapsePeriodos">
                                    <div class="card-body d-flex align-items-center p-4">
                                        <div class="bg-light rounded-circle p-3 me-3">
                                            <i class="fa-solid fa-calendar" style="color: #077bff;"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title text-dark mb-1">Cargar Periodo para docentes</h5>
                                            
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fa-solid fa-chevron-down text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Contenido Colapsable -->
                        <div class="collapse mt-3" id="collapseDictaminadores" data-bs-parent=".container">
                            <div class="card card-body shadow-sm">
                                <h5 class="card-title mb-3 text-success"><i class="fa-solid fa-list-check"></i> Dictaminadores en el Sistema</h5>
                                @if($registeredDictaminators->isEmpty())
                                    <div class="alert alert-info">No se encontraron dictaminadores registrados del listado oficial.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Fecha Registro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($registeredDictaminators as $dictaminador)
                                                    <tr>
                                                        <td class="fw-bold">{{ $dictaminador->name }}</td>
                                                        <td>{{ $dictaminador->email }}</td>
                                                        <td>{{ $dictaminador->created_at ? $dictaminador->created_at->format('d/m/Y') : '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Contenido Colapsable: Cargar Firmas -->
                        <div class="collapse mt-3" id="collapseFirmas" data-bs-parent=".container">
                            <div class="card card-body shadow-sm mb-4">
                                <h5 class="card-title mb-3 text-warning"><i class="fa-solid fa-file-signature"></i> Gestión de Firmas de Dictaminadores</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Dictaminador</th>
                                                <th>Estado Usuario</th>
                                                <th>Firma en Sistema</th>
                                                <th>Cargar Firma</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allDictaminadores as $dict)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $dict->name }}</div>
                                                        <div class="small text-muted">{{ $dict->email }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($dict->user)
                                                            <span class="badge bg-success">Registrado</span>
                                                        @else
                                                            <span class="badge bg-secondary">No Registrado</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($dict->has_signature)
                                                            <span class="badge bg-primary"><i class="fa-solid fa-check"></i> Cargada</span>
                                                        @else
                                                            <span class="badge bg-danger"><i class="fa-solid fa-xmark"></i> Pendiente</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($dict->user)
                                                        <form class="upload-signature-form d-flex gap-2" onsubmit="event.preventDefault(); uploadSignature(this);">
                                                            <input type="hidden" name="email" value="{{ $dict->email }}">
                                                            <input type="hidden" name="user_id" value="{{ $dict->user->id }}">
                                                            <input type="hidden" name="evaluator_name" value="{{ $dict->name }}">
                                                            
                                                            <input type="file" name="firma1" class="form-control form-control-sm" accept="image/*" required>
                                                            <button type="submit" class="btn btn-sm btn-primary" title="Subir firma">
                                                                <i class="fa-solid fa-upload"></i>
                                                            </button>
                                                        </form>
                                                        @else
                                                            <small class="text-muted fst-italic">El usuario debe registrarse primero.</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido Colapsable: Periodos -->
                        <div class="collapse mt-3" id="collapsePeriodos" data-bs-parent=".container">
                            <div class="card card-body shadow-sm">
                                <h5 class="card-title mb-3 text-primary"><i class="fa-solid fa-calendar-days"></i> Periodo Vigente</h5>
                                <p class="text-muted">Fechas habilitadas para el llenado de evaluaciones por parte de los docentes (Configurado en Establecer fechas).</p>
                                
                                <div class="alert alert-light border-primary" role="alert">
                                    <div class="d-flex justify-content-between align-items-center px-3">
                                        <div class="text-center">
                                            <small class="text-muted text-uppercase fw-bold d-block mb-1">Inicio</small>
                                            <span id="lblFechaInicio" class="fs-5 fw-bold text-dark"><i class="fa-solid fa-spinner fa-spin"></i></span>
                                        </div>
                                        <div class="text-center text-primary">
                                            <i class="fa-solid fa-arrow-right-long fa-2x"></i>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted text-uppercase fw-bold d-block mb-1">Fin</small>
                                            <span id="lblFechaFin" class="fs-5 fw-bold text-dark"><i class="fa-solid fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-3">
                                    <button class="btn btn-outline-primary btn-sm" onclick="actualizarPeriodosDocentes()">
                                        <i class="fa-solid fa-sync"></i> Asignar Periodo a Todos los Docentes
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Selector para elegir el formulario -->
                        {{-- <label for="formGrid">Buscar Evaluación:</label>
                        
                        <div id="formGrid" class="hierarchy-container mt-4">
                            <!-- Left Column: Categories 1, 2, and 3 -->
                            <div class="hierarchy-column-left">
                                <!-- Level 1: Category 1 -->
                                <div class="hierarchy-level-1">
                                    <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form2')">
                                        1. Permanencia en las actividades de la docencia
                                    </button>
                                </div>

                                <!-- Level 1: Category 2 -->
                                <div class="hierarchy-level-1">
                                    <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form2_2')">
                                        2. Dedicación en el desempeño docente
                                    </button>
                                </div>

                                <!-- Level 1: Category 3 -->
                                <div class="hierarchy-level-1">
                                    <button class="btn hierarchy-button category-header">
                                        3. Calidad en la docencia
                                    </button>
                                </div>
                            </div>

                            <!-- Right Column: Sub-categories with left bracket -->
                            <div class="hierarchy-column-right">
                                <div class="bracket-container">
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_1')">
                                            3.1 Participación en actividades de diseño curricular
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_2')">
                                            3.2 Calidad del desempeño docente evaluada por el alumnado
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_3')">
                                            3.3 Publicaciones relacionadas con la docencia
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_4')">
                                            3.4 Distinciones académicas recibidas por el docente
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_5')">
                                            3.5 Asistencia, puntualidad y permanencia en el desempeño docente
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_6')">
                                            3.6 Capacitación y actualización pedagógica recibida
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_7')">
                                            3.7 Cursos de actualización disciplinaria
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_8')">
                                            3.8 Impartición de cursos, diplomados, seminarios
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_8_1')" title="Responsabilidad Social Universitaria">
                                            3.8.1 RSU
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_9')">
                                            3.9 Trabajos dirigidos para la titulación
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_10')">
                                            3.10 Tutorías a estudiantes
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_11')">
                                            3.11 Asesoría a estudiantes
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_12')">
                                            3.12 Publicaciones de investigación
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_13')">
                                            3.13 Proyectos académicos de investigación
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_14')">
                                            3.14 Participación como ponente en congresos
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_15')">
                                            3.15 Registro de patentes y productos de investigación
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_16')">
                                            3.16 Actividades de arbitraje y edición
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_17')">
                                            3.17 Proyectos académicos de extensión
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_18')">
                                            3.18 Organización de congresos institucionales
                                        </button>
                                    </div>
                                    <div class="bracket-item">
                                        <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/form3_19')">
                                            3.19 Participación en cuerpos colegiados
                                        </button>
                                    </div>

                                    <!-- Dynamic options -->
                                    @foreach($forms as $form)
                                        @if(!in_array($form->form_name, $existingFormNames))
                                            <div class="bracket-item">
                                                <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/{{ $form->form_name }}')">
                                                    {{ $form->form_name }}
                                                </button>
                                            </div>
                                            @php $existingFormNames[] = $form->form_name; @endphp
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}
                        
                        
                   
                    </div>

                    {{-- <div id="formContainer" class="mt-4">
                        <!-- Aquí se cargará el contenido del formulario seleccionado -->
                    </div> --}}
                </header>
            @endif
        </div>
    </div>

    <div>
        <footer>
            <div>
                <canvas id="convocatoriaCanvas" width="1500" height="500"></canvas>
            </div>
        </footer>
    </div>

    <script>
        // Funciones de utilidad para cálculos
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


        // Mantener las funciones existentes que podrían ser utilizadas en otras partes
        function onChange() {
            // Obtener los valores de los inputs
            const puntajePosgrado = parseFloat(document.getElementById("horasPosgrado").value);
            const puntajeSemestre = parseFloat(document.getElementById("horasSemestre").value);
            const h = parseFloat(document.querySelector('#hoursText'));

            // Realizar los cálculos
            const dsePosgrado = puntajePosgrado * 8.5;
            const dseSemestre = puntajeSemestre * 8.5;
            const hora = (dsePosgrado + dseSemestre);

            // Actualizar el contenido de las etiquetas <label>
            document.getElementById("DSE").innerText = dsePosgrado;
            document.getElementById("DSE2").innerText = dseSemestre;

            // Mostrar los valores actualizados en la consola
            console.log(dsePosgrado);
            console.log(dseSemestre);

            const minimo = minWithSum(dsePosgrado, dseSemestre);

            document.getElementById("hoursText").innerText = minimo;
            console.log(minimo);
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

    document.addEventListener('DOMContentLoaded', function () {
            const toggleDarkModeButton = document.getElementById('toggle-dark-mode');
            if (toggleDarkModeButton) {
                const widthDarkButton = window.outerWidth - 230;
                toggleDarkModeButton.style.marginLeft = `${widthDarkButton}px`;
            }

            toggleDarkMode();
        });
        
 function navigateToRoute(route) {
        window.location.href = route;
      }

    function uploadSignature(form) {
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Validar que se haya seleccionado un archivo
        if (!formData.get('firma1').name) {
            alert('Por favor seleccione un archivo de imagen.');
            return;
        }

        // Validación de seguridad: Verificar que al menos el email esté presente para que el controlador pueda resolver el ID
        if (!formData.get('user_id') && !formData.get('email')) {
            alert('Error: No se pudo identificar al usuario (Falta ID o Email). Por favor recargue la página.');
            return;
        }

        fetch('{{ route("store.signature.secretaria") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            if (!response.ok) {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    const errorData = await response.json();
                    let message = errorData.message || 'Error de validación';
                    if (errorData.errors) {
                        message += ':\n' + Object.values(errorData.errors).flat().join('\n');
                    }
                    throw new Error(message);
                }
                throw new Error('Error ' + response.status + ': ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload(); // Recargar para actualizar el estado de la firma en la tabla
            } else {
                alert('❌ Error: ' + (data.message || 'No se pudo guardar la firma.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Ocurrió un error al subir la firma: ' + error.message);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Función para formatear fecha: "2 de Diciembre del 2025"
        function formatearFecha(fechaISO) {
            if (!fechaISO) return 'No definido';
            const fecha = new Date(fechaISO);
            
            // Validar fecha
            if (isNaN(fecha.getTime())) return fechaISO;

            const dias = fecha.getUTCDate();
            const meses = [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ];
            const mes = meses[fecha.getUTCMonth()];
            const anio = fecha.getUTCFullYear();

            return `${dias} de ${mes} del ${anio}`;
        }

        // Cargar fechas existentes para docentes_llenado (Solo visualización)
        fetch('{{ url("/evaluation-dates") }}')
            .then(response => response.json())
            .then(data => {
                const lblInicio = document.getElementById('lblFechaInicio');
                const lblFin = document.getElementById('lblFechaFin');

                if (data.docentes_llenado) {
                    lblInicio.textContent = formatearFecha(data.docentes_llenado.start_date);
                    lblFin.textContent = formatearFecha(data.docentes_llenado.end_date);
                } else {
                    lblInicio.textContent = 'No definido';
                    lblFin.textContent = 'No definido';
                }
            })
            .catch(error => console.error('Error al cargar fechas:', error));
    });

    function actualizarPeriodosDocentes() {
        if (!confirm('¿Está seguro de que desea actualizar el periodo para TODOS los docentes registrados? Esta acción no se puede deshacer.')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ url("/formato-evaluacion/update-periods") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
            } else {
                alert('❌ Error: ' + (data.message || 'Ocurrió un error al actualizar.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error de conexión al intentar actualizar los periodos.');
        });
    }
    </script>
</body>
</html>