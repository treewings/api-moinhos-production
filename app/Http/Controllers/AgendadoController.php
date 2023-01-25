<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendarValidation;
use App\Models\Agendado;
use App\Models\Atendimento;
use App\Models\Finalizado;
use App\Models\Moinhos;
use App\Models\Movimentacao;
use App\Models\Posexame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AgendadoController extends Controller
{
     public function agendar(AgendarValidation $request){

      $responseToken = Http::asForm()->post('https://3wingsservices.hospitalmoinhos.org.br/token', [
        'grant_type' => 'password',
        'username' => 'srv.3wings',
        'password' => 'Wings@1234'
      ]);

      $moinhosApi = Http::withToken($responseToken->object()->access_token)->post('https://3wingsservices.hospitalmoinhos.org.br/api/ExameAgendado/', [
      'numeroDeAcesso' => $request->acess_number,
      'dataAgendamento' => $request->data_agendamento,
      'horaAgendamento' => $request->hora_agendamento,
      'status' => "R",
      'dataHoraMovimentacao' => "",
      'usuario' => ""
      ]);

      
      $remove = Moinhos::where('acess_number', $request->acess_number)->first();
      if($remove){
        $remove->delete();
         $d = Agendado::create([
            'acess_number' => $request->acess_number,
            'codigo_setor_exame' => $request->codigo_setor_exame,
            'data_agendamento' => $request->data_agendamento,
            'hora_agendamento' => $request->hora_agendamento,
            'imagem_cadeira' => $request->imagem_cadeira,
            'dados' => json_encode($request->dados)
        ]);

        Movimentacao::create([
          'user' => $request->user,
          'acess_number' => $request->acess_number,
          'dados' => json_encode($request->dados),
          'movimentacao' => 'Agendado'
        ]);
      }
      

        return response([], 200)->header('Retry-After', '3000');
     }

     public function pegarTarefa(Request $request, $id){
        if($request->origem == 'agendado'){
          $agendado = Agendado::where('acess_number', $id)->first();
          $agendado->sala = $request->sala;
          $agendado->cod_sala = $request->cod_sala;
        }else{
          $agendado = Posexame::where('acess_number', $id)->first();
        }
        
        $agendado->numero_tarefa = $request->numero_tarefa;
        $agendado->imagem_cadeira = $request->imagem_cadeira;
        $agendado->status_tarefa = $request->status_tarefa;
      
        $agendado->save();

        return response('', 200)->header('Retry-After', '3000');
     }


     public function observacao(Request $request, $id){
      if($request->origem == 'agendado'){
        $agendado = Agendado::where('acess_number', $id)->first();
      }else if($request->origem == 'posexame'){
        $agendado = Posexame::where('acess_number', $id)->first();
      }else if($request->origem == 'atendimento'){
        $agendado = Atendimento::where('acess_number', $id)->first();
      }else if($request->origem == 'finalizado'){
        $agendado = Finalizado::where('acess_number', $id)->first();
      }
      
      $agendado->observacao = $request->observacao;
      $agendado->observacao_select = $request->observacao_select;
      $agendado->save();
      

      return response('', 200)->header('Retry-After', '3000');
   }

     public function agendarCancelar(Request $request){

        Moinhos::create([
          'acess_number' => $request->acess_number,
          'codigo_setor_exame' => $request->codigo_setor_exame,
          'data' => $request->hora_pedidoX,
          'dados' => json_encode($request->dados) 
        ]);

        if($request->identificacao == 1){

          $dados = Agendado::where('acess_number', $request->acess_number)->first();
          $responseToken = Http::asForm()->post('https://3wingsservices.hospitalmoinhos.org.br/token', [
            'grant_type' => 'password',
            'username' => 'srv.3wings',
            'password' => 'Wings@1234'
          ]);
    
          $moinhosApi = Http::withToken($responseToken->object()->access_token)->post('https://3wingsservices.hospitalmoinhos.org.br/api/ExameAgendado/', [
          'numeroDeAcesso' => $request->acess_number,
          'dataAgendamento' => $dados->data_agendamento,
          'horaAgendamento' => $dados->hora_agendamento,
          'status' => "C",
          'dataHoraMovimentacao' => "",
          'usuario' => ""
          ]);

          $dados->delete();
        }

        if($request->identificacao == 2){
          $dados = Atendimento::where('acess_number', $request->acess_number)->first();
          $dados->delete();
        }

        if($dados){
          return response('', 200)->header('Retry-After', '3000');
        }
     }
}
