<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ConsolidatedResponseController extends Controller
{
    public function showResumen()
    {
        // Recuperar los datos de comisiones
        $consolidatedResponses = DB::table('consolidated_responses')->get();
        
        // Calcular subtotales para las secciones
        $subtotal3_1To3_8_1 = $consolidatedResponses->reduce(function ($carry, $response) {
            return $carry + $response->actv3Comision + $response->comision3_2 + $response->comision3_3 + $response->comision3_4 + $response->comision3_5 + $response->comision3_6 + $response->comision3_7 + $response->comision3_8 + $response->comision3_8_1;
        }, 0);

        $subtotal3_9To3_11 = $consolidatedResponses->reduce(function ($carry, $response) {
            return $carry + $response->comision3_9 + $response->comision3_10 + $response->comision3_11;
        }, 0);

        $subtotal3_12To3_16 = $consolidatedResponses->reduce(function ($carry, $response) {
            return $carry + $response->comision3_12 + $response->comision3_13 + $response->comision3_14 + $response->comision3_15 + $response->comision3_16;
        }, 0);

        $subtotal3_17To3_19 = $consolidatedResponses->reduce(function ($carry, $response) {
            return $carry + $response->comision3_17 + $response->comision3_18 + $response->comision3_19;
        }, 0);

        $total = min(
            $subtotal3_1To3_8_1 + $subtotal3_9To3_11 + $subtotal3_12To3_16 + $subtotal3_17To3_19, 
            700
        );

        $totalComision1 = $consolidatedResponses->first()->comision1 ?? 0;
        $totalComision2 = $consolidatedResponses->first()->actv2Comision ?? 0;
        $totalComision3 = $total; // Calculado anteriormente

        $totalComisionRepetido = min($totalComision1 + $totalComision2 + $totalComision3,1000);

        // Evaluar la calidad mínima y el total
        $minimaCalidad = $this->evaluarCalidad($total);
        $minimaTotal = $this->evaluarTotal($totalComisionRepetido);

        // Estructurar los datos en secciones
        $sections = [
            'data' => [
                // Datos de las secciones y subtotales
                ['label' => '1. Permanencia en las actividades de la docencia', 'value' => 100, 'comision' => $consolidatedResponses->first()->comision1 ?? 0],
                ['label' => '1.1 Años de experiencia docente en la institución', 'value' => 100, 'comision' => $consolidatedResponses->first()->comision1 ?? 0],
                ['label' => '2. Dedicación en el desempeño docente', 'value' => 200, 'comision' => $consolidatedResponses->first()->actv2Comision ?? 0],
                ['label' => '2.1 Carga de trabajo docente frente a grupo', 'value' => 200, 'comision' => $consolidatedResponses->first()->actv2Comision ?? 0],
                ['label' => '3. Calidad en la docencia', 'value' => 60, 'comision' => $total],
                // Datos de las secciones 3.1 a 3.8
                ['label' => '3.1 Participación en actividades de diseño curricular', 'value' => 60, 'comision' => $consolidatedResponses->first()->actv3Comision ?? 0],
                ['label' => '3.2 Calidad del desempeño docente evaluada por los estudiantes', 'value' => 50, 'comision' => $consolidatedResponses->first()->comision3_2 ?? 0],
                ['label' => '3.3 Publicaciones relacionadas con la docencia', 'value' => 100, 'comision' => $consolidatedResponses->first()->comision3_3 ?? 0],
                ['label' => '3.4 Distinciones académicas recibidas por el docente', 'value' => 60, 'comision' => $consolidatedResponses->first()->comision3_4 ?? 0],
                ['label' => '3.5 Asistencia, puntualidad y permanencia en el desempeño docente, evaluada por el JD y por CAAC', 'value' => 75, 'comision' => $consolidatedResponses->first()->comision3_5 ?? 0],
                ['label' => '3.6 Capacitación y actualización pedagógica recibida', 'value' => 40, 'comision' => $consolidatedResponses->first()->comision3_6 ?? 0],
                ['label' => '3.7 Cursos de actualización disciplinaria recibidos dentro de su área de conocimiento', 'value' => 40, 'comision' => $consolidatedResponses->first()->comision3_7 ?? 0],
                ['label' => '3.8 Impartición de cursos, diplomados, seminarios, talleres extracurriculares, de educación, continua o de formación y capacitación docente', 'value' => 40, 'comision' => $consolidatedResponses->first()->comision3_8 ?? 0],
                ['label' => '3.8_1 RSU', 'value' => 40, 'comision' => $consolidatedResponses->first()->comision3_8_1 ?? 0],
                ['label' => 'Subtotal', 'value' => '', 'comision' => $subtotal3_1To3_8_1, 'is_subtotal' => true],
                // Datos de las secciones 3.9 a 3.11
                ['label' => '3.9 Trabajos dirigidos para la titulación de estudiantes', 'value' => 200, 'comision' => $consolidatedResponses->first()->comision3_9 ?? 0],
                ['label' => '3.10 Tutorías a estudiantes', 'value' => 115, 'comision' => $consolidatedResponses->first()->comision3_10 ?? 0],
                ['label' => '3.11 Asesoría a estudiantes', 'value' => 95, 'comision' => $consolidatedResponses->first()->comision3_11 ?? 0],
                ['label' => 'Subtotal', 'value' => '', 'comision' => $subtotal3_9To3_11, 'is_subtotal' => true],
                // Datos de las secciones 3.12 a 3.16
                ['label' => '3.12 Publicaciones de investigación relacionadas con el contenido de los PE que imparte el docente', 'value' => 150, 'comision' => $consolidatedResponses->first()->comision3_12 ?? 0],
                ['label' => '3.13 Proyectos académicos de investigación', 'value' => 130, 'comision' => $consolidatedResponses->first()->comision3_13 ?? 0],
                ['label' => '3.14 Participación como ponente en congresos o eventos académicos del área de conocimiento o afines del docente', 'value' => 40, 'comision' => $consolidatedResponses->first()->comision3_14 ?? 0],
                ['label' => '3.15 Registro de patentes y productos de investigación tecnológica y educativa', 'value' => 60, 'comision' => $consolidatedResponses->first()->comision3_15 ?? 0],
                ['label' => '3.16 Actividades de arbitraje, revisión, corrección y edición', 'value' => 30, 'comision' => $consolidatedResponses->first()->comision3_16 ?? 0],
                ['label' => 'Subtotal', 'value' => '', 'comision' => $subtotal3_12To3_16, 'is_subtotal' => true],
                // Datos de las secciones 3.17 a 3.19
                ['label' => '3.17 Proyectos académicos de extensión y difusión', 'value' => 50, 'comision' => $consolidatedResponses->first()->comision3_17 ?? 0],
                ['label' => '3.18 Organización de congresos o eventos institucionales del área de conocimiento del Docente', 'value' => 40, 'comision' => $consolidatedResponses->first()->comision3_18 ?? 0],
                ['label' => '3.19 Participación en cuerpos colegiados', 'value' => 40, 'comision' => $consolidatedResponses->first()->comision3_19 ?? 0],
                ['label' => 'Subtotal', 'value' => '', 'comision' => $subtotal3_17To3_19, 'is_subtotal' => true],
            ],
        ];

        if ($consolidatedResponses->isEmpty()) {
            // Si no hay datos, maneja este caso
            return view('form4', [
                'error' => 'No se encontraron respuestas consolidadas.'
            ]);
        }

        $totalComision1 = $consolidatedResponses->first()->comision1 ?? 0;

        return view('resumen_comision', [
            'sections' => $sections,
            'subtotal3_1To3_8_1' => $subtotal3_1To3_8_1,
            'subtotal3_9To3_11' => $subtotal3_9To3_11,
            'subtotal3_12To3_16' => $subtotal3_12To3_16,
            'subtotal3_17To3_19' => $subtotal3_17To3_19,
            'totalComision1' => $totalComision1,
            'totalComision2'=> $totalComision2,
            'total' => $total,
            'minimaCalidad' => $minimaCalidad,
            'minimaTotal' => $minimaTotal,
            'totalComisionRepetido' => $totalComisionRepetido,
        ]);

        
    }

    
    // Función para evaluar la calidad mínima
    private function evaluarCalidad($total)
    {
        switch (true) {
            case ($total >= 210 && $total <= 264.99):
                return 'I';
            case ($total >= 265 && $total <= 319.99):
                return 'II';
            case ($total >= 320 && $total <= 374.99):
                return 'III';
            case ($total >= 375 && $total <= 429.99):
                return 'IV';
            case ($total >= 430 && $total <= 484.99):
                return 'V';
            case ($total >= 485 && $total <= 539.99):
                return 'VI';
            case ($total >= 540 && $total <= 594.99):
                return 'VII';
            case ($total >= 595 && $total <= 649.99):
                return 'VIII';
            case ($total >= 650 && $total <= 700):
                return 'IX';
            default:
                return 'FALSE';
        }
    }

    // Función para evaluar el total
    private function evaluarTotal($totalComisionRepetido)
    {
        switch (true) {
            case ($totalComisionRepetido >= 301 && $totalComisionRepetido <= 377.99):
                return 'I';
            case ($totalComisionRepetido >= 378 && $totalComisionRepetido <= 455.99):
                return 'II';
            case ($totalComisionRepetido >= 456 && $totalComisionRepetido <= 533.99):
                return 'III';
            case ($totalComisionRepetido >= 534 && $totalComisionRepetido <= 611.99):
                return 'IV';
            case ($totalComisionRepetido >= 612 && $totalComisionRepetido <= 689.99):
                return 'V';
            case ($totalComisionRepetido >= 690 && $totalComisionRepetido <= 767.99):
                return 'VI';
            case ($totalComisionRepetido >= 768 && $totalComisionRepetido <= 845.99):
                return 'VII';
            case ($totalComisionRepetido >= 846 && $totalComisionRepetido <= 923.99):
                return 'VIII';
            case ($totalComisionRepetido >= 924 && $totalComisionRepetido <= 1000):
                return 'IX';
            default:
                return 'FALSE';
        }
    }

    public function storeConsolidatedData($request)
    {
        // Validación inicial para datos generales
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'comision1' => 'numeric',
            'comision1Repetido' => 'numeric',
            'actv2Comision' => 'numeric',
            'actv2ComisionRepetido' => 'numeric',
            'actv3Comision' => 'numeric',
            'comision3_2' => 'numeric',
            'comision3_3' => 'numeric',
            'comision3_4' => 'numeric',
            'comision3_5' => 'numeric',
            'comision3_6' => 'numeric',
            'comision3_7' => 'numeric',
            'comision3_8' => 'numeric',
            'comision3_8_1' => 'numeric',
            'comision3_9' => 'numeric',
            'comision3_10' => 'numeric',
            'comision3_11' => 'numeric',
            'comision3_12' => 'numeric',
            'comision3_13' => 'numeric',
            'comision3_14' => 'numeric',
            'comision3_15' => 'numeric',
            'comision3_16' => 'numeric',
            'comision3_17' => 'numeric',
            'comision3_18' => 'numeric',
            'comision3_19' => 'numeric',
            'totalLogrado' => 'numeric',
            '1. Permanencia en las actividades de la docencia' => 'numeric',
            '2. Dedicación en el desempeño docente' => 'numeric',
            '3. Calidad en la docencia' => 'numeric'
        ]);

        // Calcular subtotales
        $subtotal3_1To3_8_1 = $request->only(['actv3Comision', 'comision3_2', 'comision3_3', 'comision3_4', 'comision3_5', 'comision3_6', 'comision3_7', 'comision3_8', 'comision3_8_1'])
            ->sum();
        $subtotal3_9To3_11 = $request->only(['comision3_9', 'comision3_10', 'comision3_11'])
            ->sum();
        $subtotal3_12To3_16 = $request->only(['comision3_12', 'comision3_13', 'comision3_14', 'comision3_15', 'comision3_16'])
            ->sum();
        $subtotal3_17To3_19 = $request->only(['comision3_17', 'comision3_18', 'comision3_19'])
            ->sum();

        // Calcular el total y agregarlo a los datos validados
        $total = min(
            $subtotal3_1To3_8_1 + $subtotal3_9To3_11 + $subtotal3_12To3_16 + $subtotal3_17To3_19,
            700
        );
        $validatedData['subtotal3_1To3_8_1'] = $subtotal3_1To3_8_1;
        $validatedData['subtotal3_9To3_11'] = $subtotal3_9To3_11;
        $validatedData['subtotal3_12To3_16'] = $subtotal3_12To3_16;
        $validatedData['subtotal3_17To3_19'] = $subtotal3_17To3_19;
        $validatedData['total'] = $total;

        // Guardar los datos en la base de datos
        DB::table('consolidated_responses')->updateOrInsert(
            ['user_id' => $validatedData['user_id']],
            $validatedData
        );

        return response()->json(['message' => 'Datos consolidados guardados exitosamente']);
    }


}

