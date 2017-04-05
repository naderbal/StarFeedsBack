<?php

namespace App\Http\Controllers;

use App\Category;
use App\Celebrity;
use App\InstagramFeed;
use App\Like;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\FacebookFeed;
use App\TwitterFeed;
use App\Post;

class UserController extends Controller
{
    //todo change tags must be in feeds
    public $FACEBOOK_TAG = "Facebook";
    public $TWITTER_TAG = "Twitter";
    public $INSTAGRAM_TAG = "Instagram";

    public function save(Request $request){
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        $gender = $request->input("gender");
        $age = $request->input("age");

        if(User::where("email",'=',$email)->first() === null){
            $user = new User(["name"=>$name,"email"=>$email,"password"=>$password,"gender"=>$gender,"age"=>$age]);
            $user->save();
        }
    }


    public function getUserFeeds($id){
        //get celebrities followed by user of id $id
        $celebs = User::find($id)->celebrity;

        $posts = array();
        foreach($celebs as $celeb){
            //get all FacebookFeeds of the celebrity
            $celebFbFeeds = FacebookFeed::where('celeb_id','=',$celeb->id)->get();
            foreach($celebFbFeeds as $feed){
                $celebName = $celeb->name;
                $platform = $this->FACEBOOK_TAG;
                $post = new Post($feed, $platform, $celebName);
                array_push($posts,$post);
            }

            //get all TwitterFeeds of the celebrity
            $celebTwitterFeeds = TwitterFeed::where('celeb_id','=',$celeb->id)->get();
            foreach($celebTwitterFeeds as $feed){
                $celebName = $celeb->name;
                $platform = $this -> TWITTER_TAG;
                $post = new Post($feed, $platform, $celebName);
                array_push($posts,$post);
            }

            //get all InstagramFeeds of the celebrity
            $celebInstagramFeeds = InstagramFeed::where('celeb_id','=',$celeb->id)->get();
            foreach($celebInstagramFeeds as $feed){
                $celebName = $celeb->name;
                $platform = $this -> INSTAGRAM_TAG;
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



    public function followCeleb(Request $request){
        $userId = $request->input("user_id");
        $celebId = $request->input("celeb_id");

        $user = User::find($userId);
        $celeb = Celebrity::find($celebId);

        if($user == null || $celeb == null){
            return;
        }

        if( count($user->celebrity()->where('name',$celeb->name)->get()) > 0){
            return;
        }

        $user->celebrity()->save($celeb);
        $celeb->followers++;
        $celeb->save();

        $category = $celeb->category;

        $likes = $user ->likes()->get();
        $isFound = false;
        //loop over all likes of user
        foreach($likes as $li){
            //check if like isnt bound to a category(error)
            if(!isset($li->category)){
                continue;
            }
            //loop over categories of celebrity
            foreach($category as $cat){
                //check if current category equals category of like
                if($cat->category == $li->category->category) {
                    //increment score of like and set isFound boolean to true
                    $li->score++;
                    $li->save();
                    $isFound = true;
                }
            }
        }
        //if isfound return, else create a new like relation
            if($isFound){
            return;
            }

        foreach($category as $cat){
            $user->likes()->save(new Like(["score"=>1]));
            $like = $user->likes()->latest()->first();
            $like->category()->save($cat);
        }

    }

    public function getSuggestionsOfUser(Request $request){
        $userId = $request->input("user_id");

        $user = User::find($userId);
        $likes = $user->likes()->orderBy("score","desc")->get();
        $suggestionCelebs = [];
        foreach($likes as $like){
            $category = $like->category;
            //todo ask kinane is there a way to get children of node (all the celebs where they have this category)
            $celebs = Celebrity::get();
            foreach($celebs as $celeb){
                if($celeb->category->contains($category)){
                    if(!$user->celebrity->contains($celeb)) {
                        array_push($suggestionCelebs, $celeb);
                    }
                }
            }
        }
        return $suggestionCelebs;
    }
}
