<section role="region" aria-label="Response form">
    <form>
        @csrf
        <nav class="nav flex-column" style="padding-top: 50px; height: 900px; background-color: #afc7ce;">
            <div class="nav-header" style="display: flex; align-items: center; padding-top: 50px;">
                <li class="nav-item">
                    <a class="nav-link disabled enlaceSN" style="font-size: medium;" href="#">
                        <i class="fa-solid fa-user"></i>{{ Auth::user()->email }}
                    </a>
                </li>
                <li style="list-style: none; margin-right: 20px;">
                    <a class="enlaceSN" href="{{ route('login') }}">
                        <i class="fas fa-power-off" style="font-size: 24px;" name="cerrar_sesion"></i>
                    </a>
                </li>
            </div>
            <li class="nav-item">
                <a class="nav-link active enlaceSN" style="width: 200px;" href="{{route('rules')}}">Artículo 10
                    REGLAMENTO
                    PEDPD</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active enlaceSN" style="width: 200px;" href="{{route('resumen_comision')}}">Resumen
                    (A ser
                    llenado por la Comisión del PEDPD)</a>
            </li>
            <!-- Funcionalidad en caso de nuevos formularios
            <li class="nav-item">
                <a class="nav-link active enlaceSN" style="width: 200px;" href="{{route('dynamic_forms')}}">Ingresar
                    Nuevo formulario</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active enlaceSN" style="width: 200px;"
                    href="{{route('edit_delete_form')}}">Editar/Eliminar formulario</a>
            </li>
    -->
            <br>

        </nav>
    </form>
</section>