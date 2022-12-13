<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducativeInstitution extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "educative_institutions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institution_name',
        'name',
        'firstname',
        'lastname',
        'email',
        'cellphone',
        'institutional_charge',
        'institutional_email',
        'institutional_phone',
        'address',
        'numE',
        'numI',
        'colony',
        'postal_code',
        'observations',
        'website',
        'logotype_name',
        'logotype_url'
    ];
}
