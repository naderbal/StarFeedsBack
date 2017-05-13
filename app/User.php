<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class User extends NeoEloquent
{
    protected $label = 'User';

    protected $fillable = ['name','email','password','gender','age','fb_id','is_admin'];

    public function celebrity()
    {
        return $this->hasMany(Celebrity::class,"FOLLOWS");
    }

    public function dislikedCelebrity()
    {
        return $this->hasMany(Celebrity::class,"DISLIKES");
    }

    public function likes(){
        return $this->hasMany(Like::class,"LIKES");
    }
}
