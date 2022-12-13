<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopMaterial extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "workshop_materials";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];
}
