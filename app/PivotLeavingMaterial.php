<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PivotLeavingMaterial extends Model
{
    use SoftDeletes;
    protected $table = "pivot_leaving_material";

    protected $fillable = [
        'material_leaving_id',
        'material_id',
        'material_amount'
    ];
}
