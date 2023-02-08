<?php

namespace App\Http\Controllers;

use App\Models\Agendado;
use App\Models\Posexame;
use Illuminate\Http\Request;

class umovChecaController extends Controller
{
    public function dadosAgendado(){
        
        $agendados =Agendado::all();
      
       
        $umovCheca = [];

        foreach($agendados as $agend){
         $agen = json_decode($agend->dados, true);
         $agen['status_tarefa'] = $agend->status_tarefa ? $agend->status_tarefa : null;
         $agen['numero_tarefa'] = $agend->numero_tarefa ? $agend->numero_tarefa : null;
         if($agen['numero_tarefa'] != null && $agen['status_tarefa'] != '50' && $agen['status_tarefa'] != '70'){
             $umovCheca[] = $agen;
         }
        }
 
       
 
         $arrayDados = [
             'umovCheca' => $umovCheca ? $umovCheca : null 
         ];
 
         return response($arrayDados, 200);
    }

    public function dadosPos(){

        $posexame = Posexame::all();
 
        $umovCheca = [];


        foreach($posexame as $pos){
            $p = json_decode($pos->dados, true);
            $p['numero_tarefa'] = $pos->numero_tarefa ? $pos->numero_tarefa : null;
            $p['status_tarefa'] = $pos->status_tarefa ? $pos->status_tarefa : null;
            if($p['numero_tarefa'] != null && $p['status_tarefa'] != '50' && $p['status_tarefa'] != '70'){
                $umovCheca[] = $pos;
            }
           }

           $arrayDados = [
            'umovCheca' => $umovCheca ? $umovCheca : null 
        ]; 

        return response($arrayDados, 200);
    }


    public function atualizarSolocitadosAgendado(Request $request){
      $agendados =  Agendado::where('acess_number', $request->acess_number)->first();
      $agendados->numero_tarefa = null;
      $agendados->status_tarefa = null;
      $agendados->imagem_cadeira = 'cadeira-de-rodas-preto.png';
      $agendados->motivo_umov = $request->motivo;
      $agendados->sala = null;
      $agendados->cod_sala = null;
      $agendados->save();

      return response('', 200);
    }
    
    public function atualizarSolocitadosPos(Request $request){
      $agendados =  Posexame::where('acess_number', $request->acess_number)->first();
      $agendados->numero_tarefa = null;
      $agendados->status_tarefa = null;
      $agendados->motivo_umov = $request->motivo;
      $agendados->imagem_cadeira = 'cadeira-de-rodas-preto.png';
      $agendados->save();

      return response('', 200);
    }
}
