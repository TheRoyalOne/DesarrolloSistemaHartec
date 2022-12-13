<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialLeaving extends Model
{
    use SoftDeletes;

    protected $table = 'material_leavings';

    protected $fillable = [
        'id',
        'workshop_id',
        'technical_user_id',
        'leaving_date'
    ];
}
