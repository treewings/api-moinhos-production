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

       if($request->origem == 'posexame'){
          $pos = Posexame::where('acess_number', $request->acess_number)->first();

          if($pos){
               Finalizado::create([
                   'acess_number' => $request->acess_number,
                   'codigo_setor_exame' => $pos->codigo_setor_exame,
                   'data_agendamento' => $pos->data_agendamento,
                   'hora_agendamento' => $pos->hora_agendamento,
                   'sala' => $request->sala ? $request->sala : $pos->sala,
                   'cod_sala' => $request->cod_sala ? $request->cod_sala : $pos->cod_sala,
                   'observacao' => $pos->observacao,
                   'dados' => $pos->dados
                   ]);

                   Movimentacao::create([
                    'user' => $request->user,
                    'acess_number' => $request->acess_number,
                    'dados' => $pos->dados,
                    'movimentacao' => 'Finalizado'
                  ]);
              $pos->delete();     
               return response([], 200)->header('Retry-After', '3000');
          }else{
                   return response('', 404)->header('Retry-After', '3000');
          }
       }

       $agendados = Agendado::where('acess_number', $request->acess_number)->first();

       if($agendados){
        Atendimento::create([
            'acess_number' => $request->acess_number,
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

       }else{
            return response('', 404)->header('Retry-After', '3000');
       }
     


       $agendados->delete();

        return response([], 200)->header('Retry-After', '3000');
     }
}
