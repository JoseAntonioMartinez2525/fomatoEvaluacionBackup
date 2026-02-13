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
        <nav class="nav flex-column {{ $navClass }}" style="padding-top: 50px; height: 1200px; background: linear-gradient(90deg, #afc7ce, #4281a4);" id="navPrint">
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
                    @if($user->user_type != 'docente')
                        @if($user->user_type ==='controlador')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('secretaria') }}"><i class="fa-solid fa-table-columns"></i>  Dashboard </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('tiempo') }}" class="nav-link" title="Otorgar Prórroga de tiempo"><i class='fas fa-clock'></i> Agregar Tiempo</a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('fechas') }}" class="nav-link" title="fechas de evaluacion"><i class='fas fa-calendar'></i> Establecer fechas</a>
                        </li>
                        @endif   
                    @endif
                    <li class="nav-item">
                        <a class="nav-link active enlaceSN" aria-current="page" style="width: 200px;" href="{{ route('rules') }}" title="Reglamento deacuerdo al artículo 10 de PEDPD"><i class="fas fa-book"></i>&nbspReglamento</a>
                    </li>
                    {{-- @if($user->user_type === 'dictaminador')
                        <li class="nav-item">
                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('comision_dictaminadora') }}" title="Formato de Evaluación docente"><i class="fa-solid fa-align-justify"></i>&nbspEvaluación</a>
                        </li>
                    @endif --}}
                    <li class="nav-item">
                        {{-- @if($user->user_type === 'dictaminador')
                            <a class="nav-link active enlaceSN" style="width: 200px;"
                                href="{{ route('comision_dictaminadora') }}"><i class="fa-regular fa-folder-open"></i>&nbspBuscar evaluaciones</a> --}}
                        @if($user->user_type ==='controlador')
                            <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('docente.forms.index') }}"><i class="fa-regular fa-folder-open"></i>&nbspBuscar evaluaciones</a>
                        @endif
                    </li>
                    @if($user->user_type === 'dictaminador')
                    <li class="nav-item">
                        <a class="nav-link active enlaceSN" style="width: 200px;" 
                           href="{{ route('docente.forms.index') }}">
                            <i class="fas fa-clipboard-list"></i>&nbspFormularios completados
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- Slot para contenido adicional --}}
            {{ $slot }}
        </nav>
    </form>
</section>