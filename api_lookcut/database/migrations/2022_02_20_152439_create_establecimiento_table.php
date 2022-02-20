<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablecimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establecimiento', function (Blueprint $table) {
            $table->increments('id', 11)->unsigned();
            $table->string('descripcion');
            $table->string('nit');
            $table->integer('ciudad_id')->unsigned();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->integer('tipo_negocio_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ciudad_id')->references('id_ciudad')->on('ciudades');
            $table->foreign('tipo_negocio_id')->references('id')->on('tipo_negocio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establecimiento');
    }
}
