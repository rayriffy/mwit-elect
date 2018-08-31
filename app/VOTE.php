<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VOTE extends Model
{
    use SoftDeletes;

    protected $table = 'vote';

    protected $fillable = [
        'vote_id',
        'ticket_id',
        'election_id',
        'candidate_id',
        'prev_id'
    ];

    protected $dates = [
        'deleted_at'
    ];
}
