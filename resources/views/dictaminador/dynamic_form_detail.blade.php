@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
@endphp
<!DOCTYPE html>
<html lang="{{ $newLocale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Formulario Dinámico</title>
    <x-head-resources />
    <style>
        .bgComision { background-color: #fff3cd; }
        .bgEvaluacion { background-color: #e2e3e5; }
    </style>
</head>
<body class="font-sans antialiased">
    <x-general-header />
    
    <div class="container mt-5" style="max-width: 95%;">
        <div class="mb-4">
            {{-- Asumiendo que existe una ruta para volver al listado del docente --}}
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0 h5">{{ $form->form_name }}</h3>
                <small>Docente: {{ $docente->name }} ({{ $docente->email }})</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr class="text-center table-secondary">
                                @foreach($groupOrder as $group)
                                    @php
                                        $cols = $orderedStructure->where('group', $group);
                                        if ($cols->isEmpty()) continue;
                                        $label = match ($group) {
                                            'actividad'  => 'Actividad',
                                            'evaluacion' => 'Puntaje a evaluar',
                                            'comision'   => 'Puntaje de la Comisión Dictaminadora',
                                            'observaciones' => 'Observaciones',
                                            default      => ''
                                        };
                                    @endphp
                                    <th colspan="{{ $cols->count() }}">{{ $label }}</th>
                                @endforeach
                            </tr>
                            <tr class="text-center align-middle">
                                @foreach($orderedStructure as $column)
                                    <th>{{ $column['name'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($renderData as $row)
                                <tr>
                                    @foreach($orderedStructure as $column)
                                        @php
                                            $key = $column['key'];
                                            $value = $row[$key] ?? '';
                                        @endphp
                                        <td class="text-center align-middle">{{ $value }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $orderedStructure->count() }}" class="text-center py-3">No hay datos registrados en este formulario.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded border">
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fa-solid fa-star"></i> Puntaje Máximo:</strong> {{ $form->puntaje_maximo }}
                        </div>
                        <div class="col-md-6">
                            @if($form->acreditacion)
                                <strong><i class="fa-solid fa-certificate"></i> Acreditación:</strong> {{ $form->acreditacion }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
