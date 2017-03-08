<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Like extends NeoEloquent
{
    protected $label = 'Like';

    protected $fillable = ['score'];

    public function User(){
        return $this->belongsTo(User::class,"LIKES");
    }

    public function category(){
        return $this->hasOne(Category::class,'LIKED');
    }
}
