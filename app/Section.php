<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = "sections";
    public function sub_section()
    {
        return $this->hasMany('App\Section','padre')->where('type','SUBSECTION');
    }
    public function module()
    {
        return $this->hasMany('App\Section','padre')->where('type','MODULE');
    }
}
