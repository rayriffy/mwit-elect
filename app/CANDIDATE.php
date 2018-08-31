<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CANDIDATE extends Model
{
    use SoftDeletes;

    protected $table = 'candidate';

    protected $fillable = [
        'candidate_id',
        'election_id',
        'candidate_name'
    ];

    protected $dates = [
        'deleted_at'
    ];
}
