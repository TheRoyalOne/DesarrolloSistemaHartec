<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reforestation extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    protected $table = "reforestation_events";
    protected $fillable =['name_reforesta', 'prefix_code_reforesta','recovery_fee_reforesta','description_reforesta'];
}
