<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosexamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posexames', function (Blueprint $table) {
            $table->id();
            $table->string('acess_number');
            $table->string('codigo_setor_exame');
            $table->string('data_agendamento');
            $table->string('hora_agendamento');
            $table->string('numero_tarefa')->nullable();
            $table->string('imagem_cadeira')->nullable();
            $table->string('sala')->nullable();
            $table->string('status_tarefa')->nullable();
            $table->string('cod_sala')->nullable();
            $table->longText('observacao')->nullable();
            $table->longText('observacao_select')->nullable();
            $table->longText('dados');
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
        Schema::dropIfExists('posexames');
    }
}
