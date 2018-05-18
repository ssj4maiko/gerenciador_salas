<?php

namespace App\Http\Controllers;

use App\Usuarios;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Middleware;

class HomeController extends Controller
{
    public function showLogin(Request $request)
    {
        return view('homeless', array(
                                 'title'    => 'Realizar login no Sistema'
                                ,'inner'    => 'login'
                                ,'request'  => $request
                            ));
    }
    public function doLogin(Request $request)
    {
        return redirect('/home');
    }
    public function logOut(Request $request)
    {
        \Cookie::queue('remember_token','', '/');
        \Cookie::queue('id_usuario','', '/');

        return redirect('/login');
    }
}