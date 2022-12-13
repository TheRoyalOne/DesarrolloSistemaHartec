<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PivotMaterialWorkshop extends Model
{
    use SoftDeletes;
    protected $table = "pivot_material_workshop";

    protected $fillable = [
        'workshop_id',
        'workshop_material_id',
        'workshop_material_amount',
    ];
}
