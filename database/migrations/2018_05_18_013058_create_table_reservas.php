<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReservas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id_reserva');
            $table->integer('id_usuario')->unsigned();
            $table->integer('id_sala')->unsigned();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('id_sala')->references('id_sala')->on('salas');
            $table->dateTime('dt_start');
            $table->dateTime('dt_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
