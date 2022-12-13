<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PivotAdoptionSpecies extends Model
{
    use SoftDeletes;
    protected $table = "pivot_adoption_species";

    protected $fillable = [
        'adoption_id',
        'species_id',
        'species_amount',
    ];
}
