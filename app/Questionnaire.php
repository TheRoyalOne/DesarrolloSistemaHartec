<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaire extends Model
{
    //use SoftDeletes;

    protected $table = 'questionnaire';

    protected $fillable = [
        'id',
        'id_question_key',
        'id_sponsor',
        'id_buyer',
        'id_event'
    ];
}
