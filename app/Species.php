<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Species extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "species";

    protected $fillable = [
        'name',
        'scientific_name',                         
        'recovery_fee_a',
        'recovery_fee_b',
        'recovery_fee_c',
        'recovery_fee_d',
        'spec_1',
        'spec_2',
        'spec_3',
        'spec_4',
        'spec_5',
        'spec_6',
        'observations',
        'picture_name',
        'picture_url'
    ];

}
