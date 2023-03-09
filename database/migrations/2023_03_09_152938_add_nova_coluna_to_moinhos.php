<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNovaColunaToMoinhos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moinhos', function (Blueprint $table) {
            $table->string('nome_paciente')->nullable();
        });
        Schema::table('agendados', function (Blueprint $table) {
            $table->string('nome_paciente')->nullable();
        });
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->string('nome_paciente')->nullable();
        });
        Schema::table('posexames', function (Blueprint $table) {
            $table->string('nome_paciente')->nullable();
        });
        Schema::table('finalizados', function (Blueprint $table) {
            $table->string('nome_paciente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moinhos', function (Blueprint $table) {
            //
        });
        Schema::table('agendados', function (Blueprint $table) {
            //
        });
        Schema::table('atendimentos', function (Blueprint $table) {
            //
        });
        Schema::table('posexames', function (Blueprint $table) {
            //
        });
        Schema::table('finalizados', function (Blueprint $table) {
            //
        });
    }
}
