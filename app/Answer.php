<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "answers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'text',
        'id_question',
        'id_next_question'
    ];
}
