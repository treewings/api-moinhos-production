<?php

namespace App\Http\Controllers;

use App\Http\Views\Moinhos as ViewsMoinhos;
use App\Models\Agendado;
use App\Models\Atendimento;
use App\Models\Finalizado;
use App\Models\Moinhos;
use App\Models\Posexame;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class MoinhosController extends Controller
{
    public function dados()
    {
        $remove = Moinhos::all();

        foreach($remove as $dados){
                $dados->delete();
        }

        $view = new ViewsMoinhos();
        $view = $view->dados();
    
       while($dados = oci_fetch_assoc($view)){
            $moinhos = Agendado::where('acess_number', $dados['acess_number'])->get();
            $atendimento = Atendimento::where('acess_number', $dados['acess_number'])->get();
            $posExame = Posexame::where('acess_number', $dados['acess_number'])->get();
            $finalizado = Finalizado::where('acess_number', $dados['acess_number'])->get();

            if(!isset($moinhos[0]) && !isset($atendimento[0]) && !isset($posExame[0]) && !isset($finalizado[0]) && $dados['sn_cancelado'] != 'S'){
                Moinhos::create([
                    'acess_number' => $dados['acess_number'],
                    'codigo_setor_exame' => $dados['codigo_setor_exame'],
                    'data' =>  $dados['hora_pedidoX'],
                    'dados' => json_encode($dados)
                ]);
            }
       }

       $filtro = [];
       $umovCheca = [];
       $filtroSala = [];

       $moinhos = Moinhos::orderBy('data', 'asc')->get();
       $agendados = Agendado::orderByDesc('created_at')->get();
       $atendimentos = Atendimento::all();
       $posexame = Posexame::all();
       $finalizado = Finalizado::all();
       
       


       $Atualizado = [];
       $dataAtual = new DateTime();
       foreach($moinhos as $dadosAtulizado){
            $setor = json_decode($dadosAtulizado->dados, true);
            $hora = $dataAtual->diff($dadosAtulizado->data);
            $horas = '';
            $minutos = '';
            $mes = '';
            if($hora->m == 0){
                $mes = '';
            }else{
                $mes = $hora->m.'m ';
            }
            if(strlen($hora->h) == 1){
                $horas = '0'.$hora->h;
            }else{
                $horas = $hora->h;
            }
            
            if(strlen($hora->i) == 1){
                $minutos = '0'.$hora->i;
            }else{
                $minutos = $hora->i;
            }
            
            $setor['data_diferenca'] = $mes.$hora->d.'d '.$horas.':'.$minutos;
            $filtro[$dadosAtulizado->codigo_setor_exame] = $setor['setor_exame'];
            array_push($Atualizado, $setor);
       }

       $Agendado = [];
       foreach($agendados as $agend){
        $agen = json_decode($agend->dados, true);
        $filtro[$agend->codigo_setor_exame] = $agen['setor_exame'];
        if($agend->cod_sala){
            $filtroSala[$agend->cod_sala] = $agend->sala;
            
        }

        $hora = $dataAtual->diff($agend->updated_at);
        $horas = '';
        $minutos = '';
        $mes = '';
        if($hora->m == 0){
            $mes = '';
        }else{
            $mes = $hora->m.'m ';
        }
        if(strlen($hora->h) == 1){
            $horas = '0'.$hora->h;
        }else{
            $horas = $hora->h;
        }
        
        if(strlen($hora->i) == 1){
            $minutos = '0'.$hora->i;
        }else{
            $minutos = $hora->i;
        }
        
        $agen['data_diferenca'] = $mes.$hora->d.'d '.$horas.':'.$minutos;
        $agen['data_agendamento'] = $agend->data_agendamento;
        $agen['hora_agendamento'] = $agend->hora_agendamento;
        $agen['observacao_select'] = $agend->observacao_select;
        $agen['data_movimentacao'] = $agend->created_at->format('d/m/Y H:i');
        $agen['sala'] = $agend->sala ? $agend->sala : null;
        $agen['status_tarefa'] = $agend->status_tarefa ? $agend->status_tarefa : null;
        $agen['numero_tarefa'] = $agend->numero_tarefa ? $agend->numero_tarefa : null;
        $agen['imagem_cadeira'] = $agend->imagem_cadeira ? $agend->imagem_cadeira : null;
        $agen['observacao'] = $agend->observacao ? $agend->observacao : null;
        $agen['cod_sala'] = $agend->cod_sala ? $agend->cod_sala : null;
        if($agen['numero_tarefa'] != null && $agen['status_tarefa'] != '50' && $agen['status_tarefa'] != '70'){
            $umovCheca[] = $agen;
        }
        array_push($Agendado, $agen);
       }

       $Atendimento = [];
       foreach($atendimentos as $atend){
        $at = json_decode($atend->dados, true);
        if($atend->cod_sala){
            $filtroSala[$atend->cod_sala] = $atend->sala;
        }
        $hora = $dataAtual->diff($atend->updated_at);
        $horas = '';
        $minutos = '';
        $mes = '';
        if($hora->m == 0){
            $mes = '';
        }else{
            $mes = $hora->m.'m ';
        }
        if(strlen($hora->h) == 1){
            $horas = '0'.$hora->h;
        }else{
            $horas = $hora->h;
        }
        
        if(strlen($hora->i) == 1){
            $minutos = '0'.$hora->i;
        }else{
            $minutos = $hora->i;
        }
        
        $at['data_diferenca'] = $mes.$hora->d.'d '.$horas.':'.$minutos;
        $at['data_agendamento'] = $atend->data_agendamento;
        $at['hora_agendamento'] = $atend->hora_agendamento;
        $at['observacao_select'] = $atend->observacao_select;
        $at['data_movimentacao'] = $atend->created_at->format('d/m/Y H:i');
        $at['sala'] = $atend->sala ? $atend->sala : null;
        $at['numero_tarefa'] = $atend->numero_tarefa ? $atend->numero_tarefa : null;
        $at['imagem_cadeira'] = $atend->imagem_cadeira ? $atend->imagem_cadeira : null;
        $at['observacao'] = $atend->observacao ? $atend->observacao : null;
        $at['cod_sala'] = $atend->cod_sala ? $atend->cod_sala : null;
        $filtro[$atend->codigo_setor_exame] = $at['setor_exame'];
        if($at['numero_tarefa'] != null){
            $umovCheca[] = $agen;
        }
        array_push($Atendimento, $at);
       }


       $posDados = [];

       foreach($posexame as $pos){
        $p = json_decode($pos->dados, true);
        if($pos->cod_sala){
            $filtroSala[$pos->cod_sala] = $pos->sala;
        }
        $hora = $dataAtual->diff($pos->updated_at);
        $horas = '';
        $minutos = '';
        $mes = '';
        if($hora->m == 0){
            $mes = '';
        }else{
            $mes = $hora->m.'m ';
        }
        if(strlen($hora->h) == 1){
            $horas = '0'.$hora->h;
        }else{
            $horas = $hora->h;
        }
        
        if(strlen($hora->i) == 1){
            $minutos = '0'.$hora->i;
        }else{
            $minutos = $hora->i;
        }
        
        $p['data_diferenca'] = $mes.$hora->d.'d '.$horas.':'.$minutos;
        $p['data_agendamento'] = $pos->data_agendamento;
        $p['hora_agendamento'] = $pos->hora_agendamento;
        $p['observacao_select'] = $pos->observacao_select;
        $p['data_movimentacao'] = $pos->created_at->format('d/m/Y H:i');
        $p['sala'] = $pos->sala ? $pos->sala : null;
        $p['numero_tarefa'] = $pos->numero_tarefa ? $pos->numero_tarefa : null;
        $p['status_tarefa'] = $pos->status_tarefa ? $pos->status_tarefa : null;
        $p['imagem_cadeira'] = $pos->imagem_cadeira ? $pos->imagem_cadeira : null;
        $p['observacao'] = $pos->observacao ? $pos->observacao : null;
        $p['cod_sala'] = $pos->cod_sala ? $pos->cod_sala : null;
        $filtro[$pos->codigo_setor_exame] = $p['setor_exame'];
        if($p['numero_tarefa'] != null && $p['status_tarefa'] != '50' && $p['status_tarefa'] != '70'){
            $umovCheca[] = $pos;
        }
        array_push($posDados, $p);
       }


       $finDados = [];

       foreach($finalizado as $fin){
        $f = json_decode($fin->dados, true);
        if($fin->cod_sala){
            $filtroSala[$fin->cod_sala] = $fin->sala;
        }
        $hora = $dataAtual->diff($fin->updated_at);
        $horas = '';
        $minutos = '';
        $mes = '';
        if($hora->m == 0){
            $mes = '';
        }else{
            $mes = $hora->m.'m ';
        }
        if(strlen($hora->h) == 1){
            $horas = '0'.$hora->h;
        }else{
            $horas = $hora->h;
        }
        
        if(strlen($hora->i) == 1){
            $minutos = '0'.$hora->i;
        }else{
            $minutos = $hora->i;
        }
        
        $f['data_diferenca'] = $mes.$hora->d.'d '.$horas.':'.$minutos;
        $f['data_agendamento'] = $fin->data_agendamento;
        $f['hora_agendamento'] = $fin->hora_agendamento;
        $f['observacao_select'] = $fin->observacao_select;
        $f['data_movimentacao'] = $fin->created_at->format('d/m/Y H:i');
        $f['sala'] = $fin->sala ? $fin->sala : null;
        $f['numero_tarefa'] = $fin->numero_tarefa ? $fin->numero_tarefa : null;
        $f['status_tarefa'] = $fin->status_tarefa ? $fin->status_tarefa : null;
        $f['imagem_cadeira'] = $fin->imagem_cadeira ? $fin->imagem_cadeira : null;
        $f['observacao'] = $fin->observacao ? $fin->observacao : null;
        $f['cod_sala'] = $fin->cod_sala ? $fin->cod_sala : null;
        $filtro[$fin->codigo_setor_exame] = $f['setor_exame'];
        if($f['numero_tarefa'] != null && $f['status_tarefa'] != '50' && $f['status_tarefa'] != '70'){
            $umovCheca[] = $fin;
        }
        array_push($finDados, $f);
       }


        $arrayDados = [
            'solicitados' => $Atualizado,
            'agendados' => $Agendado,
            'atendimento' => $Atendimento,
            'pos_exame' => $posDados,
            'finalizados' => $finDados,
            'count' => [
                'total_solicitatos' => count($Atualizado),
                'total_agendados' => count($Agendado),
                'total_pos_exame' => count($posDados),
                'total_atendimento' => count($Atendimento),
                'total_finalizados' => count($finDados)
            ],
            'filtro' => $filtro,
            'filtroSala' => $filtroSala,
            'umovCheca' => $umovCheca
        ];

        return response($arrayDados, 200)->header('Retry-After', '3000');
    }
}
