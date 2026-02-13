<?php
/**
Nombre del programador: José Antonio Martínez del Toro
objetivo: Controlador para la gestión de usuarios
Fecha de creación: 2024-06-03
 */
namespace App\Http\Controllers;

use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Dompdf\Dompdf;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $datos = User::all(); // Using Eloquent instead of direct SQL queries
        return view("users")->with("datos", $datos);
    }

    public function add(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'user_type' => 'docente',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            Auth::login($user);
            return redirect()->route('welcome');
        } else {
            return back()->with("incorrecto", "Error al registrar un usuario, por favor verifique la información.");
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'password' => 'sometimes|string|min:8',
        ]);

        $user = User::find($request->id);
        if ($user) {
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return back()->with("correcto", "¡Datos de usuario editados correctamente!");
        } else {
            return back()->with("incorrecto", "Error al editar un usuario, por favor verifique la información.");
        }
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return back()->with("correcto", "¡Usuario eliminado correctamente!");
        } else {
            return back()->with("incorrecto", "Error al eliminar un usuario, por favor verifique la información.");
        }
    }

    public function import() 
    {
        Excel::import(new UsersImport, 'users.xlsx');
        
        return redirect('/')->with('success', 'All good!');
    }

    public function export() 
    {
        // Verificar si el usuario tiene permiso para exportar (está en la lista de permitidos)
        if (!in_array(Auth::user()->email, SessionsController::$allowedEmails)) {
            return redirect()->back()->with('incorrecto', 'Acceso denegado: No tiene permisos para generar este reporte.');
        }

        // 1. Configuración inicial y directorio temporal
        $timestamp = time();
        $tempDirName = 'temp_export_' . $timestamp;
        $tempPath = storage_path('app/' . $tempDirName);
        
        // Crear directorio si no existe
        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true);
        }

        // 2. Obtener usuarios (filtrar solo docentes si es necesario)
        $docenteEmails = array_keys(config('docentes', []));
        $users = User::where(function ($query) use ($docenteEmails) {
            $query->where('user_type', 'docente')
                  ->orWhereIn('email', $docenteEmails);
        })->get();

        // Pre-cargar logo para los reportes
        $logoUrl = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
        $logoBase64 = '';
        try {
            $logoContent = @file_get_contents($logoUrl);
            if ($logoContent) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoContent);
            }
        } catch (\Exception $e) {}

        $periodoArchivo ='';
        // 3. Generar PDFs individuales
        foreach ($users as $user) {
            // Obtener datos consolidados para el reporte
            // Intentamos buscar por user_id o email en la tabla de respuestas consolidadas
            $comisiones = DB::table('consolidated_responses')
                ->where('user_id', $user->id)
                ->orWhere('user_email', $user->email)
                ->first();
            
            // Si no hay datos, usamos un objeto vacío para evitar errores en la vista
            if (!$comisiones) {
                $comisiones = (object)[];
            }

            // Obtener datos del Formulario 1 para Convocatoria y Periodo
            $form1 = \App\Models\UsersResponseForm1::where('user_id', $user->id)->first();
            $convocatoria = $form1 ? ($form1->convocatoria ?? 'SinConvocatoria') : 'SinConvocatoria';
            $periodo = $form1 ? ($form1->periodo ?? 'SinPeriodo') : 'SinPeriodo';
            
            // Obtener firmas de dictaminadores
            $dictaminadores = collect([]);
            if (method_exists($user, 'dictaminadores')) {
                $dictaminadores = $user->dictaminadores()->with('dictaminadorSignature')->get()->map(function ($d) {
                    $signature = $d->dictaminadorSignature;
                    return [
                        'name' => $signature->evaluator_name ?? $d->name,
                        'signature_image' => $signature->signature_image ?? null,
                        'mime' => $signature->mime ?? 'image/png',
                    ];
                })->unique('name')->values();
            }

            // Preparar datos para la vista reporte_pdf
            // Nota: Asegúrate de que la vista maneje variables nulas con ?? ''
            $data = [
                'user' => $user,
                'logoBase64' => $logoBase64,
                'comisiones' => $comisiones,
                'total' => $comisiones->total_puntaje ?? 0,
                'dictaminadores' => $dictaminadores,
                // Variables adicionales que pueda requerir tu vista
                'convocatoria' => $convocatoria,
                'periodo' => $periodo,
            ];

            // Renderizar vista a HTML
            $html = view('reporte_pdf', $data)->render();

            // Generar PDF
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            // --- ENCRIPTACIÓN (CANDADO) ---
            // Generamos una contraseña aleatoria única para cada archivo
            $password = Str::random(10); // Genera contraseña aleatoria de 10 caracteres
            $user->pdf_password = $password; // Guardar para el Excel
            $canvas = $dompdf->getCanvas();
            if ($canvas instanceof \Dompdf\Adapter\CPDF) {
                $canvas->get_cpdf()->setEncryption($password, $password, ['print', 'copy']);
            }

            // Construir nombre del archivo: Convocatoria_Periodo_NombreDocente.pdf
            $safeConvocatoria = Str::slug($convocatoria, '_') ?: 'Convocatoria';
            $safePeriodo = Str::slug($periodo, '_') ?: 'Periodo';
            $safeNombre = Str::slug($user->name, '_') ?: 'Docente_' . $user->id;

            $pdfFilename = "{$safePeriodo}_{$safeNombre}.pdf";

            // Guardar PDF en carpeta temporal
            file_put_contents($tempPath . '/' . $pdfFilename, $dompdf->output());

            // Asignar el nombre del archivo al modelo de usuario para que el Excel lo sepa
            $user->pdf_filename = $pdfFilename;
            $periodoArchivo = $safePeriodo;
        }

        // 4. Generar Excel en la misma carpeta temporal
        $excelFilename = 'Listado_Reportes.xlsx';
        // Excel::store guarda relativo a 'storage/app', por eso usamos $tempDirName
        Excel::store(new UsersExport($users), $tempDirName . '/' . $excelFilename);

        // 5. Crear archivo ZIP
        $zipFilename = 'reportes_evaluacion_docentes_'.$periodoArchivo . $timestamp . '.zip';
        $zipPath = storage_path('app/' . $zipFilename);
        
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Agregar todos los archivos del directorio temporal al ZIP
            $files = File::files($tempPath);
            foreach ($files as $file) {
                // Añadir archivo al zip con su nombre original (sin rutas de carpetas)
                $zip->addFile($file->getRealPath(), $file->getFilename());
            }
            $zip->close();
        }

        // 6. Limpiar carpeta temporal
        File::deleteDirectory($tempPath);

        // 7. Descargar y eliminar el ZIP después del envío
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

}
