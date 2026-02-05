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
        .bgComision { background-color: #ffcc6d !important; color: black; }
        .bgEvaluacion { background-color: #0b5967 !important; color: white; }
        .table-ajust { width: auto; }
        .score-header { font-weight: bold; }
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
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            {{-- 🟦 FILA 1: SUPER ENCABEZADOS --}}
                            <tr class="text-center table-secondary">
                                @foreach($groupOrder as $group)
                                    @php
                                        $cols = $orderedStructure->where('group', $group);
                                        if ($cols->isEmpty()) continue;

                                        $label = match ($group) {
                                            'actividad'  => $form->form_name,
                                            'evaluacion' => 'Puntaje a evaluar',
                                            'comision'   => 'Puntaje de la Comisión Dictaminadora',
                                            default      => ''
                                        };
                                    @endphp

                                    @if($group === 'observaciones')
                                        <th></th>
                                    @else
                                        <th colspan="{{ $cols->count() }}" class="fw-bold text-center bg-transparent">
                                            {{ $label }}
                                        </th>
                                    @endif
                                @endforeach
                            </tr>

                            {{-- 🟨 FILA 2: ENCABEZADOS FUNCIONALES --}}
                            <tr class="table-light text-center">
                                @foreach($orderedStructure as $column)
                                    @php 
                                        $group = $column['group'];
                                        $headerStyle = '';
                                        if ($group === 'evaluacion') {
                                            $headerStyle = 'background-color: #0b5967; color: white;';
                                        } elseif ($group === 'comision') {
                                            $headerStyle = 'background-color: #ffcc6d; color: black;';
                                        }
                                    @endphp

                                    @if(in_array($group, ['evaluacion', 'comision']))
                                        <th class="fw-bold" style="{{ $headerStyle }}">
                                            @php
                                                $total = 0;
                                                if(isset($renderData) && is_array($renderData)){
                                                    foreach($renderData as $r){
                                                        if(isset($r[$column['key']]) && is_numeric($r[$column['key']])) $total += $r[$column['key']];
                                                    }
                                                }
                                                if ($group === 'evaluacion') {
                                                    $total = min($total, $form->puntaje_maximo);
                                                }
                                            @endphp
                                            <span class="score-header">{{ $total }}</span>
                                        </th>
                                    @else
                                        <th>{{ $column['name'] }}</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($renderData as $rowIndex => $row)
                                <tr>
                                    @foreach($orderedStructure as $column)
                                        @php
                                            $key = $column['key'];
                                            $value = $row[$key] ?? '';
                                            $group = $column['group'];
                                            
                                            $isEvaluacion = $group === 'evaluacion';
                                            $isComision = $group === 'comision';
                                            $isObservaciones = $group === 'observaciones';
                                        @endphp
                                        <td class="text-center align-middle">
                                            @if($group === 'actividad')
                                                <span class="fw-bold">{{ $value }}</span>
                                            @else
                                                <input type="text" 
                                                    class="form-control form-control-sm text-center" 
                                                    style="background-color: transparent; border: none; {{ ($isEvaluacion || $isComision) ? 'font-weight: bold;' : '' }} {{ $isEvaluacion ? 'color: black;' : '' }}"
                                                    value="{{ $value }}" 
                                                    readonly>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $orderedStructure->count() }}" class="text-center py-3">No hay datos registrados.</td>
                                </tr>
                            @endforelse
                            
                            <tr>
                                <td colspan="{{ $orderedStructure->count() }}" style="border: none; padding-top: 1rem;">
                                    @if(!empty($form->acreditacion))
                                        <strong>Acreditación:</strong> {{ $form->acreditacion }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded border">
                    <div class="row">
                        <div class="col-md-6">
                            <strong> Puntaje Máximo:</strong> {{ $form->puntaje_maximo }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
