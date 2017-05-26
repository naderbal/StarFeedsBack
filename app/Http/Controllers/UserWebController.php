<?php


namespace App\Http\Controllers;

use App\Category;
use App\Celebrity;
use App\InstagramFeed;
use App\Like;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\FacebookFeed;
use App\TwitterFeed;
use App\Post;
use Session;

class UserWebController extends Controller
{
    //todo change tags must be in feeds
    public $FACEBOOK_TAG = "Facebook";
    public $TWITTER_TAG = "Twitter";
    public $INSTAGRAM_TAG = "Instagram";
    public $count = 10;

    public function getWelcomePage(){

        if(Session::has('user')){
            return redirect('/home');
        }
        else {
            return view("pages.welcome");
        }
    }

    public function loginEmail(Request $request){
        $email = $request->input("email");
        $password = $request->input("password");
        $user = User::where('email','=',$email)->first();
        if($user == null || $user->password != $password){
            Session::flash("fail","Wrong Email or Password");
            return view("pages.welcome");
        }

        Session::put('user',$user);

        return redirect("/home");

    }

    public function logOut(){
        Session::flush();
        return redirect('/');
    }

    public function save(Request $request){
        $name = $request->input("name");
        $email = $request->input("r-email");
        $password = $request->input("password");
        $gender = $request->input("gender");
        $age = $request->input("age");

//        $result = filter_var( $email , FILTER_VALIDATE_EMAIL );
//
//        if(!$result){
//            Session::flash("error","This email does not exist!");
//            echo "hi";
//            return redirect()->back();
//        }

        if(User::where("email",'=',$email)->first() === null){
            $user = new User(["name"=>$name,"email"=>$email,"password"=>$password,"gender"=>$gender,"age"=>$age]);
            $user->save();
            Session::put('user',$user);
        }else{
            Session::flash('error','This email already exist');
            return redirect('/');
        }

        return redirect('/celebrities/all');
    }

    public function updateUser(Request $request){
        $id = Session::get('user')->id;
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $new_password = $request->input('new_password');
        $age = $request->input('age');
        $gender = $request->input('gender');
        $user = User::find($id);
        $is_successful = true;

        if ($user == null || $user->password != $password){
            $is_successful = false;
            Session::flash('error','Incorrect Password');
            return view('pages.profile');
        } else {
                $user->name = $name;
                $user->password = $new_password;
                $user->email = $email;
                $user->age = $age;
                $user->gender = $gender;
            if($is_successful)
            {
                $user->save();
                $is_successful = true;
            } else {
                $is_successful = false;
            }
        }
        Session::put('user',$user);
        return redirect('/edit-account');
    }

    public function saveAdmin(Request $request){
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        $gender = $request->input("gender");
        $age = $request->input("age");

        $is_admin = true;

        if(User::where("email",'=',$email)->first() === null){
            $user = new User(["name"=>$name,"email"=>$email,"password"=>$password,"gender"=>$gender,"age"=>$age,'is_admin'=>$is_admin]);
            $user->save();
            return redirect()->back();
        }
        else
            return redirect()->back()->with("error",true);
    }

    public function getUserFeeds(){

        //get celebrities followed by user of id $id
        $user=Session::get('user');
        $celebs = $user->celebrity;

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
//        if($page > 0) {
//            $pageCount = ($page - 1) * $this->count;
//            $posts = array_slice($posts, $pageCount, $this->count);
//        }
        return view('pages.home')->with("data",$posts)->with("suggestions",$this->getSuggestions());
    }

    public function getNewUserFeeds($postId){
        $newFeeds = [];
        // request user feeds with 0 to indicate new feeds
        $posts = $this->getUserFeeds();
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



    public function followCeleb($celebId){

        $userId = Session::get('user')->id;

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
        Session::put('user',$user);
        return redirect()->back();
    }

    public function getSuggestions(){
        $user = Session::get('user');
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
                            array_push($suggestionCelebs, $celeb);
                    }
                }
            }
        }
        if (count($suggestionCelebs) == 0){
            $suggestionCelebs = $this->getSuggestionsForNewUsers($user->id);
        }
        if(Route::getFacadeRoot()->current()->uri() == 'suggestions' ){
            return view('pages.suggestions')->with("suggestions",$suggestionCelebs);
        }elseif(Route::getFacadeRoot()->current()->uri() == 'home'){
            return array_splice($suggestionCelebs,0,5);
        }
    }

    public function dislikeCelebrity($celebId){
        $isSuccessful = false;

        $user = Session::get('user');
        $celeb = Celebrity::find($celebId);

        if ($user == null || $celeb == null) {
            $isSuccessful = false;

        } else {
            $user->dislikedCelebrity()->save($celeb);
            $isSuccessful = true;
        }
        Session::put('user',$user);
        return redirect('/home');

    }

    public function getExploreFeeds(){
        $explorePosts = [];
        $celebs = Celebrity::all();
        //shuffle($celebs);
        $user = Session::get('user');
        $userCelebs = $user->celebrity;
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
        return view('pages.explore')->with('explorefeeds',$explorePosts);
    }

    public function getCategories(){
        return view('pages.allCategories')->with("categories",Category::all());
    }

    public function getCelebrities(){
        $user = Session::get('user');
        $celebsSearched = [];
        $isFollowed = false;
        $celebsFollowed = $user->celebrity;
        $celebs = Celebrity::all();
        foreach($celebs as $celeb){
            if($celebsFollowed->contains($celeb)) $isFollowed = true;
            else $isFollowed = false;
            $cel = ["is_followed" => $isFollowed,"celeb" => $celeb];
            array_push($celebsSearched,$cel);
        }
        return view('pages.allCelebrities')->with("celebrities",$celebsSearched);
    }

    public function getCelebFeeds($celebName){

        $celeb = Celebrity::where('name','=',$celebName)->get();
        $user = Session::get('user');
        $celebsFollowed = $user->celebrity;
        $isFollowed=false;
        $celeb=$celeb[0];
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
        if($celebsFollowed->contains($celeb)) $isFollowed = true;
        return view('pages.timeline')->with("data",$posts)->with("celebrity",$celeb)->with('isFollowed',$isFollowed);
    }

    public function getUser(){
        $user=Session::get('user');
        $celebs=$user->celebrity;
        return view('pages.profile')->with("celebrities",$celebs);
    }

    public function getAdminAddCeleb(){
        $user=Session::get('user');
        if($user->is_admin){
            return view('pages.adminAddCeleb')->with("error",false);
        }
        else
            return redirect()->back();
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

    public function unFollowCeleb($celebid){
        $isSuccessful = false;
        $user = User::find(Session::get('user')->id);
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
        Session::put('user',$user);
        return redirect()->back();
    }

    public function getCelebsByName(Request $request)
    {
        $user = Session::get('user');
        $celebsSearched = [];
        $isFollowed = false;
        $celebsFollowed = $user->celebrity;
        $search = $request->input("search");
        $celebs = Celebrity::where('name','=',$search)->get();
        //$celebs = Celebrity::where('name','=~','.*$search.*')->get();
        //todo ask kinane like instead of equals
        foreach($celebs as $celeb){
            if($celebsFollowed->contains($celeb)) $isFollowed = true;
            else $isFollowed = false;
            $cel = ["is_followed" => $isFollowed,"celeb" => $celeb];
            array_push($celebsSearched,$cel);
        }
        return view('pages.search')->with("result", $celebsSearched)->with("search", $request->input("search"));
    }

    public function Search(Request $request){
        $userId = Session::get('user')->id;
        try {
            $name = strtolower($request->input("search"));
            $celebsSearched = [];
            $isFollowed = false;
            $celebsFollowed = User::find($userId)->celebrity;
            $celebs = Celebrity::all();
            foreach($celebs as $celeb){
                $celebName = strtolower($celeb->name);
                if(strpos($celebName, $name) > -1) {
                    if ($celebsFollowed->contains($celeb)) $isFollowed = true;
                    else $isFollowed = false;
                    $cel = ["is_followed" => $isFollowed, "celeb" => $celeb];
                    array_push($celebsSearched, $cel);
                }
            }
            return view('pages.search')->with("result",$celebsSearched)->with("search",$name);
        } catch (Exception $e){
            return redirect()->back();
        }
    }

    public function getCelebsByCategory($categoryId)
    {
        $celebsFollowed = Session::get('user')->celebrity;
        $celebsOfCategory = [];
        $category = Category::find($categoryId);
        $celebs = Celebrity::all();
        $isFollowed = false;
        foreach($celebs as $celeb){
            $categories = $celeb->category;
            foreach($categories as $cat){
                if($cat == $category){
                    if($celebsFollowed->contains($celeb)) $isFollowed = true;
                    else $isFollowed = false;
                    $cel = ["is_followed" => $isFollowed,"celeb" => $celeb];
                    array_push($celebsOfCategory,$cel);
                }
            }
        }
        return view('pages.category')->with('celebrities',$celebsOfCategory)->with("category",$category);
    }

//    public function getAbout($id){
//        $user=User::find($id);
//        return view('pages.about')->with('user',$user);
//    }

    public function getContact(){
        $user=Session::get('user');
        return view('pages.contact')->with('user',$user);
    }

    public function getFollowedCelebs(){
        $user=Session::get('user');
        $celebs=$user->celebrity;
        return view('pages.following')->with("following",$celebs);
    }

    public function getAddAdmin(){
        $user=Session::get('user');
        if($user->is_admin) {
            return view('pages.addAdmin')->with("error", false);
        }
        else
            return redirect()->back();
    }

    public function adminGetCeleb(Request $request){
        $user=Session::get('user');
        Session::forget('celebrity');
        $celebSearched = null;
        try {
            $name = strtolower($request->input("search"));
            $celebs = Celebrity::all();
            foreach($celebs as $celeb){
                $celebName = strtolower($celeb->name);
                if(strpos($celebName, $name) > -1) {
                    $celebSearched = $celeb;
                }
            }
            if($user->is_admin && $celebSearched != null){
                Session::put('celebrity',$celebSearched);
                return redirect('/admin/edit-celebrity');
            }else{
                Session::flash('error','Celebrity not found!');
                if($user->is_admin) {
                    return view('pages.adminEditCeleb');
                }
                else
                    return redirect()->back();
            }
        } catch (Exception $e){
            return redirect()->back();
        }
    }

    public function getAdminEditCeleb(){
        $user=Session::get('user');
        if($user->is_admin) {
            return view('pages.adminEditCeleb');
        }
        else
            return redirect()->back();
    }

}
