<?php

namespace App\Http\Controllers;

use App\Category;
use App\FacebookFeed;
use App\Following;
use App\TwitterFeed;
use App\User;
use ErrorException;
use Illuminate\Http\Request;
use App\Celebrity;
use App\Post;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class apiController extends Controller
{
    public $FACEBOOK_TAG = "Facebook";
    public $TWITTER_TAG = "Twitter";



    public function getUserFeeds($id){
        //get celebrities followed by user of id $id
       $celebs = User::find($id)->celebrity;

        $posts = array();
        foreach($celebs as $celeb){
            //get the all FacebookFeeds of the celebrity
            $celebFbFeeds = FacebookFeed::where('celeb_id','=',$celeb->id)->get();
            foreach($celebFbFeeds as $feed){
                $celebName = $celeb->name;
                $platform = $this->FACEBOOK_TAG;
                $post = new Post($feed, $platform, $celebName);
                array_push($posts,$post);
            }

            //get the all FacebookFeeds of the celebrity
            $celebTwitterFeeds = TwitterFeed::where('celeb_id','=',$celeb->id)->get();
            foreach($celebTwitterFeeds as $feed){
                $celebName = $celeb->name;
                $platform = $this -> TWITTER_TAG;
                $post = new Post($feed, $platform, $celebName);
                array_push($posts,$post);
            }
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


    public function getCelebs()
    {
        return Celebrity::all();
    }

    /**
     * Requests and returns the facebook posts of a celebrity through facebook's API
     * $id is the facebook user_id of the celeb.
     */
    public function makeFbCall($id)
    {
        $appID = '311242662591971';
        $appSecret = 'de2e7436f9543e1d3c816e5485cea79b';
        //Create an access token using the APP ID and APP Secret.
        $accessToken = $appID . '|' . $appSecret;
        //Tie it all together to construct the URL
        $url = "https://graph.facebook.com/$id/posts?access_token=$accessToken&fields=picture,name,message,status_type,created_time,full_picture,link,story&limit=3";
        try {
            $result = file_get_contents($url);
            $decoded = json_decode($result, true);
            return $decoded;
        }catch (ErrorException $e){
            echo 'exe'. $e;
            return null;
        }
    }

    public function getFacebookProfilePicture($id){
        $url = "https://graph.facebook.com/v2.8/$id/picture?debug=all&format=json&method=get&pretty=0&redirect=false&suppress_http_code=1";

        try {
            $result = file_get_contents($url);
            $decoded = json_decode($result, true);
        }catch (ErrorException $e){
            echo 'exe'. $e;
            return null;
        }
        return $decoded["data"]["url"];
    }

    /**
     * Requests and returns the tweets of a certain celebrity through twitter's API
     * $twtId is the screen_name of this celeb.
     */
    public function makeTwitterCall($twtId)
    {
        //This is all you need to configure.
        $app_key = 'gTglSLTCCdqbo6QPxuWUNFSUC';
        $app_token = 'UyAPVkzDeknBueoxzIkidVXMc0Qs9XjRLGhZ8mmLcc5HR3ighU';
        //These are our constants.
        $api_base = 'https://api.twitter.com/';
        $bearer_token_creds = base64_encode($app_key . ':' . $app_token);
        //Get a bearer token.
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Authorization: Basic ' . $bearer_token_creds . "\r\n" .
                    'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                'content' => 'grant_type=client_credentials'
            )
        );
        $context = stream_context_create($opts);
        $json = file_get_contents($api_base . 'oauth2/token', false, $context);
        $result = json_decode($json, true);
        if (!is_array($result) || !isset($result['token_type']) || !isset($result['access_token'])) {
            die("Something went wrong. This isn't a valid array: " . $json);
        }
        if ($result['token_type'] !== "bearer") {
            die("Invalid token type. Twitter says we need to make sure this is a bearer.");
        }
        //Set our bearer token. Now issued, this won't ever* change unless it's invalidated by a call to /oauth2/invalidate_token.
        //*probably - it's not documentated that it'll ever change.
        $bearer_token = $result['access_token'];
        //Try a twitter API request now.
        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $bearer_token
            )
        );
        $context = stream_context_create($opts);
        //todo
        $json = file_get_contents($api_base . '1.1/statuses/user_timeline.json?count=4&include_rts=false&exclude_replies=true&screen_name=' . $twtId, false, $context);
        $tweets = json_decode($json, true);
        //echo $json;
        //print_r($tweets[0]['entities']['media'][0]['media_url']);
        return $tweets;
    }


    public function saveFeedsToDatabase()
    {
        $celebs = Celebrity::all();
        $FACEBOOK_PREFIX = "facebook_";
        $TWITTER_PREFIX = "twitter_";

        foreach ($celebs as $celeb) {
            $fbId = $celeb->fb_id;
            $celebId = $celeb->id;
            $celebrity = Celebrity::find($celebId);
            if ($fbId !== '') {
                try {
                    $fbResult = $this->makeFbCall($fbId);
                    if($fbResult==null){
                        echo "null";
                        break;
                    }
                    foreach ($fbResult['data'] as $data) {
                        if (count(FacebookFeed::where('feed_id','=',$data['id'])->get()) > 0) {
                            // facebook feed previously saved
                            echo"fb found";
                            break;
                        }
                        $feedId = $data['id'];
                        $feedType = $FACEBOOK_PREFIX.$data['status_type'];
                        $created_time = $data['created_time'];
                        $feedText = null;
                        if(array_key_exists('message', $data)){
                            $feedText = $data['message'];
                        }
                        $feedImageUrl = null;
                        if(array_key_exists('full_picture', $data)){
                            $feedImageUrl = $data['full_picture'];
                        }
                        $feedLink = null;
                        if(array_key_exists('link',$data)){
                            $feedLink = $data['link'];
                        }
                        $facebookFeed = new FacebookFeed(['feed_id' => $feedId,
                            "celeb_id"=>$celebId,
                            "feed_type"=> $feedType,
                            "text" => $feedText,
                            "created_time"=>$created_time,
                            "image_url" => $feedImageUrl,
                            "link" => $feedLink]);

                        $celebrity->facebookFeed()->save($facebookFeed);
                    }
                }catch (ErrorException $e){
                    echo "ex: ".$e;
                }
            }
            $twtId = $celeb->twt_id;
            if ($twtId != '') {
                $twtResult = $this->makeTwitterCall($twtId);
                foreach ($twtResult as $result) {
                    $feedType = "twitter_text";
                    if (count(TwitterFeed::where('feed_id','=',$result['id_str'])->get()) > 0) {
                        break;
                    }
                    $feedId = $result['id_str'];
                    $created_time = $result['created_at'];
                    $feedText = null;
                    if(array_key_exists('text',$result)){
                        $feedText = $result['text'];
                    }
                    $feedImageUrl = null;
                    if (array_key_exists('media', $result['entities'])) {
                        $feedImageUrl = $result['entities']['media']['0']['media_url'];
                        $feedType = "photo";
                    }
                    $feedType = $TWITTER_PREFIX.$feedType;
                    $twitterFeed = new TwitterFeed(['feed_id' => $feedId,
                        "celeb_id"=>$celebId,
                        "text" => $feedText,
                        "feed_type" => $feedType,
                        "created_time"=>$created_time,
                        "image_url" => $feedImageUrl,
                        "video_url" => "url"]);

                    $celebrity->twitterFeed()->save($twitterFeed);
                }
            }
        }
    }

    public function testTwitter(){
        $twtResult = $this->makeTwitterCall('cristiano');//selenagomez
        //$fbResult = $this->makeFbCall("cristiano");
        //print_r($fbResult);
        print_r($twtResult[2]);
       // print_r($twtResult['0']['entities']['media']['0']['media_url']);
       /* $twtJson = json_decode($twtResult['data']);
        echo $twtJson;*/
    }
    public function testFacebook(){
        $twtResult = $this->makeFbCall('cristiano');//selenagomez
        //$fbResult = $this->makeFbCall("cristiano");
        //print_r($fbResult);
        print_r($twtResult);
       // print_r($twtResult['0']['entities']['media']['0']['media_url']);
       /* $twtJson = json_decode($twtResult['data']);
        echo $twtJson;*/
    }

    public function testPost(Request $request){
        echo "name ".$request->input("name");
    }


    public function addCeleb(Request $request){
        $name = $request->input("name");
        $fb_id = $request->input("fb_id");
        $twt_id = $request->input("twt_id");
        $category = $request->input("category");
        /*if (count(Celebrity::where(['fb_id','=',$fb_id])->get()) > 0){
            return;
        }*/
        $fbProfilePic = $this->getFacebookProfilePicture($fb_id);
        $celeb = new Celebrity(["name" => $name, "fb_id" => $fb_id,"fb_profile_url"=>$fbProfilePic,"twt_id" => $twt_id]);

        $categoryVar = Category::where("category",'=',$category)->get()->first();
        if(!$categoryVar) {
            $categoryVar = new Category(["category" => $category]);
            $categoryVar->save();
        }
        $celeb->save();
        $celeb->category()->save($categoryVar);
    }

    public function a(){
        $celeb = Celebrity::find(4);
        //$celeb = new Celebrity(["name" => "nad","fb_id" => "nnn","twt_id" => "nnt"]);
        $user =new User(['name' => 'Mo', 'email' => 'nader@email.com', 'password' => 'password', 'gender' => 'male', 'age' => '21']);
        $celeb ->user()->save($user);
    }

}
