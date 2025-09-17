{{-- filepath: resources/views/components/nav-menu.blade.php --}}
@props(['user', 'navClass' => '', 'emailClass' => ''])

<section role="region" aria-label="Response form">
    <form class="printButtonClass">
        @csrf
        <nav class="nav flex-column {{ $navClass }}"
            style="padding-top: 50px; height: 900px; background: linear-gradient(90deg, #afc7ce, #4281a4);"
            id="navPrint">
            <div class="nav-header" style="display: flex; align-items: center; padding-top: 50px;">
                <li class="nav-item">
                    <a class="nav-link disabled enlaceSN {{ $emailClass }}" style="font-size: large; color: white;padding-left: 50px;"
                        href="#">
                        <i class="fa-solid fa-user" style="color: white;"></i>&nbsp&nbsp{{ $user->email }}
                    </a>
                </li>
            <li style="list-style: none; margin-right: 20px;">
                <a href="{{ route('login') }}" style="display:inline;" title="cerrar_sesion">
                    <i class="fas fa-power-off" style="font-size: 20px; color:white;" name="cerrar_sesion"></i>
                </a>
            </li>
            </div><br>
            <div>
                <ul style="list-style: none;"">
                    <li class=" nav-item">
                    <a class="nav-link active enlaceSN" aria-current="page" style="width: 200px;"
                        href="{{ route('rules') }}" title="Reglamento deacuerdo al artículo 10 de PEDPD"><i
                            class="fas fa-book"></i>&nbspReglamento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ url('docencia') }}"
                            title="Actividades 3. Calidad en la docencia">
                            <i class="fas fa-chalkboard-teacher"></i>&nbspCalidad en la docencia
                        </a>
                    </li>
                    <!--Funcionalidad en caso de nuevos formularios
                    <li class="nav-item">
                        {{-- Incluir formularios dinamicos deacuerdo a aquellos creados por el user = '' de dynamic_forms.blade.php y redirigirlos a la ruta de otros_formularios.blade.php --}}
                        <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('otros_formularios') }}"
                            title="Otros formularios"><i class="fa-solid fa-folder-open"></i>&nbspOtros formularios</a>

                    </li>
                    -->
                </ul>
            </div>

            {{-- Slot para contenido adicional --}}
            {{ $slot }}
        </nav>
    </form>
</section>