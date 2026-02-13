<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FirmaDictaminador;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\DictaminadorSignature;

class FirmaDictaminadorController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();

        $firmaDictaminador = DictaminadorSignature::where('user_id', $user->id)->first();

        // Si ya tiene firma, toma su nombre de la BD; si no, usa el del usuario
        $personaEvaluadora = $firmaDictaminador->evaluator_name ?? $user->name;

        // Si ya tiene firma y no hemos mostrado el mensaje en esta sesión
            if ($firmaDictaminador && !session()->has('firma_msg_shown')) {
                session()->flash('status', '✅ Ya has registrado tu firma electrónica. Mostrando formularios en 5 segundos...');
                // Guardamos una bandera para que no se vuelva a flashar durante esta sesión
                session()->put('firma_msg_shown', true);
            }

        return view('comision_dictaminadora', [
        'personaEvaluadora' => $personaEvaluadora,
        'firma' => $firmaDictaminador->signature_image ?? null,
        'tieneFirma' => $firmaDictaminador ? true : false,
        ]);
    }

public function showResumen(Request $request)
{
    $user = Auth::user();
    $userType = $user->user_type;

    $firmas = []; // ✅ inicializar siempre

    if ($userType === 'dictaminador') {
        $firmaDictaminador = DictaminadorSignature::where('user_id', $user->id)->first();
        $personaEvaluadora = $firmaDictaminador->evaluator_name ?? $user->name;

        // Creamos un objeto dentro de un arreglo para que Blade pueda iterar
        $firmas[] = (object)[
            'evaluator_name' => $personaEvaluadora,
            'signature_image' => $firmaDictaminador->signature_image ?? null,
        ];
    }

    if ($userType ==='controlador') {
        $docenteId = $request->input('docente_id');

        if (!$docenteId) abort(400, 'Debe especificar un docente');

        $firmas = DictaminadorSignature::whereHas('docentes', function($q) use ($docenteId) {
            $q->where('docente_id', $docenteId);
        })->get();
    }

        return view('resumen_comision', [
        'userType' => $userType,
        'firmas' => $firmas ?? [],
    ]);
}




    
    public function storeFirma(Request $request)
    {
        $request->validate([
            
            'firma1' => 'required|image|max:2048|mimes:png,jpg,jpeg',
        ]);

        $user = Auth::user();
        $file = $request->file('firma1');

        // Crear manager con driver GD
        $manager = new ImageManager(new Driver());

        // Leer la imagen subida (devuelve un objeto Intervention/Image v3)
        $image = $manager->read($file->getPathname());

        // Convertir/cachear en GD y quitar fondo blanco
        $pngBytes = $this->removeBackgroundAndReturnPngBytes($image);

        // Base64
        $imageData = base64_encode($pngBytes);

        // Guardar o actualizar
        DictaminadorSignature::updateOrCreate(
            ['user_id' => $user->id],
            [
                'evaluator_name' => $user->name,
                'signature_image' => $imageData,
                'mime' => 'image/png',

            ]
        );

        // Mensaje flash de éxito
        session()->flash('status', '✅ Firma registrada correctamente. Mostrando formularios en 5 segundos...');


        return response()->json([
            'success' => true,
            'message' => 'Firma guardada correctamente.',
        ]);
    }

    public function storeFirmaSecretaria(Request $request)
    {
        // Recuperación robusta: Si user_id no llega, buscarlo por el email (que viene de la lista de configuración)
        if (!$request->filled('user_id') && $request->filled('email')) {
            $userByEmail = User::where('email', $request->email)->first();
            if ($userByEmail) {
                $request->merge(['user_id' => $userByEmail->id]);
            }
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'firma1' => 'required|image|max:2048|mimes:png,jpg,jpeg',
            'evaluator_name' => 'required|string',
        ]);

        $user = User::find($request->user_id);
        $file = $request->file('firma1');

        // Crear manager con driver GD
        $manager = new ImageManager(new Driver());

        // Leer la imagen subida
        $image = $manager->read($file->getPathname());

        // Convertir/cachear en GD y quitar fondo blanco
        $pngBytes = $this->removeBackgroundAndReturnPngBytes($image);

        // Base64
        $imageData = base64_encode($pngBytes);

        // Guardar o actualizar
        DictaminadorSignature::updateOrCreate(
            ['user_id' => $user->id],
            [
                'evaluator_name' => $request->evaluator_name,
                'signature_image' => $imageData,
                'mime' => 'image/png',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Firma guardada correctamente.',
        ]);
    }

    /**
     * Recibe un objeto Intervention Image (v3), quita fondo casi blanco
     * y devuelve los bytes PNG (string) resultantes.
     *
     * @param \Intervention\Image\Image $image
     * @return string PNG binary
     */
    private function removeBackgroundAndReturnPngBytes($image)
    {
        $gd = $image->core()->native();
        
        // Asegurar que la imagen es TrueColor para manejar transparencia correctamente (necesario para JPG)
        if (!imageistruecolor($gd)) {
            imagepalettetotruecolor($gd);
        }

        $width = imagesx($gd);
        $height = imagesy($gd);
        
        // Configurar para guardar canal alfa y no mezclar al editar
        imagealphablending($gd, false);
        imagesavealpha($gd, true);

        $threshold = 240; // nivel de blanco a eliminar
        $transparent = imagecolorallocatealpha($gd, 255, 255, 255, 127);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($gd, $x, $y);
                // Extraer componentes RGB usando operadores bit a bit (más rápido y compatible con TrueColor)
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                if ($r >= $threshold && $g >= $threshold && $b >= $threshold) {
                    imagesetpixel($gd, $x, $y, $transparent);
                }
            }
        }

        ob_start();
        imagepng($gd);
        $pngData = ob_get_clean();
        // imagedestroy($gd);

        return $pngData;
    }

    public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Busca si el dictaminador ya tiene firma registrada
    $firmaDictaminador = DictaminadorSignature::where('user_id', $user->id)->first();

    $personaEvaluadora = $firmaDictaminador->evaluator_name ?? $user->name;

    // Si ya tiene firma y no se ha mostrado el mensaje aún
    if ($firmaDictaminador && !session()->has('firma_msg_shown')) {
        session()->flash('status', '✅ Ya has registrado tu firma electrónica. Mostrando formularios en 5 segundos...');
        session()->put('firma_msg_shown', true);
    }

    return view('resumen_comision', [
        'personaEvaluadora' => $personaEvaluadora,
        'firma' => $firmaDictaminador->signature_image ?? null,
        'tieneFirma' => $firmaDictaminador ? true : false,
    ]);
}

public function getFirmasPorDocente(Request $request)
{
    $docenteId = $request->input('docente_id');

    if (!$docenteId) {
        return response()->json(['error' => 'Falta el user_id del docente'], 400);
    }

    // Obtener todas las firmas de dictaminadores que evaluaron a este docente
    $firmas = DictaminadorSignature::whereHas('docentes', function ($q) use ($docenteId) {
        $q->where('docente_id', $docenteId);
    })->get();

    return response()->json($firmas);
}

public function getSignatures(Request $request)
{
    $user = Auth::user();
    $userType = $user->user_type;
    $userId = $user->id;

    $email = $request->input('email'); // correo del docente seleccionado (solo lo envía secretaria)

    // ===== Dictaminador: solo retorna su propia firma =====
    if ($userType === 'dictaminador') {
        $firma = DictaminadorSignature::where('user_id', $userId)->first();

        if (!$firma) {
            return response()->json(['message' => 'No se encontró firma registrada'], 404);
        }

        return response()->json([[ // Return as an array to be consistent
            'evaluator_name' => $firma->evaluator_name ?? $user->name,
            'signature_image' => $firma->signature_image ?? null,
            'mime' => $firma->mime ?? null,
        ]]);
    }

    // ===== Secretaria: retorna todas las firmas de los dictaminadores de un docente =====
 if ($userType ==='controlador') {
    $docente = User::where('email', $email)->where('user_type', 'docente')->first();

    if (!$docente) {
        return response()->json(['message' => 'Docente no encontrado'], 404);
    }

    // Obtiene los usuarios (dictaminadores) y carga sus firmas
    $dictaminadores = $docente->dictaminadores()->with('dictaminadorSignature')->distinct()->get();

    $signatures = $dictaminadores->map(function ($dictaminador) {
        return [
            'evaluator_name'  => $dictaminador->dictaminadorSignature->evaluator_name ?? $dictaminador->name,
            'signature_image' => $dictaminador->dictaminadorSignature->signature_image ?? null,
            'mime'            => $dictaminador->dictaminadorSignature->mime ?? null,
        ];
    });

    return response()->json($signatures->values());
}

    return response()->json(['message' => 'Tipo de usuario no válido'], 403);
}


}
