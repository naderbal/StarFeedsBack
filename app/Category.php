<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Category extends NeoEloquent
{
    protected $label = 'Category';

    protected $fillable = ['category'];

    public function celebrities(){
       return $this->belongsToMany(Celebrity::class,'BELONGS_TO');
    }

    public function likes(){
        return $this->belongsToMany(Like::class,'LIKED');
    }
}
