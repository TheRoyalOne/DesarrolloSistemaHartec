<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class WorkshopEvent extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "events";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'prefix_code', 
        'description', 
        'rec_fee_online',
        'rec_fee_presencial',
        'rec_fee_business',
        'rec_fee_online_kits',
        'donation',
    ];
}
