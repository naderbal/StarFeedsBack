<?php

namespace App\Http\Controllers;

use App\Category;
use App\Celebrity;
use App\InstagramFeed;
use App\Like;
use App\User;
use App\Http\Requests;
use Exception;
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
    public $count = 10;

    public function loginEmail(Request $request){
        $email = $request->input("email");
        $password = $request->input("password");
        $user = User::where('email','=',$email)->first();
        if($user == null){
            return "false";
        }
        $followersCount = count($user->celebrity()->get());
        if($user->password != $password){
            return "false";
        }
        return [
            'data' =>
                [
                    "user" =>
                    [
                        "id"=>$user->id,
                        "name"=>$user->name,
                        "email"=>$user->email,
                        "followers_count"=>$followersCount
                    ]
                ]
        ];
    }

    public function loginFacebook(Request $request){
        $id = $request->input("id");
        $name = $request->input("name");
        $email = $request->input("email");
        $user = User::where('fb_id','=',$id)->first();
        $followersCount = 0;
        if($user == null){
            $newUser = $this->saveFacebookUser($id, $name, $email);
            if($newUser != null){
                $followersCount = count($user->celebrity()->get());
                return [
                    'data' =>
                        [
                            "user" =>
                                [
                                    "id"=>$newUser->id,
                                    "name"=>$newUser->name,
                                    "email"=>$newUser->email,
                                    "followers_count"=>$followersCount
                                ]
                        ]
                ];
            }
        }
        return [
            'data' =>
                [
                    "user" =>
                        [
                            "id"=>$user->id,
                            "name"=>$user->name,
                            "email"=>$user->email,
                            "followers_count"=>$followersCount
                        ]
                ]
        ];
    }

    public function loginGoogle(Request $request){
        $id = $request->input("id");
        $name = $request->input("name");
        $email = $request->input("email");
        $user = User::where('google_id','=',$id)->first();
        $followersCount = 0;
        if($user == null){
            $newUser = $this->saveGoogleUser($id, $name, $email);
            if($newUser != null){
                $followersCount = count($user->celebrity()->get());
                return [
                    'data' =>
                        [
                            "user" =>
                                [
                                    "id"=>$newUser->id,
                                    "name"=>$newUser->name,
                                    "email"=>$newUser->email,
                                    "followers_count"=>$followersCount
                                ]
                        ]
                ];
            }
        }
        return [
            'data' =>
                [
                    "user" =>
                        [
                            "id"=>$user->id,
                            "name"=>$user->name,
                            "email"=>$user->email,
                            "followers_count"=>$followersCount
                        ]
                ]
        ];
    }

    public function save(Request $request){
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        $gender = $request->input("gender");
        $age = $request->input("age");
        $isAdmin = false;

        $isSuccessful = false;

        if($email!= null && User::where("email",'=',$email)->first() === null){
            $user = new User(["name"=>$name,"email"=>$email,"password"=>$password,"gender"=>$gender,"age"=>$age,'is_admin'=>$isAdmin]);
            $user->save();
            $isSuccessful = true;
           return [
               'data' =>
                [
                    'is_successful' => $isSuccessful,
                    'user' => $user,
                ]
            ];
        }
        return [
            'data' =>
                [
                    'is_successful' => $isSuccessful,
                    'user' => null,
                ]
        ];

    }

    public function saveAdmin(Request $request){
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        $gender = $request->input("gender");
        $age = $request->input("age");
        $isAdmin = true;
        if(User::where("email",'=',$email)->first() === null){
            $user = new User(["name"=>$name,"email"=>$email,"password"=>$password,"gender"=>$gender,"age"=>$age,'is_admin'=>$isAdmin]);
            $user->save();
        }
    }

    public function saveFacebookUser($fbId, $name, $email){
        $user = null;
        if(User::where("fb_id",'=',$fbId)->first() === null){
            $user = new User(["name"=>$name,"email"=>$email,"fb_id"=>$fbId]);
            $user->save();
        }
        return $user;
    }

    public function saveGoogleUser($googleId, $name, $email){
        $user = null;
        if(User::where("google_id",'=',$googleId)->first() === null){
            $user = new User(["name"=>$name,"email"=>$email,"google_id"=>$googleId]);
            $user->save();
        }
        return $user;
    }

    public function getUserFollowing($id){
        $celebs = User::find($id)->celebrity;
        $returnCelebs = [];
        foreach($celebs as $celeb) {
            $cel = ["is_followed"=>true,"celeb" => $celeb];
            array_push($returnCelebs, $cel);
        }
        return ["data" => $returnCelebs];
    }


    public function getUserFeeds($id, $page){

        //get celebrities followed by user of id $id
        $celebs = User::find($id)->celebrity;

        $posts = array();
        foreach($celebs as $celeb){
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
        }
        //sort the posts by timestamp
        usort($posts, array($this, 'cmp'));
        // if page equals 0, its requested by new feeds
        if($page > 0) {
            $pageCount = ($page - 1) * $this->count;
            $posts = array_slice($posts, $pageCount, $this->count);
        }
        return ['data' => $posts];
    }

    public function getNewUserFeeds($id, $postId){
        $newFeeds = [];
        // request user feeds with 0 to indicate new feeds
        $posts = $this->getUserFeeds($id, 0);
        $posts = $posts['data'];
        foreach($posts as $post){
            if($post->id == $postId)break;
            array_push($newFeeds, $post);
        }
        return ["data" => $newFeeds];
    }

    public function updateUser(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $user = User::find($id);
        $is_successful = true;

        if ($user == null){
            $is_successful = false;
        } else {
            if($name != null){
                $user->name = $name;
            }
            if($email != null){
                if(User::where("email",'=',$email)->first() === null) {
                    $user->email = $email;
                }
                else{
                    $is_successful = false;
                }
            }
            if($is_successful)
            {
                $user->save();
                $is_successful = true;
            } else {
                $is_successful = false;
            }
        }
        return [
            'data' =>
                    [
                        'is_successful' => $is_successful,
                        'user' => $user
                    ]
        ];
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
            $isSuccessful = false;
            $user = User::find($userId);
            $celeb = Celebrity::find($celebId);

            if($user == null || $celeb == null){
                $isSuccessful = false;
            }else if( count($user->celebrity()->where('name',$celeb->name)->get()) > 0){
                $isSuccessful = false;
            } else {
                $user->celebrity()->save($celeb);
                $celeb->followers++;
                $celeb->save();
                $isSuccessful = true;
                $category = $celeb->category;

                $likes = $user->likes()->get();
                $isFound = false;
                //loop over all likes of user
                foreach ($likes as $li) {
                    //check if like isnt bound to a category(error)
                    if (!isset($li->category)) {
                        continue;
                    }
                    //loop over categories of celebrity
                    foreach ($category as $cat) {
                        //check if current category equals category of like
                        if ($cat->category == $li->category->category) {
                            //increment score of like and set isFound boolean to true
                            $li->score++;
                            $li->save();
                            $isFound = true;
                        }
                    }
                }
                //if isfound return, else create a new like relation
                if (!$isFound) {
                    foreach ($category as $cat) {
                        $user->likes()->save(new Like(["score" => 1]));
                        $like = $user->likes()->latest()->first();
                        $like->category()->save($cat);
                    }
                }
            }
            return ["is_successful"=>$isSuccessful];
        }

    public function unFollowCeleb(Request $request){
        $userId = $request->input("user_id");
        $celebId = $request->input("celeb_id");
        $isSuccessful = false;
        $user = User::find($userId);
        $celeb = Celebrity::find($celebId);

        if($user == null || $celeb == null){
            $isSuccessful = false;
        } else {
            $user->celebrity()->detach($celeb);
            $celeb->followers--;
            $celeb->save();
            $isSuccessful = true;
            $category = $celeb->category;

            $likes = $user->likes()->get();
            //loop over all likes of user
            foreach ($likes as $li) {
                //check if like isnt bound to a category(error)
                if (!isset($li->category)) {
                    continue;
                }
                //loop over categories of celebrity
                foreach ($category as $cat) {
                    //check if current category equals category of like
                    if ($cat->category == $li->category->category) {
                        //increment score of like and set isFound boolean to true
                        $li->score--;
                        $li->save();
                    }
                }
            }

        }
        return ["is_successful"=>$isSuccessful];
    }

    public function dislikeCelebrity(Request $request){
        $userId = $request->input("userId");
        $celebId = $request->input("celebId");
        $isSuccessful = false;

        $user = User::find($userId);
        $celeb = Celebrity::find($celebId);

        if ($user == null || $celeb == null) {
            $isSuccessful = false;

        } else {
            $user->dislikedCelebrity()->save($celeb);
            $isSuccessful = true;
        }

        return [
            'is_successful'=> $isSuccessful
        ];

        }

    public function getSuggestions($userId){
        $user = User::find($userId);
        $likes = $user->likes()->orderBy("score","desc")->get();
        $dislikedCelebs = $user->dislikedCelebrity()->get();
        $suggestionCelebs = [];
        foreach($likes as $like){
            $category = $like->category;
            //todo ask kinane is there a way to get children of node (all the celebs where they have this category)
            $celebs = Celebrity::get();
            foreach($celebs as $celeb){
                // check if celeb belongs to category
                if($celeb->category->contains($category)){
                    // check if user already follows celeb
                    if(!$user->celebrity->contains($celeb)) {
                        if(!$dislikedCelebs->contains($celeb))
                        array_push($suggestionCelebs, ["celeb"=>$celeb]);
                    }
                }
            }
        }
        if (count($suggestionCelebs) == 0){
            $suggestionCelebs = $this->getSuggestionsForNewUsers($userId);
        }
        return $suggestionCelebs;
    }


    private function getSuggestionsForNewUsers($userId){
        $user = User::find($userId);
        $dislikedCelebs = $user->dislikedCelebrity()->get();
        $suggestionCelebs = [];
        $allCelebs = Celebrity::all();
        //$allCelebs = $allCelebs->toArray();
        $celebsArray = [];
        foreach($allCelebs as $celeb){
            array_push($celebsArray,$celeb);
        }
        usort($celebsArray, array($this,'cmpCelebs'));
        //$allCelebs = array_reverse($allCelebs);
        foreach($allCelebs as $celeb) {
            if (!$dislikedCelebs->contains($celeb) && !$user->celebrity->contains($celeb))
                array_push($suggestionCelebs, ["celeb" => $celeb]);
        }
        return $suggestionCelebs;
    }


    public static function cmpCelebs($celeb1, $celeb2)
    {
        if ($celeb1['followers'] == $celeb2['followers']) {
            return 0;
        }
        return ($celeb1['followers'] < $celeb2['followers']) ? -1 : 1;
    }

    public function getExploreFeeds($id){
        $explorePosts = [];
        $celebs = Celebrity::all();
        //shuffle($celebs);
        $userCelebs = User::find($id)->celebrity;
        foreach($celebs as $celeb){
            if($userCelebs->contains($celeb)){
                continue;
            }
            $facebookFeeds = $celeb->FacebookFeed;
            $twitterFeeds = $celeb->TwitterFeed;
            $instagramFeeds = $celeb->InstagramFeed;
            $i = 0;
            while($i < 2){
                if ($facebookFeeds->count() > $i) {
                    $platform = $this->FACEBOOK_TAG;
                    $post = new Post($facebookFeeds->get($i), $platform, $celeb);
                    array_push($explorePosts, $post);
                }
                if ($twitterFeeds->count() > $i) {
                    $platform = $this->TWITTER_TAG;
                    $post = new Post($twitterFeeds->get($i), $platform, $celeb);
                    array_push($explorePosts, $post);
                }
                if ($instagramFeeds->count() > $i) {
                    $platform = $this->INSTAGRAM_TAG;
                    $post = new Post($instagramFeeds->get($i), $platform, $celeb);
                    array_push($explorePosts, $post);
                }
                $i++;
            }
        }
        return $explorePosts;
    }
}
