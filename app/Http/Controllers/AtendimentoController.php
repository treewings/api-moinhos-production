<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendarValidation;
use App\Models\Agendado;
use App\Models\Atendimento;
use App\Models\Finalizado;
use App\Models\Movimentacao;
use App\Models\Posexame;
use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    //
    public function atendimento(Request $request){

       $agendados = Agendado::where('acess_number', $request->acess_number)->first();

        Atendimento::create([
            'acess_number' => $request->acess_number,
            'nome_paciente' => $agendados->nome_paciente,
            'codigo_setor_exame' => $agendados->codigo_setor_exame,
            'data_agendamento' => $agendados->data_agendamento,
            'hora_agendamento' => $agendados->hora_agendamento,
            'sala' => $request->sala ? $request->sala : $agendados->sala,
            'cod_sala' => $request->cod_sala ? $request->cod_sala : $agendados->cod_sala,
            'observacao' => $agendados->observacao,
            'dados' => $agendados->dados
            ]);

            Movimentacao::create([
               'user' => $request->user,
               'acess_number' => $request->acess_number,
               'dados' => $agendados->dados,
               'movimentacao' => 'Atendimento'
             ]);

     
       $agendados->delete();

        return response([], 200);
     }
}
