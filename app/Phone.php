<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Phone extends NeoEloquent
{
    protected $label = 'Phone';

    protected $fillable = ['code', 'number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
