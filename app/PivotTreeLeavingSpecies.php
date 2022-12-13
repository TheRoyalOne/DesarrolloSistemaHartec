<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PivotTreeLeavingSpecies extends Model
{
    use SoftDeletes;
    protected $table = "pivot_tree_leaving_species";

    protected $fillable = [
        'tree_leaving_id',
        'species_id',
        'species_amount',
        'nursery_id'
    ];
}
