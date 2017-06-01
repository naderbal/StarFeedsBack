<?php


namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers;

use App\Category;
use App\Celebrity;
use App\InstagramFeed;
use App\Like;
use App\Message;
use App\User;
use App\Http\Requests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\FacebookFeed;
use App\TwitterFeed;
use App\Post;
use Session;
use Socialite;

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

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        $gUser = Socialite::driver('google')->user();

        $id = $gUser->getId();
        $name = $gUser->getName();
        $email = $gUser->getEmail();
        $user = User::where('google_id','=',$id)->first();
        $followersCount = 0;
        if($user == null) {
            $newUser = $this->saveGoogleUser($id, $name, $email);
            if ($newUser != null) {
                $followersCount = count($user->celebrity()->get());
            }
            Session::put('user',$newUser);
        }
        else{
            Session::put('user',$user);
            return redirect('/home');
        }
        return redirect("/suggestions");
    }

    public function logOut(){
        Session::flush();
        return redirect('/');
    }

    public static function cmpCelebs($celeb1, $celeb2)
    {
        if ($celeb1['followers'] == $celeb2['followers']) {
            return 0;
        }
        return ($celeb1['followers'] < $celeb2['followers']) ? -1 : 1;
    }

    public function save(Request $request){
        $name = $request->input("name");
        $email = $request->input("r-email");
        $password = $request->input("password");
        $gender = $request->input("gender");
        $country = $request->input("country");
        if(strlen($country) == 2) {
            $country = $this->getCountryByCode($country);
        }
        $age = $request->input("age");
        $isAdmin = false;


        if(User::where("email",'=',$email)->first() === null){
            $user = new User([
                "name"=>$name,
                "email"=>$email,
                "password"=>$password,
                "gender"=>$gender,
                "age"=>$age,
                "country"=>$country,
                'is_admin'=>$isAdmin
            ]);
            $user->save();
            Session::put('user',$user);
        }else{
            Session::flash('error','This email already exist');
            return redirect('/');
        }

        return redirect('/suggestions');
    }

    public function updateUserPassword(Request $request){
        $id = Session::get('user')->id;
        $password = $request->input('password');
        $new_password = $request->input('new_password');
        $user = User::find($id);
        $is_successful = true;

        if ($user == null || $user->password != $password){
            $is_successful = false;
            Session::flash('error','Incorrect Password!');
            return view('pages.profile');
        } else {
            $user->password = $new_password;
            if($is_successful)
            {
                $user->save();
                Session::flash('success','Your password has been updated!');
                $is_successful = true;
            } else {
                $is_successful = false;
            }
        }
        Session::put('user',$user);
        return redirect('/edit-account');
    }

    public function updateUser(Request $request){
        $id = Session::get('user')->id;
        $name = $request->input('name');
        $email = $request->input('email');
        $age = $request->input('age');
        $gender = $request->input('gender');
        $country = $request->input('country');
        $user = User::find($id);
        $is_successful = true;
        $country = $this->getCountryByCode($country);

        if ($user == null){
            $is_successful = false;
            Session::flash('error','Something went wrong, Try Again!');
            return view('pages.profile');
        } else {
                $user->name = $name;
                $user->email = $email;
                $user->age = $age;
                $user->gender = $gender;
                $user->country = $country;
            if($is_successful)
            {
                $user->save();
                Session::flash('success','Your information has been updated!');
                $is_successful = true;
            } else {
                $is_successful = false;
            }
        }
        Session::put('user',$user);
        return view('pages.profile');
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
        if($user) {
            $celebs = $user->celebrity;

            $posts = array();
            foreach ($celebs as $celeb) {
                //get all FacebookFeeds of the celebrity
                $celebFbFeeds = FacebookFeed::where('celeb_id', '=', $celeb->id)->get();
                foreach ($celebFbFeeds as $feed) {
                    $platform = $this->FACEBOOK_TAG;
                    $post = new Post($feed, $platform, $celeb);
                    array_push($posts, $post);
                }

                //get all TwitterFeeds of the celebrity
                $celebTwitterFeeds = TwitterFeed::where('celeb_id', '=', $celeb->id)->get();
                foreach ($celebTwitterFeeds as $feed) {
                    $celebName = $celeb->name;
                    $platform = $this->TWITTER_TAG;
                    $post = new Post($feed, $platform, $celeb);
                    array_push($posts, $post);
                }

                //get all InstagramFeeds of the celebrity
                $celebInstagramFeeds = InstagramFeed::where('celeb_id', '=', $celeb->id)->get();
                foreach ($celebInstagramFeeds as $feed) {
                    $celebName = $celeb->name;
                    $platform = $this->INSTAGRAM_TAG;
                    $post = new Post($feed, $platform, $celeb);
                    array_push($posts, $post);
                }
            }
            //sort the posts by timestamp
            usort($posts, array($this, 'cmp'));
            // if page equals 0, its requested by new feeds
//        if($page > 0) {
//            $pageCount = ($page - 1) * $this->count;
//            $posts = array_slice($posts, $pageCount, $this->count);
//        }
            return view('pages.home')->with("data", $posts)->with("suggestions", $this->getSuggestions());
        }
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
        $userId = $user->id;
        $country = $user->country;
        $likes = $user->likes()->orderBy("score","desc")->get();
        $dislikedCelebs = $user->dislikedCelebrity()->get();
        $suggestionCelebs = [];
        foreach($likes as $like){
            $category = $like->category;
            $celebs = Celebrity::get();
            foreach($celebs as $celeb){
                // check if celeb belongs to category
                if($celeb->category->contains($category)){
                    // check if user already follows celeb
                    if(!$user->celebrity->contains($celeb)) {
                        $celebScore=['score'=>0,'celeb'=>$celeb];
                        if(!$dislikedCelebs->contains($celeb))
                            if($celeb->country == $country) {
                                $celebScore = ["score"=>20,"celeb"=>$celeb];
                            } else {
                                $celebScore = ["score" => 10, "celeb" => $celeb];
                            }
                        array_push($suggestionCelebs, $celebScore);
                    }
                }
            }
        }
        foreach($suggestionCelebs as $celeb){
            if($celeb['celeb']->country == $country){
                $celeb['score']+=20;
            }
        }

        usort($suggestionCelebs, array($this,'cmpCelebsScore'));

        $returnCelebs = [];
        foreach($suggestionCelebs as $celeb){
            array_push($returnCelebs, $celeb['celeb']);
        }

        if (count($suggestionCelebs) == 0){
            $returnCelebs = $this->getSuggestionsForNewUsers($userId);
        }

        if(Route::getFacadeRoot()->current()->uri() == 'suggestions' ){
            return view('pages.suggestions')->with("suggestions",$returnCelebs);
        }else if(Route::getFacadeRoot()->current()->uri() == 'home'){
            return array_splice($returnCelebs,0,5);
        }
    }

    public static function cmpCelebsScore($celeb1, $celeb2)
    {
        if ($celeb1['score'] == $celeb2['score']) {
            return 0;
        }
        return ($celeb1['score'] > $celeb2['score']) ? -1 : 1;
    }

    private function getSuggestionsForNewUsers($userId){
        $user = User::find($userId);
        $country = $user->country;
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
                if($celeb->country == $country) {
                    $celebScore = ["score"=>20,"celeb"=>$celeb];

                } else {
                    $celebScore = ["score" => 10, "celeb" => $celeb];
                }
            array_push($suggestionCelebs, $celebScore);
        }

        usort($suggestionCelebs, array($this,'cmpCelebsScore'));
        $returnCelebs = [];
        foreach($suggestionCelebs as $celeb){
            array_push($returnCelebs, $celeb['celeb']);
        }

        return $returnCelebs;
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
        return redirect()->back();

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
            return view('pages.adminAddCeleb');
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
        $country = $request->input("country");
        if(strlen($country) == 2) {
            $country = $this->getCountryByCode($country);
        }
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
                "instagram_id" => $instagram_id,
                "country" => $country,
            ]
        );

        $categoryVar = Category::where("category",'=',$category)->get()->first();
        if(!$categoryVar) {
            $categoryVar = new Category(["category" => $category]);
            $categoryVar->save();
        }
        $celeb->save();
        $celeb->category()->save($categoryVar);

        Session::flash('success',"Celebrity has been added!");
        return view('pages.adminAddCeleb');
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

    public function adminGetMessages(){
        $user=Session::get('user');
        if($user->is_admin) {
            return view('pages.adminMessages');
        }
        else
            return redirect()->back();
    }

    public function deleteCeleb(){
        $celeb = Session::get('celebrity');

        $facebookFeed = $celeb->facebookFeed;
        $twitterFeed = $celeb->instagramFeed;
        $instagramFeed = $celeb->twitterFeed;

        foreach ($facebookFeed as $relation) {
            $relation->delete();
        }

        foreach ($twitterFeed as $relation) {
            $relation->delete();
        }

        foreach ($instagramFeed as $relation) {
            $relation->delete();
        }

        $celeb->delete();
        Session::forget('celebrity');

        return redirect()->back();
    }

    public function updateCeleb(Request $request){
            $celebId = Session::get('celebrity')->id;
            $name = $request->input("name");
            $fb_id = $request->input("fb_id");
            $twt_id = $request->input("twt_id");
            $instagram_id = $request->input("instagram_id");
            $category = $request->input("category");
            $country = $request->input("country");
            if(strlen($country) == 2) {
                $country = $this->getCountryByCode($country);
            }
            $celeb = Celebrity::find($celebId);
            if(!$celeb) return;
            if($name != $celeb->name && $name!=null)$celeb->name=$name;
            if($fb_id != $celeb->fb_id && $fb_id!=null)$celeb->fb_id=$fb_id;
            if($twt_id != $celeb->twt_id && $twt_id!=null)$celeb->twt_id=$twt_id;
            if($instagram_id != $celeb->instagram_id && $instagram_id!=null)$celeb->instagram_id=$instagram_id;
            if($country !=null && strlen($country) == 2) {
                $country = $this->getCountryByCode($country);
            }
            if($country != $celeb->country)$celeb->country=$country;
            $categoryVar = Category::where("category",'=',$category)->get()->first();
            if(!$celeb->category()->first()->category == $category){
                if(!$categoryVar) {
                    $categoryVar = new Category(["category" => $category]);
                    $categoryVar->save();
                }
                $celeb->save();
                $celeb->category()->save($categoryVar);
            }
            $celeb->save();
            Session::flash('success','success');
            return view('pages.adminEditCeleb');
    }

    public function postMessage(Request $request){
        $messageText = $request->input("message");
        $subject = $request->input('subject');
        $user = Session::get('user');

        if($user){
            $message = new Message(["message"=>$messageText,"subject"=>$subject]);
            $user->message()->save($message);
            Session::flash('success','success');
        }
        return redirect('/contact');
    }

    public function getMessages(){
        return view('pages.adminMessages')->with("messages",Message::all());
    }

    public function deleteMessage($messageId){
        $message = Message::find($messageId);

        if($message){
            $message->delete();
        }
        return redirect()->back();
    }

    public function getCountryByCode($code){
        $countries = array
        (
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States Of',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard And Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );
        $code = strtoupper($code);
        if (array_key_exists($code, $countries)) {
            return $countries[$code];
        }
        return null;
    }
}
