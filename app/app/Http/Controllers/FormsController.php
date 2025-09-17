<?php

namespace App\Http\Controllers;

use App\Models\DictaminatorsResponseForm3_10;
use App\Models\DictaminatorsResponseForm3_11;
use App\Models\DictaminatorsResponseForm3_12;
use App\Models\DictaminatorsResponseForm3_13;
use App\Models\DictaminatorsResponseForm3_14;
use App\Models\DictaminatorsResponseForm3_15;
use App\Models\DictaminatorsResponseForm3_16;
use App\Models\DictaminatorsResponseForm3_17;
use App\Models\DictaminatorsResponseForm3_18;
use App\Models\DictaminatorsResponseForm3_19;
use App\Models\DictaminatorsResponseForm3_3;
use App\Models\DictaminatorsResponseForm3_4;
use App\Models\DictaminatorsResponseForm3_5;
use App\Models\DictaminatorsResponseForm3_6;
use App\Models\DictaminatorsResponseForm3_7;
use App\Models\DictaminatorsResponseForm3_8;
use App\Models\DictaminatorsResponseForm3_8_1;
use App\Models\DictaminatorsResponseForm3_9;
use App\Models\EvaluatorSignature;
use App\Models\UserResume;
use App\Models\UsersResponseForm1;
use App\Models\UsersResponseForm2;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DictaminatorsResponseForm2;
use App\Models\DictaminatorsResponseForm2_2;
use App\Models\DictaminatorsResponseForm3_1;
use App\Models\DictaminatorsResponseForm3_2;
use Illuminate\Support\Facades\DB;

use Dompdf\Dompdf;
use Dompdf\Options;
class FormsController extends Controller
{
    

    public function getDictaminadores()
    {
        $dictaminador = User::where('user_type', 'dictaminador')->get(['id', 'email']);
        \Log::info('Dictaminador:', $dictaminador->toArray());
        return response()->json($dictaminador);
    }
public function getDictaminadorData(Request $request)
{
        $email = $request->query('user_email');
        $user_id = $request->query('user_id');

        \Log::info('emails recibido:', ['emails' => $email]);
        \Log::info('Docente ID recibido:', ['docente_id' => $user_id]);

        if (!is_array($email) || empty($email)) {
            return response()->json(['error' => 'emailss invalidos o no recibidos'], 400);
        }
        // Verificar que el dictaminador con el ID proporcionado exista
        $docente = User::where('id', $user_id)->first();

        if (!$docente) {
            return response()->json(['error' => 'Dictaminador not found'], 404);
        }

        // Aquí deberás ajustar la lógica según cómo almacenas los datos de `form2` y `form2_2`
        $form2Data = DictaminatorsResponseForm2::where('user_id', $user_id)->first();
        $form2_2Data = DictaminatorsResponseForm2_2::where('user_id', $user_id)->first();
        $form3_1Data = DictaminatorsResponseForm3_1::where('user_id', $user_id)->first();
        $form3_2Data = DictaminatorsResponseForm3_2::where('user_id', $user_id)->first();
        $form3_3Data = DictaminatorsResponseForm3_3::where('user_id', $user_id)->first();
        $form3_4Data = DictaminatorsResponseForm3_4::where('user_id', $user_id)->first();
        $form3_5Data = DictaminatorsResponseForm3_5::where('user_id', $user_id)->first();
        $form3_6Data = DictaminatorsResponseForm3_6::where('user_id', $user_id)->first();
        $form3_7Data = DictaminatorsResponseForm3_7::where('user_id', $user_id)->first();
        $form3_8Data = DictaminatorsResponseForm3_8::where('user_id', $user_id)->first();
        $form3_8_1Data = DictaminatorsResponseForm3_8_1::where('user_id', $user_id)->first();
        $form3_9Data = DictaminatorsResponseForm3_9::where('user_id', $user_id)->first();
        $form3_10Data = DictaminatorsResponseForm3_10::where('user_id', $user_id)->first();
        $form3_11Data = DictaminatorsResponseForm3_11::where('user_id', $user_id)->first();
        $form3_12Data = DictaminatorsResponseForm3_12::where('user_id', $user_id)->first();
        $form3_13Data = DictaminatorsResponseForm3_13::where('user_id', $user_id)->first();
        $form3_14Data = DictaminatorsResponseForm3_14::where('user_id', $user_id)->first();
        $form3_15Data = DictaminatorsResponseForm3_15::where('user_id', $user_id)->first();
        $form3_16Data = DictaminatorsResponseForm3_16::where('user_id', $user_id)->first();
        $form3_17Data = DictaminatorsResponseForm3_17::where('user_id', $user_id)->first();
        $form3_18Data = DictaminatorsResponseForm3_18::where('user_id', $user_id)->first();
        $form3_19Data = DictaminatorsResponseForm3_19::where('user_id', $user_id)->first();
        $resumeData = UserResume::where('user_id', $user_id)->first();
        $signaturesData = EvaluatorSignature::where('user_id', $user_id)->first();

        $form1Data = $form2Data ? $form2Data->usersResponseForm1 : null;
        $formData = $this->getAllFormData($user_id, $form1Data);
        // Return a structured response which includes both form data

        $formFinalData = DB::table('consolidated_responses')
            ->join('users_final_resume', 'consolidated_responses.user_email', '=', 'users_final_resume.email')
            ->where('consolidated_responses.user_email', $email)
            ->select('consolidated_responses.*', 'users_final_resume.*')
            ->first();

        return response()->json([
            'docente' => [
                'docente_id' => $docente->user_id,
                'email' => $docente->email,
            ],
            'responseForm1' => $formData['form1Data'],
            'form2' => $form2Data,
            'form2_2' => $formData['form2_2'],
            'forms' => $formData['forms'],
            'form2_2Data' => $form2_2Data,
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
            'user_final_resume'=> $resumeData,
            'signatures' => $signaturesData,
            'final_form' => $formFinalData,


        ]);

        
    }

    private function getAllFormData($user_id, &$form1Data)
    {
        $formData = [];
        $forms = [];

        // Obtener datos de DictaminatorsResponseForm2_2
        $formData['form2_2'] = DictaminatorsResponseForm2_2::where('user_id', $user_id)->first();
        if ($formData['form2_2'] && !$form1Data) {
            $form1Data = $formData['form2_2']->usersResponseForm1;
        }

        // Iterar sobre los modelos de DictaminatorsResponseForm3
        for ($i = 1; $i <= 19; $i++) {
            $modelClass = 'App\\Models\\DictaminatorsResponseForm3_' . $i;
            if (class_exists($modelClass)) {
                $forms['form3_' . $i] = $modelClass::where('dictaminador_id', $user_id)->first();
                if ($forms['form3_' . $i] && !$form1Data) {
                    $form1Data = $forms['form3_' . $i]->usersResponseForm1;
                }
            }
        }

        $formData['forms'] = $forms;
        $formData['form1Data'] = $form1Data;

        return $formData;
    }


     public function showForms()
    {
        // Define las vistas y los rangos de páginas manualmente
        $forms = [
            ['view' => 'form2', 'startPage' => 1, 'endPage' => 1],
            ['view' => 'form2_2', 'startPage' => 2, 'endPage' => 2],
            ['view' => 'form3_1', 'startPage' => 3, 'endPage' => 4],
            ['view' => 'form3_2', 'startPage' => 5, 'endPage' => 5],
            ['view' => 'form3_3', 'startPage' => 6, 'endPage' => 7],
            ['view' => 'form3_4', 'startPage' => 8, 'endPage' => 8],
            ['view' => 'form3_5', 'startPage' => 9, 'endPage' => 9],
            ['view' => 'form3_6', 'startPage' => 10, 'endPage' => 10],
            ['view' => 'form3_7', 'startPage' => 11, 'endPage' => 11],
            ['view' => 'form3_8', 'startPage' => 12, 'endPage' => 12],
            ['view' => 'form3_8_1', 'startPage' => 13, 'endPage' => 13],            
            ['view' => 'form3_9', 'startPage' => 14, 'endPage' => 15],
            ['view' => 'form3_10', 'startPage' => 16, 'endPage' => 16], 
            ['view' => 'form3_11', 'startPage' => 17, 'endPage' => 17], 
            ['view' => 'form3_12', 'startPage' => 18, 'endPage' => 19], //*pag 18 a 19
            ['view' => 'form3_13', 'startPage' => 20, 'endPage' => 20], //pag 20
            ['view' => 'form3_14', 'startPage' => 21, 'endPage' => 21], //pag 21
            ['view' => 'form3_15', 'startPage' => 22, 'endPage' => 22], //pag 22
            ['view' => 'form3_16', 'startPage' => 23, 'endPage' => 24], //*pag 23 a 24
            ['view' => 'form3_17', 'startPage' => 25, 'endPage' => 25], //pag 25
            ['view' => 'form3_18', 'startPage' => 26, 'endPage' => 27], //pag 26 a 27
            ['view' => 'form3_19', 'startPage' => 28, 'endPage' => 30], //*pag 28 a 30
            ['view' => 'resumen_comision', 'startPage' => 31, 'endPage' => 33],
        ];

        // Debug de los datos
    

        return view('components.form-renderer', compact('forms'));
    }




function renderHtmlWithPageNumbers($htmlContent)
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $canvas = $dompdf->getCanvas();
    $pageCount = $canvas->get_page_count();

    // Ajustar la posición del texto para que no colisione con otros elementos
    $xPosition = 520; // Posición X (ajusta según el ancho de tu diseño)
    $yPosition = 550; // Posición Y (ajusta según la altura de tu footer en landscape)
    $fontSize = 12;   // Tamaño de fuente

    for ($page = 1; $page <= $pageCount; $page++) {
        $canvas->page_text(
            $xPosition,
            $yPosition,
            "Página $page de $pageCount", // Texto dinámico
            null, // Fuente por defecto
            $fontSize,
            [0, 0, 0] // Color (negro)
        );
    }

    return $dompdf->output();
}



}