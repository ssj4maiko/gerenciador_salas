<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salas extends Model
{
	protected $table = 'salas';
	protected $primaryKey = 'id_sala';
	public $timestamps = false;
}
