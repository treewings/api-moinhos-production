<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moinhos extends Model
{
    use HasFactory;

    protected $fillable = ['acess_number', 'data', 'dados', 'codigo_setor_exame', 'nome_paciente'];
    protected $dates = ['data'];
}
