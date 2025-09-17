@php
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
@endphp
<!DOCTYPE html>
<!--
Nombre del programador: José Antonio Martínez del Toro
Objetivo: Implementación frontend del registro de usuarios
Fecha de creación: 2024-06-03
-->
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <link rel="icon" href="{{ $logo }}" type="image/png">
    <title>Evaluación docente</title>   
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 5" />
    <meta name="keywords" content="dashboard, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">
    <link rel="canonical" href="https://1.envato.market/vuexy_admin">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet">
    <style>
        #labelRegister{
            padding-left: 100px;
        }
    </style>
</head>

<body>
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row d-flex justify-content-center align-items-center">
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <div class="mb-3 text-center py-3">
                    </div>

                    <!-- Formulario de Registro -->
                    <form id="registerForm" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="text-center mb-3">
                            <p class="mb-2">Registrarse</p>
                           
                        </div>
                        <!-- Name input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="registerName" name="registerName" class="form-control" style="padding-right: 300px;"/>
                            <label class="form-label" for="registerName">Nombre (nombre de pila ó nombre completo)</label>
                            @error('registerName')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- Usertype input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <select  id="registerUsertype" name="registerUsertype" class="form-control" >
                                <option value=""></option>
                                <option value="dictaminador">Dictaminador</option>
                                <option value="docente">Docente</option>
                            </select>
                            <label class="form-label" for="registerUsertype" id="labelRegister">Usuario (Haz click aquí y selecciona)</label>
                                @error('registerUsertype')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                        </div>
                        <!-- Email input -->
                        <div data-mdb-input-init class="mb-4">
                            <input type="email" id="registerEmail" name="registerEmail" class="form-control" />
                            <label class="form-label" for="registerEmail">Correo electrónico</label>
                                @error('registerEmail')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                        </div>
                        <!-- Password input -->
                        <div data-mdb-input-init class="mb-4">
                            <input type="password" id="registerPassword" name="registerPassword" class="form-control" />
                            <label class="form-label" for="registerPassword">Contraseña</label>
                            @error('registerPassword')
                                    <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Repeat Password input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="password" id="registerRepeatPassword" name="registerPassword_confirmation"
                                class="form-control" />
                            <label class="form-label" for="registerRepeatPassword">Repetir contraseña</label>
                                @error('registerRepeatPassword')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                        </div>
                        <!-- Checkbox -->
                        <div class="form-check d-flex justify-content-center mb-4">
                            <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck" checked
                                aria-describedby="registerCheckHelpText" />
                        </div>
                        <!-- Submit button -->
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init
                            class="btn btn-primary btn-block mb-3">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de MDB Bootstrap -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function (event) {
            console.log('Formulario enviado');
            console.log('Nombre:', document.getElementById('registerName').value);
            console.log('Usuario:', document.getElementById('registerUsertype').value);
            console.log('Email:', document.getElementById('registerEmail').value);
           
        });
    </script>
</body>

</html>