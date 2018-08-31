<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ELECTION extends Model
{
    use SoftDeletes;

    protected $table = 'election';

    protected $fillable = [
        'election_id',
        'election_name',
        'election_start',
        'election_end'
    ];

    protected $dates = [
        'deleted_at'
    ];
}
