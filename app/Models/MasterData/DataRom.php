<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class DataRom extends Model
{
    protected $table = 'data_r_o_m_s';

    protected $fillable = [
        'nik',
        'name',
        'alias',
        'phone',
        'email',
        'status',
    ];
}
