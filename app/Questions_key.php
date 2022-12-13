<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questions_key extends Model
{
    //use SoftDeletes;

    protected $table = 'questions_key';

    protected $fillable = [
        'id',
        'id_next',
        'id_question',
        'id_answer'
    ];
}
