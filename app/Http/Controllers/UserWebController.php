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

class UserWebController extends Controller
{
    //todo change tags must be in feeds
    public $FACEBOOK_TAG = "Facebook";
    public $TWITTER_TAG = "Twitter";
    public $INSTAGRAM_TAG = "Instagram";
    public $count = 10;

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
        return view('pages.home')->with("data",$posts)->with("user",User::find($id))->with("suggestions",$this->get5Suggestions($id));
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


    /**
     * Comparator function, which compares the timestamps of two passed Posts
     */
    public static function cmp($post1, $post2)
    {
        return strcmp($post2->timestamp, $post1->timestamp);
    }



    public function followCeleb($id,$celebid){

        $user = User::find($id);
        $celeb = Celebrity::find($celebid);

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
            return redirect()->back();
        }

        foreach($category as $cat){
            $user->likes()->save(new Like(["score"=>1]));
            $like = $user->likes()->latest()->first();
            $like->category()->save($cat);
        }

        return redirect()->back();
    }

    public function get5Suggestions($userId){
        $user = User::find($userId);
        $likes = $user->likes()->orderBy("score","desc")->get();
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
                        array_push($suggestionCelebs, $celeb);
                    }
                }
            }
        }
        return array_only($suggestionCelebs,[0,1,2,3,4]);
    }

    public function getSuggestions($userId){
        $user = User::find($userId);
        $likes = $user->likes()->orderBy("score","desc")->get();
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
                        array_push($suggestionCelebs, $celeb);
                    }
                }
            }
        }
        return view('pages.suggestions')->with("suggestions",$suggestionCelebs)->with('user',$user);
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
        return view('pages.explore')->with('explorefeeds',$explorePosts)->with("user",User::find($id));
    }

    public function getCategories($id){
        return view('pages.allCategories')->with("categories",Category::all())->with("user",User::find($id));
    }

    public function getCelebrities($id){
        return view('pages.allCelebrities')->with("celebrities",Celebrity::all())->with("user",User::find($id));
    }

    public function getCelebFeeds($id,$celebId){
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
        return view('pages.timeline')->with("data",$posts)->with("user",User::find($id))->with("celebrity",$celeb);
    }

    public function getUser($id){
        $user=User::find($id);
        $celebs=User::find($id)->celebrity;
        return view('pages.profile')->with("user",$user)->with("celebrities",$celebs);
    }

    public function getAdminAddCeleb($id){
        return view('pages.adminAddCeleb')->with("user",User::find($id));
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

        return redirect()->back();
    }

    public function unFollowCeleb($id,$celebid){
        $isSuccessful = false;
        $user = User::find($id);
        $celeb = Celebrity::find($celebid);

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
        return redirect()->back();
    }

    public function getCelebsByName(Request $request, $userId)
    {
        $user = User::find($userId);
        $celebsSearched = [];
        $isFollowed = false;
        $celebsFollowed = User::find($userId)->celebrity;
        $celebs = Celebrity::where('name','=',$request->input("search"))->get();
        //todo ask kinane like instead of equals
        foreach($celebs as $celeb){
            if($celebsFollowed->contains($celeb)) $isFollowed = true;
            else $isFollowed = false;
            $cel = ["is_followed" => $isFollowed,"celeb" => $celeb];
            array_push($celebsSearched,$cel);
        }
        return view('pages.search')->with("result",$celebsSearched)->with("user",$user)->with("search",$request->input("search"));
    }

    public function getCelebsByCategory($id,$categoryId)
    {
        $user = User::find($id);
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
        return view('pages.category')->with('celebrities',$celebsOfCategory)->with("user",$user)->with("category",$category);
    }

    public function getAbout($id){
        $user=User::find($id);
        return view('pages.about')->with('user',$user);
    }

    public function getContact($id){
        $user=User::find($id);
        return view('pages.contact')->with('user',$user);
    }

    public function getFollowedCelebs($id){
        $user=User::find($id);
        $celebs=$user->celebrity;
        return view('pages.following')->with("following",$celebs)->with("user",$user);
    }

}
