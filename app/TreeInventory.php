<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreeInventory extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    protected $table = "tree_inventory";
    protected $fillable = ['id_nurserie', 'id_species', 'amount', 'age'];

    public function nurseries(){
        return $this->hasOne('App\Nursery','id','id_nurserie');
    }
    
    public function species(){
        return $this->hasOne('App\Species','id','id_species');
    }
}
