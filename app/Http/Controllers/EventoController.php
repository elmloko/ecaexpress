<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function getEventos ()
    {
        return view('eventos.eventos');
    }
}