<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyers extends Model
{
    use SoftDeletes;
    protected $table = "buyers";

    protected $fillable = [
        'id',
        'id_adoption',
        'name',
        'phone',
        'mail',
        'address',
        'suburb',
        'cp',
        'id_specie',
        'latitude',
        'length'
    ];
}
