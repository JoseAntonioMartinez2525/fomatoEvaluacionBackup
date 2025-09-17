<?php

namespace App\Http\Controllers;

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
use Barryvdh\DomPDF\Facade\Pdf; // Importar DomPDF
use Svg\Document;
use Svg\Nodes\EmbeddedImage;
use Intervention\Image\Facades\Image;

class DictaminatorController extends Controller
{
    public function getDocentes()
    {
        $docentes = User::where('user_type', 'docente')->get(['email']);
        \Log::info('Docentes:', $docentes->toArray());
        return response()->json($docentes);
    }

    public function getDocenteData(Request $request)
    {
        $email = $request->query('email');
        $docente = User::where('email', $email)->first();

        if (!$docente) {
            return response()->json(['error' => 'Docente not found'], 404);
        }

        $formData1 = UsersResponseForm1::where('user_id', $docente->id)->first();
        $convocatoria = UsersResponseForm1::where('user_id', $docente->id)->first();
        $periodo = UsersResponseForm1::where('user_id', $docente->id)->first();
        $nombre = UsersResponseForm1::where('user_id', $docente->id)->first();
        $area = UsersResponseForm1::where('user_id', $docente->id)->first();
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
                'convocatoria'=>$convocatoria->convocatoria,
                'periodo'=>$periodo->periodo,
                'nombre'=>$nombre->nombre,
                'area'=>$area->area,
                'departamento'=>$departamento->departamento,
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
        $email = $request->query('email');
        $user = User::where('email', $email)->first();
        $logoPath = public_path('logo_uabcs.png');
        if (!file_exists($logoPath)) {
            // Si no existe en public, intenta en storage como respaldo
            $logoPath = storage_path('logo_uabcs.png');
        }
        $logoImageContent = file_exists($logoPath) ? file_get_contents($logoPath) : '';
        $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);


        $logoBase64 = $logoImageContent
            ? 'data:image/' . $logoType . ';base64,' . base64_encode($logoImageContent)
            : '';

        //'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png'; // Or from a configuration
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // 1. Obtener la convocatoria
        $form1 = UsersResponseForm1::where('user_id', $user->id)->first();
        $convocatoria = $form1 ? $form1->convocatoria : '';

        // 2. Obtener las comisiones del usuario
        $comisiones = \DB::table('consolidated_responses')->where('user_id', $user->id)->first();

        if (!$comisiones) {
            return response()->json(['error' => 'No hay datos de comisiones para este usuario'], 404);
        }

        // 3. Calcular subtotales y totales igual que en tu ConsolidatedResponseController
        $subtotal3_1To3_8_1 = $comisiones->actv3Comision + $comisiones->comision3_2 + $comisiones->comision3_3 + $comisiones->comision3_4 + $comisiones->comision3_5 + $comisiones->comision3_6 + $comisiones->comision3_7 + $comisiones->comision3_8 + $comisiones->comision3_8_1;
        $subtotal3_9To3_11 = $comisiones->comision3_9 + $comisiones->comision3_10 + $comisiones->comision3_11;
        $subtotal3_12To3_16 = $comisiones->comision3_12 + $comisiones->comision3_13 + $comisiones->comision3_14 + $comisiones->comision3_15 + $comisiones->comision3_16;
        $subtotal3_17To3_19 = $comisiones->comision3_17 + $comisiones->comision3_18 + $comisiones->comision3_19;

        $total = min(
            $subtotal3_1To3_8_1 + $subtotal3_9To3_11 + $subtotal3_12To3_16 + $subtotal3_17To3_19,
            700
        );

        $totalComision1 = $comisiones->comision1 ?? 0;
        $totalComision2 = $comisiones->actv2Comision ?? 0;
        $totalComision3 = $total;
        $totalComisionRepetido = min($totalComision1 + $totalComision2 + $totalComision3, 1000);

        // 4. Calcular mínima calidad y mínima total usando los mismos métodos
        $minimaCalidad = $this->evaluarCalidad($total);
        $minimaTotal = $this->evaluarTotal($totalComisionRepetido);
        // Agrega esta línea para obtener la firma del evaluador:
        $evaluatorSignature = EvaluatorSignature::where('user_id', $user->id)->first() ?? new EvaluatorSignature();

        $signaturePaths = [
            'signature_path' => storage_path('app/public/' . $evaluatorSignature->signature_path),
            'signature_path_2' => storage_path('app/public/' . $evaluatorSignature->signature_path_2),
            'signature_path_3' => storage_path('app/public/' . $evaluatorSignature->signature_path_3)
        ];

        // Validación básica para evitar errores si la firma no existe
        foreach ($signaturePaths as $key => $path) {
            if (!file_exists($path)) {
                $signaturePaths[$key] = null; // O podrías asignar una imagen por defecto si gustas
            }
        }
    /*
        $svgPaths = [];

        if (!file_exists(public_path('storage/' . $evaluatorSignature->signature_path))) {
            dd('Firma no encontrada:', public_path('storage/' . $evaluatorSignature->signature_path));
        }
        //dd($signaturePaths, $svgPaths);


        foreach ($signaturePaths as $key => $inputImage) {
            if (!file_exists($inputImage)) {
                //dd("Archivo no encontrado en storage_path: {$inputImage}");
            }

            $outputSvg = storage_path('app/public/' . pathinfo($inputImage, PATHINFO_FILENAME) . '.svg');

            $svgContent = '<?xml version="1.0" encoding="UTF-8"?>
            <svg xmlns="http://www.w3.org/2000/svg" width="200" height="100">
                <image href="' . asset('storage/' . basename($inputImage)) . '" x="0" y="0" width="200" height="100"/>
            </svg>';

            file_put_contents($outputSvg, $svgContent);

            if (!file_exists($outputSvg)) {
                dd("Error: El archivo {$outputSvg} no se ha creado correctamente.");
            }

            $svgPaths[$key] = 'storage/' . pathinfo($inputImage, PATHINFO_FILENAME) . '.svg';
        }



*/
        // Variables para la vista PDF

        $signature_path = storage_path('app/public/signatures/' . basename($evaluatorSignature->signature_path));
        $signature_path_2 = storage_path('app/public/signatures/' . basename($evaluatorSignature->signature_path_2));
        $signature_path_3 = storage_path('app/public/signatures/' . basename($evaluatorSignature->signature_path_3));
        $data = [
            'logoBase64' => $logoBase64,
            'convocatoria' => $convocatoria,
            'comisiones' => $comisiones,
            'total' => $total,
            'minimaCalidad' => $minimaCalidad,
            'minimaTotal' => $minimaTotal,
            'totalComisionRepetido' => $totalComisionRepetido,
            'subtotal3_1To3_8_1' => $subtotal3_1To3_8_1,
            'subtotal3_9To3_11' => $subtotal3_9To3_11,
            'subtotal3_12To3_16' => $subtotal3_12To3_16,
            'subtotal3_17To3_19' => $subtotal3_17To3_19,
            'evaluator_name' => $evaluatorSignature->evaluator_name ?? '',
            'evaluator_name_2' => $evaluatorSignature->evaluator_name_2 ?? '',
            'evaluator_name_3' => $evaluatorSignature->evaluator_name_3 ?? '',
            'signature_path' => $signature_path,
            'signature_path_2' => $signature_path_2,
            'signature_path_3' => $signature_path_3,
            'pagina_inicio' => 31,
            'pagina_total' => 33,
        ];

        //dd($data);

        // Para asegurarte de que la ejecución no llegue al PDF
        //dd("SVGPaths generados correctamente:", $svgPaths);

        // 5. Pasar todo a la vista PDF
        $pdf = Pdf::loadView('reporte_pdf', $data);
        $pdf->setPaper('A4', 'landscape'); // <-- Esta línea establece la orientación horizontal

           
        

        //dd($evaluatorSignature->signature_path, $evaluatorSignature->signature_path_2, $evaluatorSignature->signature_path_3);
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


}

