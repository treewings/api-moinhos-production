<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultaValidation;
use App\Models\Agendado;
use App\Models\Atendimento;
use App\Models\Finalizado;
use App\Models\Moinhos;
use App\Models\Posexame;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiltroController extends Controller
{
    public function consulta(Request $request){

        $consulta = function ($query) use ($request)
        {
            if($request->has('cod_tumografiaComputadorizada')){
                $query->orWhere('codigo_setor_exame', $request->get('cod_tumografiaComputadorizada'));
            }
            if($request->has('cod_raioX')){
                $query->orWhere('codigo_setor_exame', $request->get('cod_raioX'));
            }
            if($request->has('cod_ecografiaGeral')){
                $query->orWhere('codigo_setor_exame', $request->get('cod_ecografiaGeral'));
            }
            if($request->has('cod_ressonanciaMagnetica')){
                $query->orWhere('codigo_setor_exame', $request->get('cod_ressonanciaMagnetica'));
            }
            if($request->has('cod_centroDaMulher')){
                $query->orWhere('codigo_setor_exame', $request->get('cod_centroDaMulher'));
            }
            if($request->has('cod_igEcografiaGeral')){
                $query->orWhere('codigo_setor_exame', $request->get('cod_igEcografiaGeral'));
            }
            if($request->has('cod_radiologiaPedriatrica')){
                $query->orWhere('codigo_setor_exame', $request->get('cod_radiologiaPedriatrica'));
            }

            if($request->has('cod_nome')){
                $query->orWhere('nome_paciente', 'LIKE', '%' . $request->get('cod_nome') . '%');
            }
          
            if($request->has('cod_sala')){
                $query->where('cod_sala', $request->get('cod_sala'));
            }

        };

        if($request->has('cod_sala')){
            $moinhos = Moinhos::orderBy('data', 'asc')->get();
        }
        if($request->has('cod_tumografiaComputadorizada') || $request->has('cod_raioX') || $request->has('cod_ecografiaGeral') || $request->has('cod_ressonanciaMagnetica') || $request->has('cod_centroDaMulher') || $request->has('cod_igEcografiaGeral') || $request->has('cod_radiologiaPedriatrica') || $request->has('cod_nome')){
            $moinhos = Moinhos::orderBy('data', 'asc')->where($consulta)->get();
        }
        
        
        $agendados = Agendado::where($consulta)->get();
        $atendimentos = Atendimento::where($consulta)->get();
        $posexame = Posexame::where($consulta)->get();
        $finalizado = Finalizado::where($consulta)->where('created_at', '>=', Carbon::now()->subHours(24))->get();
        $filtro = [];
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
        $filtroSala = [];

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
        $agen['sala'] = $agend->sala ? $agend->sala : null;
        $agen['data_movimentacao'] = $agend->created_at->format('d/m/Y H:i');
        $agen['status_tarefa'] = $agend->status_tarefa ? $agend->status_tarefa : null;
        $agen['numero_tarefa'] = $agend->numero_tarefa ? $agend->numero_tarefa : null;
        $agen['imagem_cadeira'] = $agend->imagem_cadeira ? $agend->imagem_cadeira : null;
        $agen['observacao'] = $agend->observacao ? $agend->observacao : null;
        $agen['cod_sala'] = $agend->cod_sala ? $agend->cod_sala : null;
        $agen['motivo_umov'] = $agend->motivo_umov ? $agend->motivo_umov : null;
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
        $at['sala'] = $atend->sala ? $atend->sala : null;
        $at['data_movimentacao'] = $atend->created_at->format('d/m/Y H:i');
        $at['numero_tarefa'] = $atend->numero_tarefa ? $atend->numero_tarefa : null;
        $at['imagem_cadeira'] = $atend->imagem_cadeira ? $atend->imagem_cadeira : null;
        $at['observacao'] = $atend->observacao ? $atend->observacao : null;
        $at['cod_sala'] = $atend->cod_sala ? $atend->cod_sala : null;
        $filtro[$atend->codigo_setor_exame] = $at['setor_exame'];
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
        $p['sala'] = $pos->sala ? $pos->sala : null;
        $p['data_movimentacao'] = $pos->created_at->format('d/m/Y H:i');
        $p['numero_tarefa'] = $pos->numero_tarefa ? $pos->numero_tarefa : null;
        $p['status_tarefa'] = $pos->status_tarefa ? $pos->status_tarefa : null;
        $p['imagem_cadeira'] = $pos->imagem_cadeira ? $pos->imagem_cadeira : null;
        $p['observacao'] = $pos->observacao ? $pos->observacao : null;
        $p['motivo_umov'] = $pos->motivo_umov ? $pos->motivo_umov : null;
        $p['cod_sala'] = $pos->cod_sala ? $pos->cod_sala : null;
        $filtro[$pos->codigo_setor_exame] = $p['setor_exame'];
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
        ];

        return response($arrayDados, 200)->header('Retry-After', '3000');
    }
}
