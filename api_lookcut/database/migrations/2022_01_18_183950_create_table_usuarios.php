<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_identificacion', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id_usuario')->unsigned();
            $table->string('usuario');
            $table->string('password');
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->boolean('estado')->default(1);
            $table->integer('fecha_nacimiento')->nullable();
            $table->string('lugar_nacimiento')->nullable();
            $table->string('cedula')->nullable();
            $table->string('grupo_sanguineo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->integer('clave_fallas')->unsigned();
            $table->integer('fecha_ingreso')->nullable();
            $table->char('genero')->nullable();
            $table->integer('tipo_identificacion_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tipo_identificacion_id')->references('id')->on('tipo_identificacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_identificacion');
        Schema::dropIfExists('usuarios');
    }
}
