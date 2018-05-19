<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservas extends Model
{
	protected $table = 'reservas';
	protected $primaryKey = 'id_reserva';
	public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';

    public function usuario(){
    	return $this->hasOne('\App\Usuarios');
    }
    public function sala(){
    	return $this->hasOne('\App\Salas');
    }
}
