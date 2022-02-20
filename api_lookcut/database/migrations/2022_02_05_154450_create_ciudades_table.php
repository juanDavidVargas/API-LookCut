<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiudadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->increments('id_ciudad')->unsigned();
            $table->integer('id_departamento');
            $table->string('ciu_codigo_dane')->nullable();
            $table->string('ciu_descripcion');
            $table->string('ciu_abreviatura')->nullable();
            $table->string('ciu_codigo_postal')->nullable();
            $table->string('ciu_latitud')->nullable();
            $table->string('ciu_longitud')->nullable();
            $table->boolean('ciu_estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciudades');
    }
}
