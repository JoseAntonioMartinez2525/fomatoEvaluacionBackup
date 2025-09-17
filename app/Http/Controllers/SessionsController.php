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
use Illuminate\Support\Facades\Hash;

class SessionsController extends Controller
{
    protected $allowedEmails = [
        'joma_18@alu.uabcs.mx',
        'oa.campillo@uabcs.mx',
        'rluna@uabcs.mx',
        'v.andrade@uabcs.mx',
    ];
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $email = strtolower(trim($request->input('email')));
        $password = $request->input('password');


        // Check if the email is allowed to login without a password
        if (in_array($email, $this->allowedEmails) && $request->input('no_password_required') == 'true') {
            $user = User::where('email', $email)->first();

            // If user does not exist, create the user
            if (!$user) {
                $user = User::create([
                    'name' => $email,
                    'user_type'=> '', // Empty string for special users
                    'email' => $email,
                    'password' => Hash::make('defaultpassword'), // You can set a default password or make it null
                ]);
            }

            // Log in the user
            Auth::login($user);
            if ($user->user_type === 'dictaminador') {
                return redirect()->route('comision_dictaminadora')
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }else if ($user->user_type === ''){
                return redirect()->route('secretaria')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
            }else if($user->user_type === 'docente'){
                return redirect()->intended('welcome')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0'); 
            }
               
        }

        // Regular login process
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $noCache = 'no-cache, no-store, must-revalidate';
            $pragmaNoCache = 'no-cache';
            $expiresZero = '0';

            if ($user->user_type === 'dictaminador') {
                return response()->view('comision_dictaminadora');
            } else if ($user->user_type === 'secretaria') {
                return response()->view('secretaria')
                    ->header('Cache-Control', $noCache)
                    ->header('Pragma', $pragmaNoCache)
                    ->header('Expires', $expiresZero);
            } else {
                return redirect()->intended('/welcome')
                    ->header('Cache-Control', $noCache)
                    ->header('Pragma', $pragmaNoCache)
                    ->header('Expires', $expiresZero);
            }
    }
        return back()->withErrors([
            'email' => 'Credenciales incorrectas, por favor intente de nuevo',
            'password' => 'Credenciales incorrectas, por favor intente de nuevo',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        \Log::info('Logout ejecutado correctamente.');

        return redirect('/login') // Redirige a la vista de login
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    public function welcome(Request $request)
    {
        $user = Auth::user();

        // Pass the user's email to the view
        return view('welcome', compact('user'));
    }

    public function showLoginForm()
    {
        // Verifica si el modo oscuro está habilitado para este usuario
        return view('auth.login', ['darkMode' => session('dark_mode', false)]);
    }

}
