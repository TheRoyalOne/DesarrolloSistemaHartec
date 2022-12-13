<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReforestationChecks extends Model
{
    use SoftDeletes;

    protected $table = 'reforestation_checks';

    protected $fillable = [
        'id',
        'adoption_id',
        'technical_user_id',
        'check_date'
    ];
}
