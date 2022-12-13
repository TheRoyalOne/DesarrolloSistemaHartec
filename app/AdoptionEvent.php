<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdoptionEvent extends Model
{
    use SoftDeletes;
    
    protected $dates = ["deleted_at"];
    protected $table = "adoption_events";
    protected $fillable = ['name','prefix_code','description', 'trees','recovery_fee'];
    
}
