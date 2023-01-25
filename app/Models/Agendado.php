<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendado extends Model
{
    use HasFactory;

    protected $fillable = ['acess_number', 'dados', 'codigo_setor_exame', 'data_agendamento', 'hora_agendamento', 'imagem_cadeira', 'observacao', 'observacao_select'];
}
