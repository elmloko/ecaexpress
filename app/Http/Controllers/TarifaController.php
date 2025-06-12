<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TarifaController extends Controller
{
    public function getTarifa ()
    {
        return view('tarifario.tarifa ');
    }
}