<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posexame extends Model
{
    use HasFactory;

    protected $fillable = ['acess_number', 'dados', 'codigo_setor_exame', 'data_agendamento', 'hora_agendamento', 'sala', 'observacao', 'cod_sala', 'observacao_select'];
}
