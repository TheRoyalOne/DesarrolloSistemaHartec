<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Spec extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table = "specs";

    protected $fillable = [
        'name',
        'spec_frut1',
        'spec_frut2',
        'spec_frut3',
        'spec_orn1',
        'spec_orn2',
        'spec_orn3',
        'spec_conymad1',
        'spec_conymad2',
        'spec_conymad3',
        'spec_hojacad1',
        'spec_hojacad2',
        'spec_hojacad3',
        'spec_banq1',
        'spec_banq2',
        'spec_banq3',
        'spec_llan1',
        'spec_llan2',
        'spec_llan3',
        'spec_mac1',
        'spec_mac2',
        'spec_mac3',
        'spec_azotea1',
        'spec_azotea2',
        'spec_azotea3',
        'spec_int1',
        'spec_int2',
        'spec_int3',
        'spec_ext1',
        'spec_ext2',
        'spec_ext3',
        'spec_plant1',
        'spec_plant2',
        'spec_plant3',
        'spec_suc1',
        'spec_suc2',
        'spec_suc3'
    ];
}
