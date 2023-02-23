<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendAgendamentoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number;
    protected $data;
    protected $hora;
    protected $status;

    /**
     * Create a new job instance.
     *
     * @param  string  $accessToken
     * @param  array  $requestBody
     * @return void
     */
    public function __construct($number, $data, $hora, $status)
    {
        $this->number = $number;
        $this->data = $data;
        $this->hora = $hora;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $responseToken = Http::asForm()->post('https://3wingsservices.hospitalmoinhos.org.br/token', [
            'grant_type' => 'password',
            'username' => 'srv.3wings',
            'password' => 'Wings@1234'
          ]);
    
          $moinhosApi = Http::withToken($responseToken->object()->access_token)->post('https://3wingsservices.hospitalmoinhos.org.br/api/ExameAgendado/', [
          'numeroDeAcesso' => $this->number,
          'dataAgendamento' => $this->data,
          'horaAgendamento' => $this->hora,
          'status' => $this->status,
          'dataHoraMovimentacao' => "",
          'usuario' => ""
          ]);
    }
}
