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
    <title>Formularios Completados - Evaluación docente</title>

    <x-head-resources />
    <script>
        window.isDarkModeGlobal = {{ session('dark_mode', false) ? 'true' : 'false' }};
    </script>
    <style>
        .form-card {
            background-color: white;
            border-left: 4px solid #528fb3;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateX(5px);
        }

        body.dark-mode .form-card {
            background-color: #2c2c2c;
            border-left-color: #22426d;
            color: white;
        }

        .form-card.completed {
            border-left-color: #28a745;
        }

        .form-card.incomplete {
            border-left-color: #dc3545;
        }

        .docente-header {
            background: linear-gradient(90deg, #528fb3, #4280a3);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        body.dark-mode .docente-header {
            background: linear-gradient(90deg, #22426d, #1a3555);
        }

        .btn-view-form {
            background-color: #528fb3;
            color: white;
            border: none;
        }

        .btn-view-form:hover {
            background-color: #4280a3;
            color: white;
        }

        body.dark-mode .btn-view-form {
            background-color: #22426d;
        }

        body.dark-mode .btn-view-form:hover {
            background-color: #1a3555;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        body.dark-mode .status-completed {
            background-color: #1a4d2e;
            color: #7fff7f;
        }

        .back-button {
            margin-bottom: 20px;
        }


    </style>
</head>

<body class="font-sans antialiased">
    @auth
        @if(Auth::user()->user_type !== 'docente' || in_array(Auth::user()->email, \App\Http\Controllers\SessionsController::$allowedEmails))
            <x-nav-menu :user="Auth::user()"/>
            <x-general-header />
            
            <button id="toggle-dark-mode" class="btn btn-secondary printButtonClass dark-mode-button">
                <i class="fa-solid fa-moon"></i>&nbsp;Modo Obscuro
            </button>

            <div class="container mt-4 main-content-area" style="max-width: 900px;">
                <!-- Back Button -->
                <div class="back-button">
                    <a href="{{ route('docente.forms.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Volver a la lista
                    </a>
                </div>

                <!-- Docente Header -->
                <div class="docente-header">
                    <h2 class="mb-2">
                        <i class="fa-solid fa-user"></i> {{ $docente->name ?? 'Docente' }}
                    </h2>
                    <p class="mb-0">
                        <i class="fa-solid fa-envelope"></i> {{ $docenteEmail }} 
                    </p>
                    <a class="text-white mt-4 fw-bold" href="{{ route('form4', ['teacher' => $docenteEmail]) }}">Ver Resumen General de comision</a>
                </div>

                <h4 class="mb-4">
                    <i class="fa-solid fa-folder-open"></i> Formularios Completados
                    <span class="badge bg-primary">{{ count($completedForms) }}</span>
                </h4>

                @if(count($completedForms) > 0)
                    <div id="formsList">
                        @foreach($completedForms as $form)
                            <div class="form-card completed">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">
                                            <i class="fa-solid fa-file-alt"></i> {{ $form['form_name'] }}
                                        </h5>
                                        <p class="mb-1 text-xs">
                                            <i class="fa-solid fa-calendar-check"></i> 
                                            <strong>Completado:</strong> 
                                            {{ $form['completed_at'] ? \Carbon\Carbon::parse($form['completed_at'])->format('d/m/Y H:i') : 'N/A' }}
                                        </p>
                                        @if($form['updated_at'] && $form['updated_at'] != $form['completed_at'])
                                            <p class="mb-0 text-xs">
                                                <i class="fa-solid fa-clock"></i> 
                                                <strong>Actualizado:</strong> 
                                                {{ \Carbon\Carbon::parse($form['updated_at'])->format('d/m/Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-completed mb-2 d-block">
                                            <i class="fa-solid fa-check-circle"></i> Completado
                                        </span>
                                        @if($form['route'] !== '#')
                                            <a href="{{ $form['route'] }}"
                                               class="btn btn-view-form btn-sm"
                                               target="_blank">
                                                <i class="fa-solid fa-eye"></i> Ver Detalles
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-exclamation-triangle"></i> 
                        Este docente no ha completado ningún formulario aún.
                    </div>
                @endif
            </div>
        @else
            <p>No tiene permisos para ver esta página.</p>
        @endif
    @else
        <p>Por favor, inicia sesión.</p>
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toggleDarkMode();
        });

        document.addEventListener('DOMContentLoaded', function () {

            const darkModeTextMuted = document.querySelectorAll('.text-muted');
            const isDarkMode = document.body.classList.contains('dark-mode');

            if (isDarkMode) {
                darkModeTextMuted.forEach(el => {
                    el.style.color = "white";
                });
            } else {
                darkModeTextMuted.forEach(el => {
                    el.style.color = "";
                });
            }

        });

    </script>
</body>
</html>