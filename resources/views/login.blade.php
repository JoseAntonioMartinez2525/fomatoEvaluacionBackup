@php
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
@endphp
<!DOCTYPE html>
<!--
Nombre del programador: José Antonio Martínez del Toro
Objetivo: Implementación frontend de la gestion de usuarios
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
    @keyframes onAutoFillStart {}
input:-webkit-autofill {
    animation-name: onAutoFillStart;
    animation-duration: 0.01s;
    animation-iteration-count: 1;
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

                    <!-- Pills navs -->
                    <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab-login" data-mdb-pill-init href="#pills-login" role="tab"
                                aria-controls="pills-login" aria-selected="true">Login</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-register" data-mdb-pill-init href="{{ route('/formato-evaluacion_bk2/register') }}" role="tab"
                                aria-controls="pills-register" aria-selected="false">Registro</a>

                        </li>
                    </ul>
                    <!-- Pills navs -->

                    <!-- Pills content -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-login" role="tabpanel"
                            aria-labelledby="tab-login">
                            <!-- Formulario de Login -->
                            <center>
                                <h3 class="mb-3">Login</h3>

                                <form method="POST" action="{{ route('/formato-evaluacion_bk2/login.post') }}" id="formAuthentication"
                                    class="mb-3">
                                    @csrf
                                    <div class="mb-2">
                                        <label for="email" class="form-label">Correo electrónico</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="Ingrese su correo electrónico" value="{{ old('email') }}"
                                            autofocus>
                                    </div>
                                    <input type="hidden" id="no_password_required" name="no_password_required" value="false">
                                    <div class="mb-3 form-password-toggle" id="password-group">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="password">Contraseña</label>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password" value="{{ old('password') }}" />
                                            <span class="input-group-text cursor-pointer"><i
                                                    class="ti ti-eye-off"></i></span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember-me">
                                            <label class="form-check-label" for="remember-me">
                                                Recordar
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary d-grid w-100">Login</button>
                                </form>
                            </center>
                        </div>
                        <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                            <form id="registerForm" href="{{route('register') }}">
                            @csrf
                            </form>
                        </div>
                    </div>
                    <!-- Pills content -->
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de MDB Bootstrap -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>
    <script>
    function checkAllowedEmail() {
        const allowedEmails = [
            'joma_18@alu.uabcs.mx',
            'oa.campillo@uabcs.mx',
            'rluna@uabcs.mx',
            'v.andrade@uabcs.mx'
        ];
        const emailInput = document.getElementById('email');
        if (!emailInput) return;
        const email = emailInput.value.trim().toLowerCase();
        if (allowedEmails.includes(email)) {
            document.getElementById('no_password_required').value = 'true';
            document.getElementById('password-group').style.display = 'none';
        } else {
            document.getElementById('no_password_required').value = 'false';
            document.getElementById('password-group').style.display = 'block';
        }
    }

    // Detectar cambios manuales y por autocompletado
    document.getElementById('email').addEventListener('input', checkAllowedEmail);
    document.getElementById('email').addEventListener('change', checkAllowedEmail);
    window.addEventListener('DOMContentLoaded', function () {
        checkAllowedEmail();
        // Esperar un poco por si el navegador autocompleta
        setTimeout(checkAllowedEmail, 500);
    });

    // Opcional: detectar autocompletado con CSS animation (hack avanzado)
    document.getElementById('email').addEventListener('animationstart', function (e) {
        if (e.animationName === 'onAutoFillStart') {
            checkAllowedEmail();
        }
    });
    </script>
</body>

</html>