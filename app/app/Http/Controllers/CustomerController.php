<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    public function index()
    {
        $datos = DB::select("select * from user");
        return view("customers")->with("datos", $datos);
    }

    public function add(Request $request)
    {
        try {
            $sql = DB::insert(" insert into user(email)values(?) ", [

                $request->email,
            ]);
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == true) {
            return back()->with("correcto", "¡Usuario registrado correctamente!");
        } else {
            return back()->with("incorrecto", "Error al registrar un usuario, por favor verifique la información.");
        }
    }

    public function update(Request $request)
    {
        try {
            $sql = DB::update(" update user set email=? where id=? ", [
                $request->email,
                $request->id,
            ]);
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == true) {
            return back()->with("correcto", "¡Datos del usuario editados correctamente!");
        } else {
            return back()->with("incorrecto", "Error al editar un usuario, por favor verifique la información.");
        }
    }

    public function delete($id)
    {
        try {
            $sql = DB::delete(" delete from user where id=$id ");
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == true) {
            return back()->with("correcto", "Usuario eliminado correctamente!");
        } else {
            return back()->with("incorrecto", "Error al eliminar un usuario, por favor verifique la información.");
        }


    }



}
