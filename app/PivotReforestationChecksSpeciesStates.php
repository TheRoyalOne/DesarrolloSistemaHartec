<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PivotReforestationChecksSpeciesStates extends Model
{
    use SoftDeletes;
    protected $table = "pivot_reforestation_checks_species_states";
    
    protected $fillable = [
        'reforestation_check_id',
        'species_id',
        'dead_trees',
        'sick_trees'
    ];
}
