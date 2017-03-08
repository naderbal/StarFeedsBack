<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;


class FacebookFeed extends NeoEloquent
{
    protected $label = 'FacebookFeed';
    protected $fillable = ['feed_id','celeb_id','feed_type','created_time','text','link','image_url'];
    public $timestamps = false;
    public function celeb(){
        return $this->belongsTo(Celebrity::class,'POSTED');
    }

}
