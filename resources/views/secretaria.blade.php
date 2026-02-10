@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$formType = request()->query('formType');
$formName = request()->query('formName');
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
use App\Models\DynamicForm; // Ensure to include the model
use App\Support\DictaminadoresConfig;
use App\Models\DictaminadorSignature;
use App\Models\User;

$forms = DynamicForm::all(); // Fetch all forms from the database
$existingFormNames = [];

// Obtener dictaminadores registrados que coinciden con la configuración
$dictaminadores = config('dictaminadores', []);

// Emails reales desde config NUEVO
$allowedEmails = array_keys($dictaminadores);

$registeredDictaminators = User::whereIn('email', $allowedEmails)->get();

// Firmas existentes
$registeredUserIds = $registeredDictaminators->pluck('id');
$existingSignatures = DictaminadorSignature::whereIn('user_id', $registeredUserIds)
    ->pluck('user_id')
    ->toArray();

// Construir listado final
$allDictaminadores = [];

foreach ($allowedEmails as $email) {
    $user = $registeredDictaminators->firstWhere('email', $email);
    $meta = DictaminadoresConfig::byEmail($email);

    $allDictaminadores[] = (object) [
        'email' => $email,
        'name' => $meta['nombre'] ?? 'N/A',
        'user' => $user,
        'has_signature' => $user && in_array($user->id, $existingSignatures),
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

        body.dark-mode .dynamic-th {
            background-color: black !important;
            color: white !important;
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

                            <!-- Card 5: Cargar Convocatoria -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm hover-card" style="cursor: pointer; border-left: 5px solid #6f42c1;" data-bs-toggle="collapse" data-bs-target="#collapseConvocatoria" aria-expanded="false" aria-controls="collapseConvocatoria">
                                    <div class="card-body d-flex align-items-center p-4">
                                        <div class="bg-light rounded-circle p-3 me-3">
                                            <i class="fa-solid fa-bullhorn" style="color: #6f42c1;"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title text-dark mb-1">Cargar Convocatoria</h5>
                                            <p class="card-text text-muted small mb-0">Establecer nombre de convocatoria.</p>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="fa-solid fa-chevron-down text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 6: Generador de Formularios -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm hover-card" style="cursor: pointer; border-left: 5px solid #dc3545;" data-bs-toggle="collapse" data-bs-target="#collapseGenerator" aria-expanded="false" aria-controls="collapseGenerator">
                                    <div class="card-body d-flex align-items-center p-4">
                                        <div class="bg-light rounded-circle p-3 me-3">
                                            <i class="fa-solid fa-table-list fa-2x" style="color: #dc3545;"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title text-dark mb-1">Generador de Formularios</h5>
                                            <p class="card-text text-muted small mb-0">Crear y configurar nuevas tablas.</p>
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

                                <!-- Sección Historial -->
                                <div class="mt-3 border-top pt-2">
                                    <button class="btn btn-link text-decoration-none text-secondary p-0 w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#historyCollapse" aria-expanded="false">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Ver Historial de Periodos Anteriores
                                    </button>
                                    <div class="collapse mt-2" id="historyCollapse">
                                        <ul class="list-group list-group-flush small" id="listaHistorialPeriodos">
                                            <!-- Se llena con JS -->
                                        </ul>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mt-3">
                                    <button class="btn btn-outline-primary btn-sm" onclick="actualizarPeriodosDocentes()">
                                        <i class="fa-solid fa-sync"></i> Asignar Periodo a Todos los Docentes
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido Colapsable: Convocatoria -->
                        <div class="collapse mt-3" id="collapseConvocatoria" data-bs-parent=".container">
                            <div class="card card-body shadow-sm">
                                <h5 class="card-title mb-3" style="color: #6f42c1;"><i class="fa-solid fa-bullhorn"></i> Configurar Convocatoria</h5>
                                <p class="text-muted">Ingrese el nombre de la convocatoria vigente para asignarla a todos los docentes.</p>
                                
                                <div class="mb-3">
                                    <label for="txtConvocatoria" class="form-label">Nombre de la Convocatoria</label>
                                    <input type="text" class="form-control" id="txtConvocatoria" placeholder="Ej. Convocatoria 2024-1">
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-sm" style="color: #6f42c1; border-color: #6f42c1;" onmouseover="this.style.backgroundColor='#6f42c1'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#6f42c1';" onclick="actualizarConvocatoriaDocentes()">
                                        <i class="fa-solid fa-save"></i> Guardar y Asignar Convocatoria
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido Colapsable: Generador -->
                        <div class="collapse mt-3" id="collapseGenerator" data-bs-parent=".container">
                            <div class="card card-body shadow-sm">
                                <h5 class="card-title mb-3" style="color: #dc3545;"><i class="fa-solid fa-table-list"></i> Nuevo Formulario Dinámico</h5>
                                <p class="text-muted">Defina la estructura de la nueva tabla de evaluación.</p>
                                
                                <form id="dynamicFormGenerator" onsubmit="event.preventDefault(); guardarFormularioDinamico();">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nombre del Formulario</label>
                                            <input type="text" class="form-control" id="newFormName" required placeholder="Ej. 3.20 Actividades Extraordinarias">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Puntaje Máximo</label>
                                            <input type="number" class="form-control" id="newFormMaxScore" required placeholder="Ej. 50">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Número de Filas</label>
                                            <input type="number" class="form-control" id="newFormRows" min="1" value="1">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Número de Columnas Dinámicas</label>
                                            <input type="number" class="form-control" id="newFormCols" min="0" value="1">
                                            <div class="form-text">Columnas adicionales entre Actividad y Puntajes.</div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="button" class="btn btn-secondary w-100" onclick="previewTable()">
                                                <i class="fa-solid fa-eye"></i> Previsualizar Tabla
                                            </button>
                                        </div>
                                    </div>

                                    <div id="previewContainer" class="mt-4 table-responsive" style="display:none;">
                                        <h6 class="text-muted mb-2">Vista Previa de Estructura</h6>
                                        <table class="table table-bordered" id="previewTable">
                                            <thead class="table-light">
                                                <tr id="previewHeaderRow">
                                                    <!-- Headers generated by JS -->
                                                </tr>
                                            </thead>
                                            <tbody id="previewBody">
                                                <!-- Rows generated by JS -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="100%">
                                                        <label class="fw-bold">Acreditación:</label>
                                                        <textarea class="form-control" id="newFormAcreditacion" rows="2" placeholder="Descripción de la acreditación..."></textarea>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa-solid fa-save"></i> Guardar Formulario
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                            <!-- Card 7: Generar Reportes de todos los docentes (Redirección) -->
                            <div class="col-md-4">
                                <a href="{{ route('users.export') }}" class="text-decoration-none">
                                    <div class="card h-100 shadow-sm hover-card" style="border-left: 5px solid #285bb3;">
                                        <div class="card-body d-flex align-items-center p-4">
                                            <div class="bg-light rounded-circle p-3 me-3">
                                                <i class="fa-solid fa-download" style="color: #285bb3;"></i>
                                            </div>
                                            <div>
                                                <h5 class="card-title text-dark mb-1">Generar Reportes de todos los docentes</h5>
                                                <p class="card-text text-muted small mb-0">listado de docentes en excel (.xls) y pdf independientes en archivo descargable .zip</p>
                                            </div>
                                            <div class="ms-auto">
                                                <i class="fa-solid fa-arrow-right text-secondary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
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
                                                <button class="btn hierarchy-button form-option" onclick="navigateToRoute('/formato-evaluacion/' + encodeURIComponent({{ json_encode($form->form_name) }}))">
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

        // Cargar historial de fechas para docentes_llenado
        fetch('{{ url("/evaluation-dates/history") }}')
            .then(response => response.json())
            .then(data => {
                const lblInicio = document.getElementById('lblFechaInicio');
                const lblFin = document.getElementById('lblFechaFin');
                const listaHistorial = document.getElementById('listaHistorialPeriodos');

                if (data && data.length > 0) {
                    // El primer elemento es el vigente (ordenado por ID desc)
                    const vigente = data[0];
                    lblInicio.textContent = formatearFecha(vigente.start_date);
                    lblFin.textContent = formatearFecha(vigente.end_date);

                    // El resto son historial
                    if (data.length > 1) {
                        for (let i = 1; i < data.length; i++) {
                            const periodo = data[i];
                            const li = document.createElement('li');
                            li.className = 'list-group-item bg-light text-muted px-0';
                            li.innerHTML = `<i class="fa-regular fa-calendar-check me-2"></i> ${formatearFecha(periodo.start_date)} - ${formatearFecha(periodo.end_date)}`;
                            listaHistorial.appendChild(li);
                        }
                    } else {
                        listaHistorial.innerHTML = '<li class="list-group-item text-muted fst-italic px-0">No hay periodos anteriores.</li>';
                    }
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

    function actualizarConvocatoriaDocentes() {
        const nombreConvocatoria = document.getElementById('txtConvocatoria').value;
        
        if (!nombreConvocatoria) {
            alert('Por favor ingrese un nombre para la convocatoria.');
            return;
        }

        if (!confirm('¿Está seguro de que desea establecer "' + nombreConvocatoria + '" como la convocatoria para TODOS los docentes?')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ url("/formato-evaluacion/update-convocatoria") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ convocatoria: nombreConvocatoria })
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
            alert('❌ Error de conexión al intentar actualizar la convocatoria.');
        });
    }

    function previewTable() {
        const rows = parseInt(document.getElementById('newFormRows').value) || 1;
        const cols = parseInt(document.getElementById('newFormCols').value) || 0;
        const formName = document.getElementById('newFormName').value || 'Nombre del Formulario';
        const table = document.getElementById('previewTable');
        const thead = table.querySelector('thead');
        const body = document.getElementById('previewBody');

        // Clear existing headers
        thead.innerHTML = '';

        // --- Row 1: Super Headers ---
        const tr1 = document.createElement('tr');
        
        // Actividad (colspan = 1 (Activity Name) + cols (Dynamic))
        const thActividad = document.createElement('th');
        thActividad.className = 'bg-light text-center dynamic-th';
        thActividad.colSpan = cols + 1; // +1 for the Description column
        thActividad.textContent = 'Actividad';
        tr1.appendChild(thActividad);

        // Puntaje a evaluar
        const thPuntaje = document.createElement('th');
        thPuntaje.className = 'bg-light text-center table-ajust cd dynamic-th';
        thPuntaje.textContent = 'Puntaje a evaluar';
        tr1.appendChild(thPuntaje);

        // Puntaje Comisión
        const thComision = document.createElement('th');
        thComision.className = 'bg-light text-center table-ajust cd dynamic-th';
        thComision.textContent = 'Puntaje de la Comisión Dictaminadora';
        tr1.appendChild(thComision);

        // Empty header for Observaciones alignment in Row 1
        const thObsPlaceholder1 = document.createElement('th');
        thObsPlaceholder1.className = 'bg-light';
        tr1.appendChild(thObsPlaceholder1);
        
        thead.appendChild(tr1);

        // --- Row 2: Section Title & Totals ---
        const tr2 = document.createElement('tr');

        // Form Name / Section Title
        const thSection = document.createElement('th');
        thSection.colSpan = cols + 1;
        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.className = 'form-control form-control-sm fw-bold';
        nameInput.value = formName;
        nameInput.id = 'previewFormNameInput';
        thSection.appendChild(nameInput);
        thSection.className = 'text-start ps-3'; 
        tr2.appendChild(thSection);

        // Total Score Placeholder
        const thTotalScore = document.createElement('th');
        thTotalScore.textContent = '0';
        thTotalScore.className = 'text-center';
        thTotalScore.style.backgroundColor = '#0b5967';
        thTotalScore.style.color = 'white';
        tr2.appendChild(thTotalScore);

        // Total Commission Placeholder
        const thTotalComm = document.createElement('th');
        thTotalComm.textContent = '0';
        thTotalComm.className = 'text-center';
        thTotalComm.style.backgroundColor = '#ffcc6d';
        tr2.appendChild(thTotalComm);

        // Empty header for Observaciones alignment in Row 2
        const thObsPlaceholder2 = document.createElement('th');
        tr2.appendChild(thObsPlaceholder2);

        thead.appendChild(tr2);

        // --- Row 3: Column Headers ---
        const tr3 = document.createElement('tr');
        
        // Fixed: Description/Activity Name
        const thDesc = document.createElement('th');
        thDesc.textContent = 'Descripción';
        thDesc.className = 'text-center';
        tr3.appendChild(thDesc);

        // Dynamic Columns Headers
        for(let i=0; i<cols; i++) {
            const th = document.createElement('th');
            th.innerHTML = `<input type="text" class="form-control form-control-sm dynamic-col-name text-center" placeholder="Encabezado ${i+1}">`;
            tr3.appendChild(th);
        }

        // Fixed: Subtotal
        const thSubtotal = document.createElement('th');
        thSubtotal.textContent = 'Subtotal';
        thSubtotal.className = 'text-center';
        tr3.appendChild(thSubtotal);

        // Fixed: Empty/Commission Header
        const thCommHeader = document.createElement('th');
        thCommHeader.textContent = ''; 
        tr3.appendChild(thCommHeader);

        // Fixed: Observaciones
        const thObs = document.createElement('th');
        thObs.textContent = 'Observaciones';
        thObs.className = 'text-center';
        tr3.appendChild(thObs);

        thead.appendChild(tr3);

        // Rows
        let rowsHtml = '';
        for(let i=0; i<rows; i++) {
            rowsHtml += '<tr>';
            
            // Description Value
            rowsHtml += `<td><input type="text" class="form-control form-control-sm row-desc-val" placeholder="Descripción de la actividad"></td>`;

            // Dynamic Values
            for(let j=0; j<cols; j++) {
                rowsHtml += `<td><input type="text" class="form-control form-control-sm row-dynamic-val text-center" placeholder="Valor"></td>`;
            }
            
            // Subtotal
            rowsHtml += '<td class="text-center bg-light"><small>0</small></td>'; 
            rowsHtml += '<td class="text-center bg-light"><small>0</small></td>'; 
            
            // Observaciones
            rowsHtml += '<td><input type="text" class="form-control form-control-sm" disabled></td>'; 
            rowsHtml += '</tr>';
        }
        body.innerHTML = rowsHtml;

        document.getElementById('previewContainer').style.display = 'block';
    }

    async function guardarFormularioDinamico() {
        // 1. Construir estructura con metadata
        const formStructure = buildFormStructure();
        const structureKeys = formStructure.map(col => col.key);

        // 2. Datos básicos
        const previewNameInput = document.getElementById('previewFormNameInput');
        const formName = previewNameInput
            ? previewNameInput.value
            : document.getElementById('newFormName').value;

        const maxScore = document.getElementById('newFormMaxScore').value;
        const acreditacion = document.getElementById('newFormAcreditacion').value;
        const rows = document.getElementById('newFormRows').value;
        const cols = document.getElementById('newFormCols').value;

        // 3. Nombres de columnas dinámicas (se mantienen por compatibilidad)
        const colInputs = document.querySelectorAll('.dynamic-col-name');
        let columnNames = [];
        colInputs.forEach(input => columnNames.push(input.value));

        // 4. Construir table_data
        let tableData = [];
        const trs = document.querySelectorAll('#previewBody tr');

        trs.forEach(tr => {
            const inputs = tr.querySelectorAll('input');
            let row = {};

            structureKeys.forEach((key, index) => {
                row[key] = inputs[index]?.value ?? '';
            });

            tableData.push(row);
        });

        // 5. Payload
        const payload = {
            form_name: formName,
            puntaje_maximo: maxScore,
            acreditacion: acreditacion,
            filas: rows,
            columnas: cols,
            column_names: columnNames,      // legacy / compatibilidad
            form_structure: formStructure,  // 🔑 CLAVE
            table_data: tableData,
            user_id: {{ Auth::id() }},
            email: "{{ Auth::user()->email }}",
            user_type: "{{ Auth::user()->user_type }}"
        };

        // 6. Envío
        try {
            const response = await fetch("{{ route('dynamic-form.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok) {
                let serverMessage = result.message || 'Error del servidor';
                if (result.errors) {
                    serverMessage += '\n' + Object.values(result.errors).flat().join('\n');
                }
                throw new Error(serverMessage);
            }

            if (result.success) {
                alert('✅ Formulario guardado correctamente');
                location.reload();
            } else {
                alert('❌ Error: ' + (result.message || 'Ocurrió un error inesperado.'));
            }

        } catch (e) {
            console.error('Error al guardar el formulario:', e);
            alert('❌ Error: ' + e.message);
        }
    }


    function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '_')
        .replace(/[^\w\-]+/g, '');
}

const dynamicHeaders = [];
document.querySelectorAll('.dynamic-col-name').forEach(input => {
    dynamicHeaders.push(slugify(input.value));
});


function buildFormStructure() {
    const structure = [];

    // 1. Actividad (Descripción)
    structure.push({
        key: 'actividad',
        name: 'Actividad',
        group: 'actividad'
    });

    // 2. Columnas dinámicas (también actividad)
    document.querySelectorAll('.dynamic-col-name').forEach(input => {
        structure.push({
            key: slugify(input.value),
            name: input.value,
            group: 'actividad'
        });
    });

    // 3. Puntaje a evaluar
    structure.push({
        key: 'puntaje_a_evaluar',
        name: 'Puntaje a evaluar',
        group: 'evaluacion'
    });

    // 4. Puntaje comisión
    structure.push({
        key: 'puntaje_de_la_comision_dictaminadora',
        name: 'Puntaje Comisión',
        group: 'comision'
    });

    // 5. Observaciones
    structure.push({
        key: 'observaciones',
        name: 'Observaciones',
        group: 'observaciones'
    });

    return structure;
}
    </script>
</body>
</html>