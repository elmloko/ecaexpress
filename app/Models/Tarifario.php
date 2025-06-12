<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarifario extends Model
{
    use SoftDeletes;

    protected $table = 'tarifario';

    protected $fillable = [
        'peso',
        'empresa',
        'local',
        'nacional',
        'camiri',
        'sud',
        'norte',
        'centro',
        'euro',
        'asia',
    ];

    protected $casts = [
        'peso'     => 'float',
        'empresa'  => 'string',
        'local'    => 'float',
        'nacional' => 'float',
        'camiri'   => 'float',
        'sud'      => 'float',
        'norte'    => 'float',
        'centro'   => 'float',
        'euro'     => 'float',
        'asia'     => 'float',
    ];
}
