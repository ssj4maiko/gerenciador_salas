<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManualLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    private function update_session($usuario){
        $cook_id = Cookie::queue('id_usuario'       , $usuario->id_usuario    , 60);
        $cook_tk = Cookie::queue('remember_token'   , $usuario->remember_token, 60);
    }





    private function check_current_session($request){
        $token = $request->cookie('remember_token');
        $usuario = Usuarios::where('remember_token',$token)
                           ->get();
        if(count($usuario) > 0){
            $this->update_session($usuario[0]);

            return true;
        }
        return false;
    }

    private function check_post($request){
        if($request->isMethod('post')){
            if($request->username != '' && $request->password != ''){
                $usuario = Usuarios::where('username',$request->username)
                                   ->get();

                if(count($usuario) > 0){
                    $usuario = $usuario[0];

                    if(Hash::check($request->password,$usuario->password)){
                        $usuario = (object) array(
                             'id_usuario'     => $usuario->id_usuario
                            ,'remember_token' => Hash::make($request->username . date('U'))
                        );

                        $this->update_session($usuario);

                        Usuarios::where('id_usuario',$usuario->id_usuario)
                                ->update(array('remember_token' => $usuario->remember_token));

                        return true;
                    }
                }
            }
        }
        return false;
    }




    
    public function handle($request, Closure $next, $guard = null)
    {
        $check = false;
        switch(true){
            case $this->check_current_session($request):
                $check = true;
                break;
            case $this->check_post($request):
                $check = true;
                break;
        }

        if (!$check) {
            return redirect('/login');
        }
        return $next($request);
    }
}
