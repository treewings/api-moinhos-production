<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Movimentacao;
use App\Models\Posexame;
use Illuminate\Http\Request;

class PosexameController extends Controller
{
    public function posexame(Request $request){
        $atendimento = Atendimento::where('acess_number', $request->acess_number)->first();
 
        if($atendimento){
         Posexame::create([
             'acess_number' => $atendimento->acess_number,
             'nome_paciente' => $atendimento->nome_paciente,
             'codigo_setor_exame' => $atendimento->codigo_setor_exame,
             'data_agendamento' => $atendimento->data_agendamento,
             'hora_agendamento' => $atendimento->hora_agendamento,
             'sala' => $atendimento->sala,
             'cod_sala' => $atendimento->cod_sala,
             'observacao' => $atendimento->observacao,
             'dados' => $atendimento->dados
             ]);


             Movimentacao::create([
                'user' => $request->user,
                'acess_number' => $request->acess_number,
                'dados' => $atendimento->dados,
                'movimentacao' => 'PosExame'
              ]);
        }else{
             return response('', 404)->header('Retry-After', '3000');
        }
      
 
 
        $atendimento->delete();
 
         return response([], 200)->header('Retry-After', '3000');
      }
}
