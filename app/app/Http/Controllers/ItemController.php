<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index() 
    { $items = Item::paginate(10); // Obtener 10 elementos por página 
        return view('comision_dictaminadora', compact('items'));
    }
}


