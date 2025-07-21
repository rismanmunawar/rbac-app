<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataNom extends Model
{
    use HasFactory;

    protected $table = 'data_n_o_m_s';

    protected $fillable = [
        'nik',
        'name',
        'alias',
        'phone',
        'email',
        'status',
    ];
}
