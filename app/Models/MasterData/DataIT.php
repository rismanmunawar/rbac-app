<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class DataIT extends Model
{
    protected $table = 'data_i_t_s';

    protected $fillable = [
        'nik',
        'name',
        'alias',
        'designation',
        'image',
        'phone',
        'email',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
