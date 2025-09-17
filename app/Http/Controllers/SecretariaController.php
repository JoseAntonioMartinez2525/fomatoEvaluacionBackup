<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SecretariaController extends Controller
{
    public function showSecretaria()
    {
        $users = User::where('user_type', 'dictaminador')->get();
        return view('secretaria', compact('users'));
    }
}
