<?php

namespace App\Http\Controllers;

use App\Category;
use App\FacebookFeed;
use App\Following;
use App\InstagramFeed;
use App\TwitterFeed;
use App\User;
use ErrorException;
use Illuminate\Http\Request;
use App\Celebrity;
use App\Post;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use InstagramScraper\Instagram;

class apiController extends Controller
{
    public $FACEBOOK_TAG = "Facebook";
    public $TWITTER_TAG = "Twitter";
    public $Instagram_TAG = "Instagram";


    public function getCategories(){
        return Category::all();
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
        $url = "https://graph.facebook.com/$id/posts?access_token=$accessToken&fields=id,picture,name,message,status_type,created_time,full_picture,link,story&limit=3";
        try {
            $result = file_get_contents($url);
            $decoded = json_decode($result, true);
            return $decoded;
        }catch (ErrorException $e){
            echo 'exe'. $e;
            return null;
        }
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

    public function makeInstagramCall($handle){
        $media = Instagram::getMedias($handle, 10);

        return $media;
    }


    public function saveFeedsToDatabase()
    {
        $celebs = Celebrity::all();
        $FACEBOOK_PREFIX = "facebook_";
        $TWITTER_PREFIX = "twitter_";
        $Instagram_PREFIX = "instagram_";

        foreach ($celebs as $celeb) {
            $fbId = $celeb->fb_id;
            $celebId = $celeb->id;
            $celebrity = Celebrity::find($celebId);
            if ($fbId !== '' && $fbId != null) {
                try {
                    $fbResult = $this->makeFbCall($fbId);
                    if($fbResult==null){
                        echo "null";
                        break;
                    }
                    foreach ($fbResult['data'] as $data) {
                        if (count(FacebookFeed::where('feed_id','=',$data['id'])->get()) > 0) {
                            // facebook feed previously saved
                            break;
                        }
                        $feedId = $data['id'];
                        $feedType = $FACEBOOK_PREFIX.$data['status_type'];
                        $created_time = $data['created_time'];
                        $feedText = "";
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
                        if($fbId == 'StarFeedsDev'){
                            echo "succ";
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
            }else{
                echo "null celeb";
            }
            $twtId = $celeb->twt_id;
            if ($twtId != '' && $twtId != null) {
                $twtResult = $this->makeTwitterCall($twtId);
                foreach ($twtResult as $result) {
                    $feedType = "twitter_text";
                    if (count(TwitterFeed::where('feed_id','=',$result['id_str'])->get()) > 0) {
                        break;
                    }
                    $feedId = $result['id_str'];
                    $created_time = $result['created_at'];
                    $feedText = "";
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
            $instagramHandle = $celeb->instagram_id;
            if($instagramHandle != '' && $instagramHandle != null){
                $instagramResult = $this->makeInstagramCall($instagramHandle);
                foreach($instagramResult as $result){
                    if (count(InstagramFeed::where('feed_id','=',$result->id)->get()) > 0) {
                        break;
                    }
                    $feedId = $result->id;
                    $created_time = $result->createdTime;
                    $feedType = $result->type;
                    $feedText = $result->caption;
                    $feedImageUrl = $result->imageHighResolutionUrl;
                    $feedVideoUrl = $result->videoStandardResolutionUrl;
                    $feedType = $Instagram_PREFIX.$feedType;

                    $instagramFeed = new InstagramFeed(['feed_id' => $feedId,
                        "celeb_id"=>$celebId,
                        "text" => $feedText,
                        "feed_type" => $feedType,
                        "created_time"=>$created_time,
                        "image_url" => $feedImageUrl,
                        "video_url" => $feedVideoUrl]);

                    $celebrity->InstagramFeed()->save($instagramFeed);
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
        $twtResult = $this->makeFbCall('StarFeedsDev');//selenagomez
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


    public function a(){
        $celeb = Celebrity::find(4);
        //$celeb = new Celebrity(["name" => "nad","fb_id" => "nnn","twt_id" => "nnt"]);
        $user =new User(['name' => 'Mo', 'email' => 'nader@email.com', 'password' => 'password', 'gender' => 'male', 'age' => '21']);
        $celeb ->user()->save($user);
    }

    public function testInstagram(){
        $medias = Instagram::getMedias('nusr_et', 10);
        return $medias;
    }

}
