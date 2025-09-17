<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormContentController extends Controller
{
    public function getFormContent($form)
    {
        if ($form === 'form2') {
            return view('form2')->render();
        } elseif ($form === 'form2_2') {
            return view('form2_2')->render();
        } else if($form === 'form3_1'){
            return view('form3_1')->render();
        }else {
            return response()->json(['error' => 'Formulario no encontrado'], 404);
        }
    }
}
