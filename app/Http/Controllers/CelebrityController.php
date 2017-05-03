<?php

namespace App\Http\Controllers;

use App\Category;
use App\Celebrity;
use App\FacebookFeed;
use App\InstagramFeed;
use App\Post;
use App\TwitterFeed;
use App\User;
use ErrorException;
use Illuminate\Http\Request;

use App\Http\Requests;

class CelebrityController extends Controller
{
    public $FACEBOOK_TAG = "Facebook";
    public $TWITTER_TAG = "Twitter";
    public $INSTAGRAM_TAG = "Instagram";

    public function getCelebsByName($celebName, $userId)
    {
        $celebsSearched = [];
        $isFollowed = false;
        $celebsFollowed = User::find($userId)->celebrity;
        $celebs = Celebrity::where('name','=',$celebName)->get();
        //todo ask kinane like instead of equals
        foreach($celebs as $celeb){
            if($celebsFollowed->contains($celeb)) $isFollowed = true;
            else $isFollowed = false;
            $cel = ["is_followed" => $isFollowed,"celeb" => $celeb];
            array_push($celebsSearched,$cel);
        }
        return ["data"=>$celebsSearched];
    }

    public function getCelebsByCategory($categoryId)
    {
        $celebsOfCategory = [];
        $category = Category::find($categoryId);
        $celebs = Celebrity::all();
        foreach($celebs as $celeb){
            $categories = $celeb->category;
            foreach($categories as $cat){
                if($cat == $category){
                    array_push($celebsOfCategory,$celeb);
                }
            }
        }
        return $celebsOfCategory;
    }

    public function getCelebsByCountry($countryId)
    {

    }

    public function getCeleb($celebId, $userId)
    {
        $isFollowed = false;
        $celeb = Celebrity::find($celebId);
        $celebsFollowed = User::find($userId)->celebrity;
        $cel = [];
        if($celebsFollowed->contains($celeb)) $isFollowed = true;
        else $isFollowed = false;

        $cel = ["is_followed" => $isFollowed,"celeb" => $celeb];
        return ["data"=>$cel];
    }

    public function getCelebFeeds($celebId){
        $celeb = Celebrity::find($celebId);
        if($celeb == null) return [];
        $posts = [];
        //get all FacebookFeeds of the celebrity
        $celebFbFeeds = FacebookFeed::where('celeb_id','=',$celeb->id)->get();
        foreach($celebFbFeeds as $feed){
            $platform = $this->FACEBOOK_TAG;
            $post = new Post($feed, $platform, $celeb);
            array_push($posts,$post);
        }

        //get all TwitterFeeds of the celebrity
        $celebTwitterFeeds = TwitterFeed::where('celeb_id','=',$celeb->id)->get();
        foreach($celebTwitterFeeds as $feed){
            $celebName = $celeb->name;
            $platform = $this -> TWITTER_TAG;
            $post = new Post($feed, $platform, $celeb);
            array_push($posts,$post);
        }

        //get all InstagramFeeds of the celebrity
        $celebInstagramFeeds = InstagramFeed::where('celeb_id','=',$celeb->id)->get();
        foreach($celebInstagramFeeds as $feed){
            $celebName = $celeb->name;
            $platform = $this -> INSTAGRAM_TAG;
            $post = new Post($feed, $platform, $celeb);
            array_push($posts,$post);
        }

        //sort the posts by timestamp
        usort($posts, array($this, 'cmp'));
        return $posts;
    }

    /**
     * Comparator function, which compares the timestamps of two passed Posts
     */
    public static function cmp($post1, $post2)
    {
        return strcmp($post2->timestamp, $post1->timestamp);
    }


    public function addCeleb(Request $request){
        $name = $request->input("name");
        $fb_id = $request->input("fb_id");
        $twt_id = $request->input("twt_id");
        $instagram_id = $request->input("instagram_id");
        $category = $request->input("category");
        /*if (count(Celebrity::where(['fb_id','=',$fb_id])->get()) > 0){
            return;
        }*/
        $fbProfilePic = null;
        if($fb_id != null){
            $fbProfilePic = $this->getFacebookProfilePicture($fb_id);
        }
        $celeb = new Celebrity([
                "name" => $name,
                "followers" => 0,
                "fb_id" => $fb_id,
                "fb_profile_url"=>$fbProfilePic,
                "twt_id" => $twt_id,
                "instagram_id" => $instagram_id
            ]
        );

        $categoryVar = Category::where("category",'=',$category)->get()->first();
        if(!$categoryVar) {
            $categoryVar = new Category(["category" => $category]);
            $categoryVar->save();
        }
        $celeb->save();
        $celeb->category()->save($categoryVar);
    }

    public function getFacebookProfilePicture($id){
        $url = "https://graph.facebook.com/v2.8/$id/picture?debug=all&format=json&method=get&pretty=0&redirect=false&suppress_http_code=1";

        try {
            $result = file_get_contents($url);
            $decoded = json_decode($result, false);
        }catch (ErrorException $e){
            echo 'exe'. $e;
            return null;
        }
        $res = null;
        /*if(array_key_exists('data',$decoded)){
            $res = $decoded["data"]["url"];
        }*/
        $res = $decoded->data->url;
        return $res;
    }

}
