<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class TypeEvent extends Model
{
    use SoftDeletes;
    
    protected $data = ["deleted_at"];
    protected $table = "type_event";
    protected $fillable = ["name", "description"];
}
