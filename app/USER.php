<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class USER extends Model
{
    use SoftDeletes;

    protected $table = 'user';

    protected $fillable = [
        'ticket',
        'is_admin'
    ];

    protected $dates = [
        'deleted_at'
    ];
}
