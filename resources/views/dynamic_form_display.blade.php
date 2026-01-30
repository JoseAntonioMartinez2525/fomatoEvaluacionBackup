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
                    <form id="dynamic-form-{{ $form->id }}" method="POST" onsubmit="event.preventDefault(); submitDynamicForm('{{ url('/dynamic-forms/save-response') }}', 'dynamic-form-{{ $form->id }}');">
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
                                @foreach($renderData as $rowIndex => $row)
                                    <tr>
                                        @foreach($form->form_structure as $column)
                                            @php
                                                $key = $column['key'];
                                                // 'actividad' should be read-only text, 'puntaje...' and 'observaciones' have specific logic
                                                $isActividad = ($index === 0);
                                                $isSubtotal = ($index === count($form->form_structure) - 3);
                                                $isCommission = ($index === count($form->form_structure) - 2);
                                                $isObservaciones = ($index === count($form->form_structure) - 1);
                                                $isEditable = !$isActividad && !$isSubtotal && !$isCommission;
                                            @endphp
                                            <td>
                                                @if($isActividad)
                                                    <span class="fw-bold">{{ $row[$key] ?? '' }}</span>
                                                    <input type="hidden" name="data[{{ $loop->parent->index }}][{{ $key }}]" value="{{ $row[$key] ?? '' }}">
                                                @elseif($isEditable)
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
    <script>
        function submitDynamicForm(url, formId) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);
            
            // Construct JSON payload from FormData
            const structuredData = {
                user_id: formData.get('user_id'),
                email: formData.get('email'),
                user_type: formData.get('user_type'),
                form_id: formData.get('form_id'),
                data: []
            };
            
            // Extract table data
            const rows = form.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                const rowData = {};
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    const name = input.name; 
                    // Regex to extract key from name="data[rowIndex][key]"
                    const match = name.match(/\[(.+?)\]$/);

                    if (match) {
                        const key = match[1];
                        rowData[key] = input.value;
                    }
                });
                structuredData.data.push(rowData);
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(structuredData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Formulario guardado correctamente');
                } else {
                    alert('❌ Error: ' + (data.message || 'Error al enviar'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Error de conexión');
            });
        }
    </script>
</body>
</html>
