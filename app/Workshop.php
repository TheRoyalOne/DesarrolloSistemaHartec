<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workshop extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "workshops";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sponsor_id',
        'educative_institution_id',
        'rec_fee_type',
        'event_id',
        'workshop_date',
        'workshop_time',
        'workshop_user_id',
        'code_event'
    ];

    /**
     * The mateirals that belong to the workshop.
     */
    public function workshopMaterials()
    {
        return $this->belongsToMany('App\WorkshopMaterial', 'pivot_material_workshop', 'workshop_id', 'workshop_material_id')
            ->wherePivot('deleted_at', '=', null)
            ->as('pivot_material_workshop')
            ->withPivot('workshop_material_amount');
    }
}
