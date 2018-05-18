<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuarios;
use Validator;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    private function _validation($request){
        $rules = array(
             'realname'	=> 'required'
            ,'username'	=> 'required'
            ,'password'	=> 'required|confirmed'
        );

        return Validator::make($request->all(), $rules);
    }
    private function _form($id, $input, $request){

    	$forms['params'] = array(
    		 'action'	=> $id ? url('/usuario/save') : url('/registrar/save')
    		,'name'		=> 'save_form'
    		,'id_update'=> $id
    		,'back'		=> $id ? url('/home') : url('/login')
    		);

    	$name = 'realname';
    	$forms['fields'][$name] = array(
    		 'type'		=>	'text'
    		,'label'	=>	'Nome do Usuário'
    		,'value'	=>	isset($request->$name) ? $request->$name : ($input ? $input->$name : '')
    		,'attrb'	=>	array()
    		);
    	$name = 'username';
    	$forms['fields'][$name] = array(
    		 'type'		=>	'text'
    		,'label'	=>	'ID login'
    		,'value'	=>	isset($request->$name) ? $request->$name : ($input ? $input->$name : '')
    		,'attrb'	=>	array()
    		);
    	$name = 'password';
    	$forms['fields'][$name] = array(
    		 'type'		=>	'password'
    		,'label'	=>	'Senha'
    		,'value'	=>	isset($request->$name) ? $request->$name : ($input ? $input->$name : '')
    		,'attrb'	=>	array()
    		);
    	$name = 'password_confirmation';
    	$forms['fields'][$name] = array(
    		 'type'		=>	'password'
    		,'label'	=>	'Repetir Senha'
    		,'value'	=>	isset($request->$name) ? $request->$name : ($input ? $input->$name : '')
    		,'attrb'	=>	array()
    		);

    	return $forms;
    }
    public function create($id_usuario = null,Request $request)
    {
    	if($id_usuario){
    		$usuario = Usuarios::find($id_usuario);
	        return view('home', array(
	        						 'title'	=> 'Atualizar perfil'
	        						,'inner'	=> 'form'
	        						,'form'		=> $this->_form($id_usuario, $usuario, $request)
	        						,'request'	=> $request
	        					));
    	} else {
    		$usuario = Usuarios::find($id_usuario);
	        return view('homeless', array(
	        						 'title'	=> 'Registrar novo Usuário'
	        						,'inner'	=> 'form'
	        						,'form'		=> $this->_form($id_usuario, $usuario, $request)
	        						,'request'	=> $request
	        					));
    	}
    }
    public function save($id_usuario = null,Request $request){
    	$val = $this->_validation($request);

    	if($val->fails()){
            return redirect('/'.($id_usuario ? 'usuario/'.$id_usuario : 'registrar/'))
	                ->withErrors($val,'save_form')
	                ->withInput();
    	}
    	if(!$id_usuario)
    		$usuario = new Usuarios;
    	else
    		$usuario = Usuarios::find($id_usuario);

    	$usuario->realname = $request->realname;
    	$usuario->username = $request->username;
    	$usuario->password = Hash::make($request->password);
    	$usuario->save();

    	$alert = array(
    		 'type'		=> 'success'
    		,'message'	=> $id_usuario ? 'Usuário atualizado com sucesso' : 'Usuário criado com sucesso'
    		);

    	$request->session()->flash('alert', $alert);

        return redirect($id_usuario ? '/home' : '/login');

    	//return $this->create($id_usuario);
    }
    public function del($id_usuario, Request $request){
    	Usuarios::destroy($id_usuario);

    	$alert = array(
    		 'type'		=> 'success'
    		,'message'	=> 'Usuário excluído com sucesso'
    		);

    	$request->session()->flash('alert', $alert);

        return redirect('/logout');
    }
}
