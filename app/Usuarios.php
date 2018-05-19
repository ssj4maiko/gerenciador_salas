<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
	protected $table = 'usuarios';
	protected $primaryKey = 'id_usuario';
	public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';
    //

    private $currentUser = null;
    public function scopeCurrentUser($query){
    	$id = \Cookie::get('id_usuario');
    	if($id){
	    	return $query->find($id);
    	}
    	return $query;
    }
}
