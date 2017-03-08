<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class TwitterFeed extends NeoEloquent
{
    protected $label = 'TwitterFeed';
    public $timestamps = false;

    protected $fillable = ['feed_id','celeb_id','feed_type','created_time', 'text','link','image_url'];

    public function celeb(){
        return $this->belongsTo(Celebrity::class,'TWEETED');
    }


}
