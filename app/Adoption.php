<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adoption extends Model
{
    use SoftDeletes;

    protected $table = 'adoptions';

    protected $fillable = [
        'educative_institution_id',
        'sponsor_id',
        'event_id',
        'adoption_date',
        'adoption_time',
        'technical_user_id',
        'code_event',
        'name',
        'address',
        'phone',
        'email' ,
        'postal_code',
        'qr_code',
        'latitude',
        'longitude',
    ];

    public function event()
    {
       return $this->hasOne('App\Workshop','id','event_id');
    }

    public function institution()
    {
        return $this->hasOne('App\EducativeInstitution','id','educative_institution_id');
    }

    public function user()
    {
       return $this->hasOne('App\User','id','technical_user_id'); 
    }

    /**
     * The species that belong to the adoption.
     */
    public function adoptionSpecies()
    {
        return $this->belongsToMany('App\Species', 'pivot_adoption_species', 'adoption_id', 'species_id')
            ->wherePivot('deleted_at', '=', null)
            ->as('pivot_adoption_species')
            ->withPivot('species_amount');
    }
}
