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
    <title>{{ $form->form_name }}</title>

    <x-head-resources />
    <style>
        .table-responsive {
            margin-top: 20px;
        }
        .main-content {
            margin-left: 330px; /* Ajusta este valor para que coincida con el ancho de tu menú de navegación */
            padding: 20px;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <x-general-header />
    <div class="bg-gray-50 text-black/50">
        <div class="relative min-h-screen flex">
            @if (Route::has('login') && Auth::check())
                <x-nav-docentes :user="Auth::user()" />
            @endif

            <main class="main-content grow">
                <header>
                    <h3>{{ $form->form_name }}</h3>
                    <h4>Puntaje máximo: <label class="bg-black text-white px-4">{{ $form->puntaje_maximo }}</label></h4>
                </header>

                <div class="table-responsive">
                    <form id="dynamic-form-{{ $form->id }}" method="POST" onsubmit="event.preventDefault();">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                        <input type="hidden" name="user_type" value="{{ auth()->user()->user_type }}">
                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    @foreach($form->form_structure as $column)
                                        <th>{{ $column['name'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($form->form_data as $row)
                                    <tr>
                                        @foreach($form->form_structure as $column)
                                            @php
                                                $key = $column['key'];
                                                $isEditable = !in_array($key, ['puntaje_a_evaluar', 'puntaje_de_la_comision_dictaminadora', 'observaciones']);
                                            @endphp
                                            <td>
                                                @if($isEditable)
                                                    <input type="text" class="form-control" name="data[{{ $loop->parent->index }}][{{ $key }}]" value="{{ $row[$key] ?? '' }}">
                                                @else
                                                    <span>{{ $row[$key] ?? '' }}</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p class="mt-3"><strong>Acreditación:</strong> {{ $form->acreditacion }}</p>
                        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
