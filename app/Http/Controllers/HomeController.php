<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the welcome page with form options.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showWelcome()
    {
        $areaOptions = ['Agropecuaria', 'Ciencias del Mar y Tierra', 'Ciencias Sociales y Humanidades'];
        $departamentoOptions = ['Agronomia', 'Ciencia animal y Conservación del habitat', 'Ciencias de la tierra', 'Ciencias Marinas y Costeras', 'Ciencias Sociales y Juridicas', 'Economia', 'Humanidades', 'Ingenieria en Pesquerias', 'Sistemas Computacionales'];
        $menu = ['areaOptions', 'departamentoOptions'];
        return view('welcome', compact('menu'));
    }
}
