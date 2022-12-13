<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sponsor extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = 'sponsors';

    protected $fillable = [
        'enterprise_name',
        'roll',
        'size',
        'end_sponsorship',
        'social_reason',
        'rfc',
        'prefix_code_event',
        'name',
        'firstname',
        'lastname',
        'email',
        'cellphone',
        'address',
        'numE',
        'numI',
        'colony',
        'postal_code',
        'num_employees',
        'observations',
        'web_site',
        'logotype_name',
        'logotype_url',
        'id_event_adoptions',
        'id_event_workshops',
        'id_packages',
        'user'
    ];
}




