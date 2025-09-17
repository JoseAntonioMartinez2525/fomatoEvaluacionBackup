    <div class="relative min-h-screen flex flex-col items-center justify-center">
        @if (Route::has('login'))
            @if (Auth::check())
                <section role="region" aria-label="Response form">
                    <form class="printButtonClass">
                        @csrf
                    <nav class="nav flex-column" style="padding-top: 50px; height: 900px; background-color: #afc7ce;">
                        <div class="nav-header" style="display: flex; align-items: center; padding-top: 50px;">
                            <li class="nav-item">
                                <a class="nav-link disabled enlaceSN" style="font-size: medium; color: white;" href="#">
                                    <i class="fa-solid fa-user" style="color: white;"></i>&nbsp&nbsp&nbsp&nbsp{{ $user->email }}
                                </a>
                            </li>
                            <li style="list-style: none; margin-right: 20px;">
                                <a class="enlaceSN" title="cerrar_sesion" href="{{ route('login') }}">
                                    <i class="fas fa-power-off" style="font-size: 24px; color: white;"></i>
                                </a>
                            </li>
                            </div><br>
                            <div>
                                <ul style="list-style: none;"">
                                                <li class=" nav-item">
                                    <a class="nav-link active enlaceSN" aria-current="page" style="width: 200px;" href="{{ route('rules') }}"
                                        title="Reglamento deacuerdo al artículo 10 de PEDPD"><i class="fas fa-book"></i>&nbspReglamento</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('resumen') }}"
                                            title="A ser llenado por la Comisión del PEDPD""><i class=" fas fa-list"></i>&nbspResumen</a>
                                    </li><br>
                                    <li id="reportLink" class="nav-item d-none">
                                        <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('perfil') }}"><i
                                                class="fas fa-chart-bar"></i>Mostrar
                                            Reporte</a>
                                    </li>
                                    <li class="nav-item">
                                        @if($user->user_type === 'dictaminador')
                                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('comision_dictaminadora') }}"><i
                                                    class="fa-regular fa-folder-open"></i>&nbspSelección de Formatos</a>
                                        @else
                                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('secretaria') }}"><i
                                                    class="fa-regular fa-folder-open"></i>&nbspSelección de
                                                Formatos</a>
                                        @endif
                                    </li>
                                    <!-- Funcionalidad en caso de nuevos formularios
                                    @if(Auth::user()->user_type === '')
                                    <li class="nav-item">
                                        <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('dynamic_forms') }}">Añadir nuevo formulario</a>
                                    </li>
                                    @endif
                                    -->
                        </nav>
                    </form>
                </section>
            @endif
        @endif
    </div>
    <x-general-header />