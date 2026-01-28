<?php

namespace App\Http\Controllers;

use Log;
use App\Models\EvaluatorSignature;
use App\Models\UsersResponseForm1;
use App\Models\UsersResponseForm2;
use App\Models\UsersResponseForm2_2;
use App\Models\UsersResponseForm3_1;
use App\Models\UsersResponseForm3_10;
use App\Models\UsersResponseForm3_11;
use App\Models\UsersResponseForm3_12;
use App\Models\UsersResponseForm3_13;
use App\Models\UsersResponseForm3_14;
use App\Models\UsersResponseForm3_15;
use App\Models\UsersResponseForm3_16;
use App\Models\UsersResponseForm3_17;
use App\Models\UsersResponseForm3_18;
use App\Models\UsersResponseForm3_19;
use App\Models\UsersResponseForm3_2;
use App\Models\UsersResponseForm3_3;
use App\Models\UsersResponseForm3_4;
use App\Models\UsersResponseForm3_5;
use App\Models\UsersResponseForm3_6;
use App\Models\UsersResponseForm3_7;
use App\Models\UsersResponseForm3_8;
use App\Models\UsersResponseForm3_8_1;
use App\Models\UsersResponseForm3_9;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Dompdf\Exception;
use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de tener el modelo User
use App\Models\UserTimer;
use Barryvdh\DomPDF\Facade\Pdf; // Importar DomPDF
use Svg\Document;
use Svg\Nodes\EmbeddedImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
class DictaminatorController extends Controller
{
public function adminResetTimer(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'segundosExtra' => 'required|integer|min:1',
    ]);

    $user = \App\Models\User::where('email', $request->email)->firstOrFail();

    // Crear o obtener el timer
    $timer = \App\Models\UserTimer::firstOrCreate(
        ['user_id' => $user->id],
        ['tiempo_restante' => 0, 'expirado' => false]
    );

    // Si el timer está expirado o en 0, reiniciar desde los segundos extra
    if ($timer->expirado || $timer->tiempo_restante <= 0) {
        $timer->tiempo_restante = $request->segundosExtra;
    } else {
        // Si aún tenía tiempo, solo sumar
        $timer->tiempo_restante += $request->segundosExtra;
    }

    $timer->expirado = false;
    $timer->save();




    return response()->json([
        'message' => 'Timer prorrogado correctamente',
        'nuevoTiempo' => $timer->tiempo_restante
    ]);

        
}

    
    public function getDocentes(Request $request)
{
    $search = $request->query('search');

    $query = User::where('user_type', 'docente');

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $docentes = $query->get(['name as nombre', 'email']);

    return response()->json($docentes);
}

    public function getDocentesByDictaminador(Request $request)
    {
        $dictaminadorId = $request->input('dictaminador_id') ?? \Auth::id();
        $includeOthers = $request->input('include_others') === 'true';

        if ($includeOthers) {
            // Traer TODOS los docentes registrados en el sistema (tabla users)
            $docentes = \App\Models\User::where('user_type', 'docente')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();
            return response()->json($docentes);
        }

        // Consultar la tabla pivote (historial de evaluaciones) solo para el dictaminador actual
        $query = DB::table('dictaminador_docente')
            ->join('users', 'dictaminador_docente.docente_id', '=', 'users.id')
            ->where('dictaminador_docente.dictaminador_id', $dictaminadorId)
            ->select('users.id', 'users.name', 'users.email')
            ->distinct();

        return response()->json($query->get());
    }

    public function getDocenteData(Request $request)
    {

        Log::error('Mensaje de prueba de Log::error');
        
        $email = $request->query('email');
        $docente = User::where('email', $email)->first();

        if (!$docente) {
            return response()->json(['error' => 'Docente not found'], 404);
        }

        $formData1 = UsersResponseForm1::where('user_id', $docente->id)->first();

        $departamento = UsersResponseForm1::where('user_id', $docente->id)->first();
        $form2Data = UsersResponseForm2::where('user_id', $docente->id)->first();
        $form2_2Data = UsersResponseForm2_2::where('user_id', $docente->id)->first();
        $form3_1Data = UsersResponseForm3_1::where('user_id', $docente->id)->first();
        $form3_2Data = UsersResponseForm3_2::where('user_id', $docente->id)->first();
        $form3_3Data = UsersResponseForm3_3::where('user_id', $docente->id)->first();
        $form3_4Data = UsersResponseForm3_4::where('user_id', $docente->id)->first();
        $form3_5Data = UsersResponseForm3_5::where('user_id', $docente->id)->first();
        $form3_6Data = UsersResponseForm3_6::where('user_id', $docente->id)->first();
        $form3_7Data = UsersResponseForm3_7::where('user_id', $docente->id)->first();
        $form3_8Data = UsersResponseForm3_8::where('user_id', $docente->id)->first();
        $form3_8_1Data = UsersResponseForm3_8_1::where('user_id', $docente->id)->first();
        $form3_9Data = UsersResponseForm3_9::where('user_id', $docente->id)->first();
        $form3_10Data = UsersResponseForm3_10::where('user_id', $docente->id)->first();
        $form3_11Data = UsersResponseForm3_11::where('user_id', $docente->id)->first();
        $form3_12Data = UsersResponseForm3_12::where('user_id', $docente->id)->first();
        $form3_13Data = UsersResponseForm3_13::where('user_id', $docente->id)->first();
        $form3_14Data = UsersResponseForm3_14::where('user_id', $docente->id)->first();
        $form3_15Data = UsersResponseForm3_15::where('user_id', $docente->id)->first();
        $form3_16Data = UsersResponseForm3_16::where('user_id', $docente->id)->first();
        $form3_17Data = UsersResponseForm3_17::where('user_id', $docente->id)->first();
        $form3_18Data = UsersResponseForm3_18::where('user_id', $docente->id)->first();
        $form3_19Data = UsersResponseForm3_19::where('user_id', $docente->id)->first();

        // Return a structured response which includes both form data
        return response()->json([
            'docente' => [
                'id' => $docente->id,
                'email' => $docente->email,
                'convocatoria'=>$formData1?->convocatoria,
                'periodo'=>$formData1?->periodo,
                'nombre'=>$formData1?->nombre,
                'area'=>$formData1?->area,
                'departamento'=>$formData1?->departamento,
            ],
            'form1'=>$formData1,
            'form2' => $form2Data,    // existing fields can still be accessed
            'form2_2' => $form2_2Data,  // potentially useful for this view
            'form3_1' => $form3_1Data,
            'form3_2' => $form3_2Data,
            'form3_3' => $form3_3Data,
            'form3_4' => $form3_4Data,
            'form3_5' => $form3_5Data,
            'form3_6' => $form3_6Data,
            'form3_7' => $form3_7Data,
            'form3_8' => $form3_8Data,
            'form3_8_1' => $form3_8_1Data,
            'form3_9' => $form3_9Data,
            'form3_10' => $form3_10Data,
            'form3_11' => $form3_11Data,
            'form3_12' => $form3_12Data,
            'form3_13' => $form3_13Data,
            'form3_14' => $form3_14Data,
            'form3_15' => $form3_15Data,
            'form3_16' => $form3_16Data,
            'form3_17' => $form3_17Data,
            'form3_18' => $form3_18Data,
            'form3_19' => $form3_19Data,

        ]);
    }
    public function getAuthenticatedDocenteData(Request $request)
    {
        $docente = Auth::user();

        if (!$docente) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $formData1 = UsersResponseForm1::where('user_id', $docente->id)->first();
        $form2Data = UsersResponseForm2::where('user_id', $docente->id)->first();
        $form2_2Data = UsersResponseForm2_2::where('user_id', $docente->id)->first();
        $form3_1Data = UsersResponseForm3_1::where('user_id', $docente->id)->first();
        $form3_2Data = UsersResponseForm3_2::where('user_id', $docente->id)->first();
        $form3_3Data = UsersResponseForm3_3::where('user_id', $docente->id)->first();
        $form3_4Data = UsersResponseForm3_4::where('user_id', $docente->id)->first();
        $form3_5Data = UsersResponseForm3_5::where('user_id', $docente->id)->first();
        $form3_6Data = UsersResponseForm3_6::where('user_id', $docente->id)->first();
        $form3_7Data = UsersResponseForm3_7::where('user_id', $docente->id)->first();
        $form3_8Data = UsersResponseForm3_8::where('user_id', $docente->id)->first();
        $form3_8_1Data = UsersResponseForm3_8_1::where('user_id', $docente->id)->first();
        $form3_9Data = UsersResponseForm3_9::where('user_id', $docente->id)->first();
        $form3_10Data = UsersResponseForm3_10::where('user_id', $docente->id)->first();
        $form3_11Data = UsersResponseForm3_11::where('user_id', $docente->id)->first();
        $form3_12Data = UsersResponseForm3_12::where('user_id', $docente->id)->first();
        $form3_13Data = UsersResponseForm3_13::where('user_id', $docente->id)->first();
        $form3_14Data = UsersResponseForm3_14::where('user_id', $docente->id)->first();
        $form3_15Data = UsersResponseForm3_15::where('user_id', $docente->id)->first();
        $form3_16Data = UsersResponseForm3_16::where('user_id', $docente->id)->first();
        $form3_17Data = UsersResponseForm3_17::where('user_id', $docente->id)->first();
        $form3_18Data = UsersResponseForm3_18::where('user_id', $docente->id)->first();
        $form3_19Data = UsersResponseForm3_19::where('user_id', $docente->id)->first();

        return response()->json([
            'form1' => $formData1,
            'form2' => $form2Data,
            'form2_2' => $form2_2Data,
            'form3_1' => $form3_1Data, 'form3_2' => $form3_2Data, 'form3_3' => $form3_3Data,
            'form3_4' => $form3_4Data, 'form3_5' => $form3_5Data, 'form3_6' => $form3_6Data,
            'form3_7' => $form3_7Data, 'form3_8' => $form3_8Data, 'form3_8_1' => $form3_8_1Data,
            'form3_9' => $form3_9Data, 'form3_10' => $form3_10Data, 'form3_11' => $form3_11Data,
            'form3_12' => $form3_12Data, 'form3_13' => $form3_13Data, 'form3_14' => $form3_14Data,
            'form3_15' => $form3_15Data, 'form3_16' => $form3_16Data, 'form3_17' => $form3_17Data,
            'form3_18' => $form3_18Data, 'form3_19' => $form3_19Data,
        ]);
    }

    public function getUserId(Request $request)
    {
        $email = $request->query('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            return response()->json(['user_id' => $user->id]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function generarPDF(Request $request)
    {
        Log::info(['GENERAR PDF']);

        $email = $request->query('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // === LOGO ===
        $logoPath = public_path('logo_uabcs.png');
        if (!file_exists($logoPath)) {
            $logoPath = storage_path('logo_uabcs.png');
        }
        $logoImageContent = file_exists($logoPath) ? file_get_contents($logoPath) : '';
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoBase64 = $logoImageContent
            ? 'data:image/' . $logoType . ';base64,' . base64_encode($logoImageContent)
            : '';

        // === FORM 1 ===
        $form1 = UsersResponseForm1::where('user_id', $user->id)->first();
       $convocatoria = $form1->convocatoria ?? '';

        // === FORM 2 ===
        $form2 = UsersResponseForm2::where('user_id', $user->id)->first();
        $form2_2 = UsersResponseForm2_2::where('user_id', $user->id)->first();

        // === FORM 3 ===
        $form3_1 = UsersResponseForm3_1::where('user_id', $user->id)->first();
        $form3_2 = UsersResponseForm3_2::where('user_id', $user->id)->first();
        $form3_3 = UsersResponseForm3_3::where('user_id', $user->id)->first();
        $form3_4 = UsersResponseForm3_4::where('user_id', $user->id)->first();
        $form3_5 = UsersResponseForm3_5::where('user_id', $user->id)->first();
        $form3_6 = UsersResponseForm3_6::where('user_id', $user->id)->first();
        $form3_7 = UsersResponseForm3_7::where('user_id', $user->id)->first();
        $form3_8 = UsersResponseForm3_8::where('user_id', $user->id)->first();
        $form3_8_1 = UsersResponseForm3_8_1::where('user_id', $user->id)->first();
        $form3_9 = UsersResponseForm3_9::where('user_id', $user->id)->first();
        $form3_10 = UsersResponseForm3_10::where('user_id', $user->id)->first();
        $form3_11 = UsersResponseForm3_11::where('user_id', $user->id)->first();
        $form3_12 = UsersResponseForm3_12::where('user_id', $user->id)->first();
        $form3_13 = UsersResponseForm3_13::where('user_id', $user->id)->first();
        $form3_14 = UsersResponseForm3_14::where('user_id', $user->id)->first();
        $form3_15 = UsersResponseForm3_15::where('user_id', $user->id)->first();
        $form3_16 = UsersResponseForm3_16::where('user_id', $user->id)->first();
        $form3_17 = UsersResponseForm3_17::where('user_id', $user->id)->first();
        $form3_18 = UsersResponseForm3_18::where('user_id', $user->id)->first();
        $form3_19 = UsersResponseForm3_19::where('user_id', $user->id)->first();

        // === Construir "comisiones" directamente ===
        $comisiones = (object) [
            'comision1' => $form2->comision1 ?? 0,
            'actv2Comision' => $form2_2->actv2Comision ?? 0,
            'actv3Comision' => $form3_1->actv3Comision ?? 0,
            'comision3_2' => $form3_2->comision3_2 ?? 0,
            'comision3_3' => $form3_3->comision3_3 ?? 0,
            'comision3_4' => $form3_4->comision3_4 ?? 0,
            'comision3_5' => $form3_5->comision3_5 ?? 0,
            'comision3_6' => $form3_6->comision3_6 ?? 0,
            'comision3_7' => $form3_7->comision3_7 ?? 0,
            'comision3_8' => $form3_8->comision3_8 ?? 0,
            'comision3_8_1' => $form3_8_1->comision3_8_1 ?? 0,
            'comision3_9' => $form3_9->comision3_9 ?? 0,
            'comision3_10' => $form3_10->comision3_10 ?? 0,
            'comision3_11' => $form3_11->comision3_11 ?? 0,
            'comision3_12' => $form3_12->comision3_12 ?? 0,
            'comision3_13' => $form3_13->comision3_13 ?? 0,
            'comision3_14' => $form3_14->comision3_14 ?? 0,
            'comision3_15' => $form3_15->comision3_15 ?? 0,
            'comision3_16' => $form3_16->comision3_16 ?? 0,
            'comision3_17' => $form3_17->comision3_17 ?? 0,
            'comision3_18' => $form3_18->comision3_18 ?? 0,
            'comision3_19' => $form3_19->comision3_19 ?? 0,
        ];

        // === Cálculos ===
        $subtotal3_1To3_8_1 = $comisiones->actv3Comision + $comisiones->comision3_2 + $comisiones->comision3_3 +
            $comisiones->comision3_4 + $comisiones->comision3_5 + $comisiones->comision3_6 +
            $comisiones->comision3_7 + $comisiones->comision3_8 + $comisiones->comision3_8_1;

        $subtotal3_9To3_11 = $comisiones->comision3_9 + $comisiones->comision3_10 + $comisiones->comision3_11;
        $subtotal3_12To3_16 = $comisiones->comision3_12 + $comisiones->comision3_13 + $comisiones->comision3_14 + $comisiones->comision3_15 + $comisiones->comision3_16;
        $subtotal3_17To3_19 = $comisiones->comision3_17 + $comisiones->comision3_18 + $comisiones->comision3_19;

        $total = min($subtotal3_1To3_8_1 + $subtotal3_9To3_11 + $subtotal3_12To3_16 + $subtotal3_17To3_19, 700);
        $totalComision1 = $comisiones->comision1;
        $totalComision2 = $comisiones->actv2Comision;
        $totalComision3 = $total;
        $totalComisionRepetido = min($totalComision1 + $totalComision2 + $totalComision3, 1000);

        $minimaCalidad = $this->evaluarCalidad($total);
        $minimaTotal = $this->evaluarTotal($totalComisionRepetido);

        // === Dictaminadores ===
        $dictaminadoresCollection = $user->dictaminadores()->with('dictaminadorSignature')->distinct()->get();
        $dictaminadores = $dictaminadoresCollection->map(function ($dictaminador) {
            $signature = $dictaminador->dictaminadorSignature;
            return [
                'name' => $signature->evaluator_name ?? $dictaminador->name ?? 'Nombre no disponible',
                'signature_image' => $signature->signature_image ?? '',
                'mime' => $signature->mime ?? 'image/png',
            ];
        })
        ->unique('name') // 🔹 evita duplicados por nombre
        ->values();      // 🔹 reindexa el array


        // === Datos a la vista ===
        $data = [
            'logoBase64' => $logoBase64,
            'convocatoria' => $form1->convocatoria ?? '',
            'comisiones' => $comisiones,
            'totalComision1' => $totalComision1,
            'totalComision2' => $totalComision2,
            'total' => $total,
            'minimaCalidad' => $minimaCalidad,
            'minimaTotal' => $minimaTotal,
            'totalComisionRepetido' => $totalComisionRepetido,
            'subtotal3_1To3_8_1' => $subtotal3_1To3_8_1,
            'subtotal3_9To3_11' => $subtotal3_9To3_11,
            'subtotal3_12To3_16' => $subtotal3_12To3_16,
            'subtotal3_17To3_19' => $subtotal3_17To3_19,
            'dictaminadores' => $dictaminadores,
            'pagina_inicio' => 31,
            'pagina_total' => 33,
        ];

        Log::info('Data enviada al PDF:', $data);
        \Log::info('FORM1', ['user_id' => $user->id, 'form1' => $form1]);

        $pdf = Pdf::loadView('reporte_pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('disable-smart-shrinking', true);

        return $pdf->stream('reporte_pdf.pdf');
    }


    // Copia los métodos de evaluación de ConsolidatedResponseController:
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

    function resizeAndEncodeBase64($path, $newWidth = 200)
    {
        list($width, $height, $type) = getimagesize($path);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $srcImage = imagecreatefromjpeg($path);
                $mime = 'jpeg';
                break;
            case IMAGETYPE_PNG:
                $srcImage = imagecreatefrompng($path);
                $mime = 'png';
                break;
            case IMAGETYPE_GIF:
                $srcImage = imagecreatefromgif($path);
                $mime = 'gif';
                break;
            default:
                throw new Exception('Unsupported image type');
        }

        $newHeight = intval(($newWidth / $width) * $height);
        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Soporte para transparencia si es PNG
        if ($type == IMAGETYPE_PNG) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }

        imagecopyresampled($resized, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($resized, null, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($resized, null, 8);
                break;
            case IMAGETYPE_GIF:
                imagegif($resized);
                break;
        }
        $imageData = ob_get_clean();

        return 'data:image/' . $mime . ';base64,' . base64_encode($imageData);
    }


    public function updateForm(Request $request, $formIdentifier)
    {
        // 1. Mapeo de identificadores de formulario a sus modelos y reglas de validación
        $formConfig = $this->getFormConfig($formIdentifier);

        if (!$formConfig) {
            return response()->json(['success' => false, 'message' => 'Formulario no válido.'], 404);
        }

        // 2. Obtener las reglas de validación
        $controllerClass = $formConfig['controller'];
        $validationRules = $controllerClass::getValidationRules();

        // 3. Obtener la clase del modelo
        $modelClass = $formConfig['model'];

        // 3. Validar la petición
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        // Normalize observation fields to avoid NULL values that break DB constraints.
        // Instantiate the form controller to access its observation field list.
        try {
            $formControllerInstance = new $controllerClass();
            if (method_exists($formControllerInstance, 'getObservationFields')) {
                $reflectionMethod = new \ReflectionMethod($formControllerInstance, 'getObservationFields');
                
                // REMOVED: $reflectionMethod->setAccessible(true);
                
                $obsFields = $reflectionMethod->invoke($formControllerInstance);

                foreach ($obsFields as $campo) {
                    if (!array_key_exists($campo, $validatedData) || $validatedData[$campo] === null || trim((string)($validatedData[$campo] ?? '')) === '') {
                        $validatedData[$campo] = 'sin comentarios';
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::warning("Normalization failed for form_{$formIdentifier}: " . $e->getMessage());
        }

        try {
            // 4. Usar updateOrCreate para actualizar o crear el registro
            $record = $modelClass::updateOrCreate(
                [
                    'user_id' => $validatedData['user_id'],
                    'dictaminador_id' => $validatedData['dictaminador_id']
                ],
                $validatedData
            );

            // 5. Devolver una respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Formulario actualizado correctamente.',
                'data' => $record
            ]);

        } catch (\Exception $e) {
            \Log::error("Error al actualizar form_{$formIdentifier}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor al actualizar.'
            ], 500);
        }
    }

    /**
     * Devuelve la configuración (modelo y reglas) para un identificador de formulario.
     */
    private function getFormConfig($identifier)
{
    // Formularios con configuraciones específicas o que no siguen el patrón numérico simple.
    $specificConfig = [
        '38' => [
            'controller' => \App\Http\Controllers\DictaminatorForm3_8Controller::class,
            'model' => \App\Models\DictaminatorsResponseForm3_8::class,
        ],
        '381' => [
            'controller' => \App\Http\Controllers\DictaminatorForm3_8_1Controller::class,
            'model' => \App\Models\DictaminatorsResponseForm3_8_1::class,
        ],
        '2' => [
            'controller' => \App\Http\Controllers\DictaminatorForm2_Controller::class,
            'model' => \App\Models\DictaminatorsResponseForm2::class,
        ],
        '22' => [
            'controller' => \App\Http\Controllers\DictaminatorForm2_2Controller::class,
            'model' => \App\Models\DictaminatorsResponseForm2_2::class,
        ],
    ];

    if (isset($specificConfig[$identifier])) {
        return $specificConfig[$identifier];
    }

    // Generar configuraciones dinámicas para el resto de los formularios form3_X
    if (preg_match('/^3(\d+)$/', $identifier, $matches)) {
        $i = $matches[1];
        if ($i >= 1 && $i <= 19) {
            return [
                'controller' => "App\\Http\\Controllers\\DictaminatorForm3_{$i}Controller",
                'model' => "App\\Models\\DictaminatorsResponseForm3_{$i}",
                'form_type' => "form3_{$i}",
            ];
        }
    }

    return null;
}


}
