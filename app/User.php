<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class User extends NeoEloquent
{
    protected $label = 'User';

    protected $fillable = ['name',
        'email',
        'password',
        'gender',
        'age',
        'fb_id',
        'google_id',
        'is_admin',
        'country'];

    public function celebrity()
    {
        return $this->hasMany(Celebrity::class,"FOLLOWS");
    }

    public function message()
    {
        return $this->hasMany(Message::class,"POSTS");
    }

    public function dislikedCelebrity()
    {
        return $this->hasMany(Celebrity::class,"DISLIKES");
    }

    public function likes(){
        return $this->hasMany(Like::class,"LIKES");
    }
}
