@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
@endphp
<!DOCTYPE html>
<html lang="{{ $newLocale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle Formulario Dinámico</title>
    <x-head-resources />
    <style>
        .bgComision { background-color: #ffcc6d !important; color: black; }
        .bgEvaluacion { background-color: #0b5967 !important; color: white; }
        .table-ajust { width: auto; }
        .score-header { font-weight: bold; }
    </style>
    @php
        $canEdit = in_array(Auth::user()->user_type, ['dictaminador', 'secretaria']);
        // Identificar la clave de la columna de evaluación para usarla como referencia
        $evalKey = $orderedStructure->firstWhere('group', 'evaluacion')['key'] ?? null;
    @endphp
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
                <form id="commissionForm">
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
                                                if (isset($renderData) && is_array($renderData)) {
                                                    foreach ($renderData as $idx => $r) {
                                                        $val = 0;
                                                        if ($group === 'comision') {
                                                            // Para comisión, sumar el valor guardado o el sugerido (evaluación)
                                                            $cData = $commissionData[$idx] ?? null;
                                                            $val = $cData ? $cData->puntaje_comision : ($r[$evalKey] ?? 0);
                                                        } else {
                                                            $val = $r[$column['key']] ?? 0;
                                                        }
                                                        if (is_numeric($val)) $total += $val;
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
                                @php
                                    $comData = $commissionData[$rowIndex] ?? null;
                                @endphp
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
                                            @elseif($isComision && $canEdit)
                                                @php
                                                    // Valor por defecto: si existe en BD comisión, usarlo; si no, usar el puntaje del docente
                                                    $defaultVal = $comData ? $comData->puntaje_comision : ($row[$evalKey] ?? 0);
                                                @endphp
                                                <input type="hidden" name="rows[{{ $rowIndex }}][row_identifier]" value="{{ $rowIndex }}">
                                                <input type="number" 
                                                    step="0.01"
                                                    name="rows[{{ $rowIndex }}][puntaje_comision]"
                                                    class="form-control form-control-sm text-center fw-bold" 
                                                    style="background-color: transparent; border: 1px solid #ced4da;"
                                                    value="{{ $defaultVal }}">
                                            @elseif($isObservaciones && $canEdit)
                                                @php
                                                    $obsVal = $comData ? $comData->observaciones : ($value ?? '');
                                                @endphp
                                                <input type="text" 
                                                    name="rows[{{ $rowIndex }}][observaciones]"
                                                    class="form-control form-control-sm" 
                                                    value="{{ $obsVal }}">
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
                </form>
                
                <div class="mt-4 p-3 bg-light rounded border">
                    <div class="row">
                        <div class="col-md-6">
                            <strong> Puntaje Máximo:</strong> {{ $form->puntaje_maximo }}
                        </div>
                        <div class="col-md-6 text-end">
                            @if($canEdit)
                                <button type="button" onclick="submitCommission()" class="btn btn-success">
                                    <i class="fa-solid fa-save"></i> Guardar Evaluación
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function submitCommission() {
        const form = document.getElementById('commissionForm');
        const formData = new FormData(form);
        
        // Agregar datos adicionales requeridos por el controlador
        const payload = {
            user_id: '{{ Auth::id() }}',
            email: '{{ $docente->email }}',
            user_type: '{{ Auth::user()->user_type }}',
            rows: []
        };

        // Convertir FormData a estructura JSON esperada
        // Iteramos sobre los inputs del formulario para construir el array 'rows'
        // Nota: Asumimos que los inputs tienen nombres como rows[index][campo]
        const rowsObj = {};
        for (const [key, value] of formData.entries()) {
            const match = key.match(/rows\[(\d+)\]\[(\w+)\]/);
            if (match) {
                const index = match[1];
                const field = match[2];
                if (!rowsObj[index]) rowsObj[index] = {};
                rowsObj[index][field] = value;
            }
        }
        payload.rows = Object.values(rowsObj);

        fetch('/update-commission-data/{{ $form->id }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Evaluación guardada correctamente.');
                location.reload();
            } else {
                alert('Error al guardar: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión al guardar.');
        });
    }
    </script>
</body>
</html>
