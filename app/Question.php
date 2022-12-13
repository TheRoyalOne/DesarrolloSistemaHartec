<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "questions";

    protected $fillable = ['sentence', 'first_question'];
}
