<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Usuarios;
use App\Salas;
use App\Reservas;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        return view('home',array(
                             'title'    => 'Dashboard'
                            ,'inner'    => null
                    ));
    }
}