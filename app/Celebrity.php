<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Celebrity extends NeoEloquent
{
    protected $label = 'Celebrity';

    protected $fillable = ['name','followers' ,'fb_id','fb_profile_url','twt_id','instagram_id'];

    public function user(){
        return $this->belongsToMany(User::class,'FOLLOWS');
    }

    public function category(){
        return $this->hasMany(Category::class,'BELONGS_TO');
    }

    public function facebookFeed(){
        return $this->hasMany(FacebookFeed::class,'POSTED');
    }

    public function twitterFeed(){
        return $this->hasMany(TwitterFeed::class, 'TWEETED');
    }

    public function instagramFeed(){
        return $this->hasMany(InstagramFeed::class, 'POSTED');
    }

}
