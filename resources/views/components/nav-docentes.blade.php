{{-- filepath: resources/views/components/nav-menu.blade.php --}}
@props(['user', 'navClass' => '', 'emailClass' => ''])

@php
    use App\Models\DynamicForm;
    use Illuminate\Support\Str;
    $dynamicNavForms = DynamicForm::all();
@endphp

<section role="region" aria-label="Response form">
    <form class="printButtonClass">
        @csrf
        <nav class="nav flex-column {{ $navClass }}" id="main-nav"
            style="width: var(--nav-width); position: fixed; left: 0; top: 0; margin-left: 0; padding-top: 0.125rem; height: 100vh; overflow-y: auto; background: linear-gradient(90deg, #afc7ce, #4281a4);">
            <div class="nav-header" style="display: flex;padding-top: 2rem;justify-content: flex-start;align-content: flex-start;flex-direction: row-reverse;align-items: baseline;">
                <li style="list-style: none; margin-right: 20px;">
                    <a href="{{ route('login') }}" style="display:inline;padding-left:1rem;" title="cerrar_sesion">
                        <i class="fas fa-power-off" style="font-size: 20px; color:white;" name="cerrar_sesion"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled enlaceSN {{ $emailClass }}" style="font-size: large; color: white;padding-left: 1rem;"
                        href="#">
                        <i class="fa-solid fa-user" style="color: white;"></i>&nbsp&nbsp{{ $user->email }}
                    </a>
                </li>
            </div><br>
            <div>
                <ul style="list-style: none;" class="list-center">
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
                    {{-- Renderiza aquí los formularios dinámicos que no son de la sección 3 --}}
                    @foreach($dynamicNavForms as $form)
                        @if(!Str::startsWith($form->form_type, '3.'))
                            <li class="nav-item">
                                <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ route('dynamic.form.show', ['form_name' => $form->form_name]) }}" title="{{ $form->form_name }}">
                                    <i class="fa-solid fa-folder-open"></i>&nbsp;{{ $form->form_name }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            {{-- Slot para contenido adicional --}}
            {{ $slot }}
        </nav>
    </form>
</section>