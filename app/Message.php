<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Message extends NeoEloquent
{
    protected $label = 'Message';

    protected $fillable = ['message'];

    public function celebrity()
    {
        return $this->belongsToOne(User::class,"POSTED");
    }

}
