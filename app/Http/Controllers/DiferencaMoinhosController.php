<?php

namespace App\Http\Controllers;

use App\Models\Agendado;
use App\Models\Atendimento;
use App\Models\Moinhos;
use Illuminate\Http\Request;
use App\Http\Views\Moinhos as ViewsMoinhos;
use App\Models\Finalizado;
use App\Models\Posexame;
use Illuminate\Support\Facades\Artisan;

class DiferencaMoinhosController extends Controller
{
    public function diferenca(){
      
      $moinhosArray = []; 
      $view = new ViewsMoinhos();
      $view = $view->dados();
      while($dados = oci_fetch_assoc($view)){
        $moinhos = Agendado::where('acess_number', $dados['acess_number'])->get();
        $atendimento = Atendimento::where('acess_number', $dados['acess_number'])->get();
        $solicitados = Moinhos::where('acess_number', $dados['acess_number'])->get();
        $pos = Posexame::where('acess_number', $dados['acess_number'])->get();
        $fin = Finalizado::where('acess_number', $dados['acess_number'])->get();
        if(!isset($moinhos[0]) && !isset($atendimento[0]) && !isset($solicitados[0]) && !isset($pos[0]) && !isset($fin[0])){
            array_push($moinhosArray, $dados);
            if($dados != ''){
                Moinhos::create([
                    'acess_number' => $dados['acess_number'],
                    'codigo_setor_exame' => $dados['codigo_setor_exame'],
                    'data' => $dados['hora_pedidoX'],
                    'dados' =>  json_encode($dados)
                ]);
            }
        }
      }

      $arrayDados = [
        'solicitados' => $moinhosArray,
    ];

      
      return response($arrayDados, 200)->header('Retry-After', '3000');

    }

    public function atualizaDados(Request $request){
        
            $agendado = Agendado::all()->count();
            $atendimento = Atendimento::all()->count();
            $posexame = Posexame::all()->count();
            $finalizado = Finalizado::all()->count();
       
        
        if($agendado == $request->agendado && $atendimento == $request->atendimento && $posexame == $request->posexame && $finalizado == $request->finalizado){
            
            return response('', 200)->header('Retry-After', '3000');
        }else{
            
            return response('', 404)->header('Retry-After', '3000');
        }
    }


}
