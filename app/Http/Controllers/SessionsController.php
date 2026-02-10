<?php
/**
 * nombre del programador: Jose Antonio Martínez del Toro
 * objetivo: Controlador de sesiones
 * fecha: 2024-05-31
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersResponseForm1; // Import UsersResponseForm1
use Illuminate\Support\Facades\Log;
use App\Models\EvaluationDate;
use Carbon\Carbon;
use App\Support\DictaminadoresConfig;

class SessionsController extends Controller
{
    public static $allowedEmails = [
        'joma_18@alu.uabcs.mx',
        'oa.campillo@uabcs.mx',
        'rluna@uabcs.mx',
        'v.andrade@uabcs.mx',
    ];
    public function index()
    {
        return view('login');
    }

    // Simulación de lista física de dictaminadores
    protected $dictaminadorEmails;

    public function __construct()
    {
        $this->dictaminadorEmails = DictaminadoresConfig::emails();

    }

public function login(Request $request)
{
    $email = strtolower(trim($request->input('email')));
    $password = $request->input('password');
    $isNoPassword = $request->input('no_password_required') == 'true';

    // --- ACCESO PARA USUARIOS SIN CONTRASEÑA (SECRETARIA) ---
    if (in_array($email, self::$allowedEmails) && $isNoPassword) {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $email,
                'user_type' => 'secretaria',
                'password' => Hash::make('defaultpassword'),
            ]
        );

        Auth::login($user);
        return $this->redirectByUserType($user);
    }

    // --- ACCESO PARA DICTAMINADORES (flag permanente) ---
    $user = User::where('email', $email)->first();

    if ($user && $user->is_dictaminador && $isNoPassword) {
        $dicta = DictaminadoresConfig::byEmail($email);
        $departamento = $dicta['departamento'] ?? $user->departamento;
        
        $user->update([
            'is_dictaminador' => true,
            'departamento' => $departamento,
        ]);

        // --- Asegurar registro en UsersResponseForm1 si también es docente ---
        $isAlsoDocente = array_key_exists(strtolower($user->email), config('docentes', []));
        if ($isAlsoDocente) {
            $userResponseForm1 = UsersResponseForm1::firstOrNew(['user_id' => $user->id]);
            $docente = config('docentes')[strtolower($user->email)] ?? null;

            $userResponseForm1->nombre = $docente['nombre'] ?? $user->name;
            $userResponseForm1->area = $docente['area'] ?? ($user->area ?? 'No definida');
            $userResponseForm1->departamento = $docente['departamento'] ?? ($user->departamento ?? 'No definido');

            if (!$userResponseForm1->exists) {
                $userResponseForm1->periodo = UsersResponseForm1::calculateCurrentPeriod();
                $latestForm1 = UsersResponseForm1::latest()->first();
                $userResponseForm1->convocatoria = $latestForm1->convocatoria ?? 'Convocatoria no asignada';
            }

            $userResponseForm1->save();
        }

        Auth::login($user);
        return $this->redirectByUserType($user);
    }

    // --- LOGIN REGULAR CON CONTRASEÑA ---
    if (Auth::attempt(['email' => $email, 'password' => $password])) {
        $user = Auth::user();

        // Sincronizar UsersResponseForm1 si es docente
        if (array_key_exists(strtolower($user->email), config('docentes', [])) || $user->user_type === 'docente') {
            $userResponseForm1 = UsersResponseForm1::firstOrNew(['user_id' => $user->id]);
            $docente = config('docentes')[strtolower($user->email)] ?? null;

            $userResponseForm1->nombre = $docente['nombre'] ?? $user->name;
            $userResponseForm1->area = $docente['area'] ?? ($user->area ?? 'No definida');
            $userResponseForm1->departamento = $docente['departamento'] ?? ($user->departamento ?? 'No definido');

            if (!$userResponseForm1->exists) {
                $userResponseForm1->periodo = UsersResponseForm1::calculateCurrentPeriod();
                $latestForm1 = UsersResponseForm1::latest()->first();
                $userResponseForm1->convocatoria = $latestForm1->convocatoria ?? 'Convocatoria no asignada';
            }

            $userResponseForm1->save();
        }

        return $this->redirectByUserType($user);
    }

    return back()->withErrors([
        'email' => 'Credenciales incorrectas, por favor intente de nuevo',
        'password' => 'Credenciales incorrectas, por favor intente de nuevo',
    ]);
}


private function redirectByUserType($user)
{
    $noCache = 'no-cache, no-store, must-revalidate';
    $pragmaNoCache = 'no-cache';
    $expiresZero = '0';

    // Verificación de período para docentes al iniciar sesión
    if ($user->user_type === 'docente') {
        $evaluationDates = EvaluationDate::where('type', 'docentes_llenado')->latest('id')->first();
        $now = Carbon::now();

        if ($evaluationDates) {
            $startDate = Carbon::parse($evaluationDates->start_date);
            $endDate = Carbon::parse($evaluationDates->end_date)->endOfDay();

            if (!$now->between($startDate, $endDate)) {
                return response()->view('errors.period-closed', [], 403);
            }
        } else {
            return response()->view('errors.period-closed', ['message' => 'El período de evaluación aún no ha sido configurado.'], 403);
        }
    }

    if ($user->user_type === 'dictaminador') {
        // Redirigir a welcome (DashboardController) para que evalúe si es rol dual y las fechas activas
        return redirect()->route('welcome')
            ->header('Cache-Control', $noCache)
            ->header('Pragma', $pragmaNoCache)
            ->header('Expires', $expiresZero);
    } elseif ($user->user_type === 'secretaria') {
        return redirect()->route('secretaria')
            ->header('Cache-Control', $noCache)
            ->header('Pragma', $pragmaNoCache)
            ->header('Expires', $expiresZero);
    } else {
        return redirect()->route('welcome')
            ->header('Cache-Control', $noCache)
            ->header('Pragma', $pragmaNoCache)
            ->header('Expires', $expiresZero);
    }
}


    public function logout(Request $request)
    {
        Auth::logout();
               
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        \Log::info('Logout ejecutado correctamente.');

        return redirect()->route('login') // Redirige a la ruta nombrada 'login'
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    public function welcome(Request $request)
    {
        $user = Auth::user();
        
        // Obtener datos guardados en UsersResponseForm1 (creado al login)
        $form1 = UsersResponseForm1::where('user_id', $user->id)->first();

        $periodo = $form1 ? $form1->periodo : (\App\Models\UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido');
        $convocatoria = $form1 ? $form1->convocatoria : 'Convocatoria no asignada';
        
        // Cargar datos directamente del archivo docentes.php si es posible
        $docente = config('docentes')[strtolower($user->email)] ?? null;


        // 1. Priorizar datos de UsersResponseForm1 si existen (ya sincronizados en login)
        $nombre = $form1->nombre ?? ($docente['nombre'] ?? $user->name);
        $area = $form1->area ?? ($docente['area'] ?? 'No definida');
        $departamento = $form1->departamento ?? ($docente['departamento'] ?? 'No definido');

        Log::info('Welcome page data prepared', [
            'user_id' => $user->id,
            'email' => $user->email,
            'nombre' => $nombre,
            'area' => $area,
            'departamento' => $departamento,
            'has_form1' => (bool) $form1
        ]);

        return view('welcome', compact('user', 'periodo', 'convocatoria', 'nombre', 'area', 'departamento'));
    }

    public function showLoginForm()
    {
        // Verifica si el modo oscuro está habilitado para este usuario
        return view('auth.login', ['darkMode' => session('dark_mode', false)]);
    }

    

}