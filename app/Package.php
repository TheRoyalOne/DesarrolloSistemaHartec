<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "packages";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'workshop_event_1_id',
        'workshop_event_2_id',
        'workshop_event_3_id',
        'workshop_event_4_id',
        'adoption_event_1_id',
        'adoption_event_2_id',
        'adoption_event_3_id',
        'adoption_event_4_id'
    ];
}
