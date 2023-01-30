<?php

namespace App\Console;

use App\Http\Views\Moinhos;
use App\Models\Agendado;
use App\Models\Atendimento;
use App\Models\Finalizado;
use App\Models\Moinhos as ModelsMoinhos;
use App\Http\Views\Moinhos as ViewsMoinhos;
use App\Models\Posexame;
use DateTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function (){
            $view = new ViewsMoinhos();
            $view = $view->dados();
            while($dados = oci_fetch_assoc($view)){
              $moinhos = Agendado::where('acess_number', $dados['acess_number'])->get();
              $atendimento = Atendimento::where('acess_number', $dados['acess_number'])->get();
              $solicitados = ModelsMoinhos::where('acess_number', $dados['acess_number'])->get();
              $pos = Posexame::where('acess_number', $dados['acess_number'])->get();
              $fin = Finalizado::where('acess_number', $dados['acess_number'])->get();
              if(!isset($moinhos[0]) && !isset($atendimento[0]) && !isset($solicitados[0]) && !isset($pos[0]) && !isset($fin[0])){
                  if($dados != ''){
                      ModelsMoinhos::create([
                          'acess_number' => $dados['acess_number'],
                          'codigo_setor_exame' => $dados['codigo_setor_exame'],
                          'data' => $dados['hora_pedidoX'],
                          'dados' =>  json_encode($dados)
                      ]);
                  }
              }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
