<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class nursery extends Model
{
    use SoftDeletes;

    protected $dates = ["deleted_at"];

    protected $table = "nurseries";

    protected $fillable = [
        'name',
        'responsable_user_id',
        'ubication'
    ];
}
