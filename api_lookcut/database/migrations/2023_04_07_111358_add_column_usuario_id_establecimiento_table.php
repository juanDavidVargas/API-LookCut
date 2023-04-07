<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUsuarioIdEstablecimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('establecimiento', function (Blueprint $table) {
            $table->integer('usuario_id')->after('tipo_negocio_id')->nullable()->unsigned();
            
            $table->foreign('usuario_id')->references('id_usuario')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('establecimiento', function (Blueprint $table) {
            $table->dropColumn('usuario_id');
        });
    }
}
