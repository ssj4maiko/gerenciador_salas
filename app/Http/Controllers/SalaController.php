<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Salas;
use Validator;

class SalaController extends Controller
{
    private function _validation($request){
        $rules = array(
            'descricao'  => 'required',
        );

        return Validator::make($request->all(), $rules);
    }
    private function _form($id, $sala, $request){

    	$forms['params'] = array(
    		 'action'	=> url('/sala/save')
    		,'name'		=> 'save_form'
    		,'id_update'=> $id
    		,'back'		=> url('/home')
    		);
    	$name = 'descricao';
    	$forms['fields'][$name] = array(
    		 'type'		=>	'text'
    		,'label'	=>	'Descrição'
    		,'value'	=>	isset($request->$name) ? $request->$name : ($sala ? $sala->$name : '')
    		,'attrb'	=>	array()
    		);
    	return $forms;
    }
    public function create($id_sala = null,Request $request)
    {
    	$sala = null;
    	if($id_sala){
    		$sala = Salas::find($id_sala);
    	}
        return view('home', array(
        						 'title'	=> $id_sala ? 'Atualizar cadastro sala' : 'Cadastrar nova sala'
        						,'inner'	=> 'form'
        						,'form'		=> $this->_form($id_sala, $sala, $request)
        						,'request'	=> $request
        					));
    }
    public function save($id_sala = null,Request $request){
    	$val = $this->_validation($request);

    	if($val->fails()){
            return redirect('/sala'.($id_sala ? '/'.$id_sala : ''))
	                ->withErrors($val,'save_form')
	                ->withInput();
    	}
    	if(!$id_sala)
    		$sala = new Salas;
    	else
    		$sala = Salas::find($id_sala);

    	$sala->descricao = $request->descricao;
    	$sala->save();

    	$alert = array(
    		 'type'		=> 'success'
    		,'message'	=> $id_sala ? 'Sala atualizada com sucesso' : 'Sala criada com sucesso'
    		);

    	$request->session()->flash('alert', $alert);

        return redirect('/home');

    	//return $this->create($id_sala);
    }
    public function del($id_sala, Request $request){
    	Salas::destroy($id_sala);

    	$alert = array(
    		 'type'		=> 'success'
    		,'message'	=> 'Sala excluída com sucesso'
    		);

    	$request->session()->flash('alert', $alert);

        return redirect('/home');
    }
}
