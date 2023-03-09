<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoinhosController;
use App\Http\Controllers\AgendadoController;
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\DiferencaMoinhosController;
use App\Http\Controllers\FiltroController;
use App\Http\Controllers\FinalizadoController;
use App\Http\Controllers\PosexameController;
use App\Http\Controllers\umovChecaController;
use App\Http\Controllers\UsuarioAdministradorController;
use App\Models\Finalizado;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [UsuarioAdministradorController::class, 'authenticate']);

Route::post('registrar', [UsuarioAdministradorController::class, 'register']);
Route::group(['middleware' => ['jwt.verify']], function () {
    
    

    Route::get('/moinhos', [MoinhosController::class, 'dados']);
    Route::post('/moinhos/consulta', [FiltroController::class, 'consulta']);

    // Route::get('/moinhos/diferenca', [DiferencaMoinhosController::class, 'diferenca']);
    
    Route::post('/moinhos/atualiza/agendado', [DiferencaMoinhosController::class, 'atualizaDados']);
    
    Route::post('/moinhos/agendar', [AgendadoController::class, 'agendar']);
    Route::post('/moinhos/agendar/tarefa/{id}', [AgendadoController::class, 'pegarTarefa']);
    
    
    Route::post('/moinhos/observacao/{id}', [AgendadoController::class, 'observacao']);
    
    Route::post('/moinhos/cancelar', [AgendadoController::class, 'agendarCancelar']);
    
    Route::post('/moinhos/atendimento', [AtendimentoController::class, 'atendimento']);
    Route::post('/moinhos/posexame', [PosexameController::class, 'posexame']);
    
    
    
    Route::post('/moinhos/finalizar', [FinalizadoController::class, 'finalizado']);



    Route::get('/moinhos/checaumov/agendados', [umovChecaController::class, 'dadosAgendado']);
    Route::get('/moinhos/checaumov/posexame', [umovChecaController::class, 'dadosPos']);
    Route::post('/moinhos/checaumov/limpar-dados-agendados', [umovChecaController::class, 'atualizarSolocitadosAgendado']);
    Route::post('/moinhos/checaumov/limpar-dados-posexame', [umovChecaController::class, 'atualizarSolocitadosPos']);


});
