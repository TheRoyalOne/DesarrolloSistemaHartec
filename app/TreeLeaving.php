<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreeLeaving extends Model
{
    use SoftDeletes;

    protected $table = 'tree_leavings';

    protected $fillable = [
        'id',
        'adoption_id',
        'labels',
        'technical_user_id',
        'leaving_date'
    ];
}
