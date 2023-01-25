<?php

namespace App\Http\Controllers;

use App\Models\Finalizado;
use App\Models\Movimentacao;
use App\Models\Posexame;
use Illuminate\Http\Request;

class FinalizadoController extends Controller
{
    public function finalizado(Request $request){
        $posExame = Posexame::where('acess_number', $request->acess_number)->first();
 
        if($posExame){
         Finalizado::create([
            'acess_number' => $request->acess_number,
            'codigo_setor_exame' => $posExame->codigo_setor_exame,
            'data_agendamento' => $posExame->data_agendamento,
            'hora_agendamento' => $posExame->hora_agendamento,
            'sala' => $posExame->sala,
            'cod_sala' => $posExame->cod_sala,
            'observacao' => $posExame->observacao,
            'dados' => $posExame->dados
             ]);

             Movimentacao::create([
               'user' => $request->user,
               'acess_number' => $request->acess_number,
               'dados' => $posExame->dados,
               'movimentacao' => 'Finalizado'
             ]);
        }else{
             return response('', 404)->header('Retry-After', '3000');
        }
      
 
 
        $posExame->delete();
 
         return response([], 200)->header('Retry-After', '3000');
      }
}
