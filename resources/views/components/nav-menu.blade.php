{{-- filepath: resources/views/components/nav-menu.blade.php --}}
@props(['user', 'navClass' => '', 'emailClass' => ''])
<style>
body.dark-mode .nav {
    background: linear-gradient(90deg, #4a4a4a, #2c2c2c);
}
</style>
<section role="region" aria-label="Response form">
    <form class="printButtonClass" id="blackForm">
        @csrf
        <nav class="nav flex-column {{ $navClass }}" style="padding-top: 50px; height: 1000px; background: linear-gradient(90deg, #afc7ce, #4281a4);" id="navPrint">
            <div class="nav-header" style="display: flex; align-items: center; padding-top: 50px;">
                <li class="nav-item">
                    <a class="nav-link disabled enlaceSN {{ $emailClass }}" style="font-size: medium; color: white;" href="#">
                        <i class="fa-solid fa-user" style="color: white;"></i>&nbsp&nbsp{{ $user->email }}
                    </a>
                </li>
                <li style="list-style: none; margin-right: 20px;">
                    <a href="{{ route('login') }}" style="display:inline;">
                            <i class="fas fa-power-off" style="font-size: 24px; color:white;" name="cerrar_sesion"></i>
                    </a>
                </li>
            </div><br>
            <div>
                <ul style="list-style: none;">
                    <li class="nav-item">
                        <a class="nav-link active enlaceSN" aria-current="page" style="width: 200px;" href="{{ route('rules') }}" title="Reglamento deacuerdo al artículo 10 de PEDPD"><i class="fas fa-book"></i>&nbspReglamento</a>
                    </li>
                    {{-- @if($user->user_type === 'dictaminador')
                        <li class="nav-item">
                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('comision_dictaminadora') }}" title="Formato de Evaluación docente"><i class="fa-solid fa-align-justify"></i>&nbspEvaluación</a>
                        </li>
                    @endif --}}
                    @if($user->user_type != 'docente')
                        @if($user->user_type === '')
                        <li class="nav-item">
                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('resumen') }}" title="A ser llenado por la Comisión del PEDPD""><i class="fas fa-list"></i>&nbspResumen</a>
                        </li><br>
                        <li id="reportLink" class="nav-item d-none">
                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('perfil') }}"><i class="fas fa-chart-bar"></i>Mostrar
                                Reporte</a>
                        </li>
                        @else 
                        <li class="nav-item">
                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('resumen_comision') }}" title="A ser llenado por la Comisión del PEDPD""><i class="fas fa-list"></i>&nbspResumen</a>
                        </li>  
                        @endif   
                    @endif
                    <li class="nav-item">
                        @if($user->user_type === 'dictaminador')
                            <a class="nav-link active enlaceSN" style="width: 200px;"
                                href="{{ route('comision_dictaminadora') }}"><i class="fa-regular fa-folder-open"></i>&nbspBuscar evaluaciones</a>
                        @elseif($user->user_type === '')
                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('secretaria') }}"><i class="fa-regular fa-folder-open"></i>&nbspBuscar evaluaciones</a>
                        @endif
                    </li>
                </ul>
            </div>

            {{-- Slot para contenido adicional --}}
            {{ $slot }}
        </nav>
    </form>
</section>