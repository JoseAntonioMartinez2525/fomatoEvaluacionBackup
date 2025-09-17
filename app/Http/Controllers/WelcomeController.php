<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
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
        $areaOptions = ['Agropecuaria', 'Ciencias del Mar y Tierra', 'Ciencias Sociales y Humanidades', 'Otro'];
        $departamentoOptions = ['Agronomia', 'Ciencia animal y Conservación del habitat', 'Ciencias de la tierra', 'Ciencias Marinas y Costeras', 'Ciencias Sociales y Juridicas', 'Economia', 'Humanidades', 'Ingenieria en Pesquerias', 'Sistemas Computacionales'];

        // Other data retrieval logic if needed
        
        return view('welcome', ['areaOptions' => $areaOptions, 'departamentoOptions' => $departamentoOptions]);
    }
}
