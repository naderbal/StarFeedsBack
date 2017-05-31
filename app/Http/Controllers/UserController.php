<?php

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
        $country = $request->input("country");
        if(strlen($country) == 2) {
            $country = $this->getCountryByCode($country);
        }
        $age = $request->input("age");
        $isAdmin = false;

        $isSuccessful = false;

        if($email!= null && User::where("email",'=',$email)->first() === null && $password != null){
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
        $country = $request->input('country');
        $user = User::find($id);
        $is_successful = true;

        if ($user == null){
            $is_successful = false;
        } else {
            if($name != null){
                $user->name = $name;
            }
            if($country != null){
                if(strlen($country) == 2) {
                    $country = $this->getCountryByCode($country);
                }
                $user->country = $country;
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
            array_push($returnCelebs, ["celeb"=>$celeb['celeb']]);
        }
        if (count($suggestionCelebs) == 0){
            $returnCelebs = $this->getSuggestionsForNewUsers($userId);
        }

        return $returnCelebs;
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

    public function postMessage(Request $request){
        $userId = $request->input("user-id");
        $messageText = $request->input("message");
        $user = User::find($userId);

        if($user){
            $message = new Message(["message"=>$messageText]);
            $user->message()->save($message);
        }
    }

    public function getMessages(){
        return Message::all();
    }

    public function deleteMessage(Request $request){
        $messageId = $request->input("message-id");

        $message = Message::find($messageId);

        if($message){
            $message->delete();
        }
    }

    public function deleteCeleb(Request $request){
        $celebId = $request->input("celeb-id");

        $celeb = Celebrity::find($celebId);
        if(!$celeb) return;
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
    }

    public function updateCeleb(Request $request){
        $celebId = $request->input("celeb-id");
        $name = $request->input("name");
        $fb_id = $request->input("fb_id");
        $twt_id = $request->input("twt_id");
        $instagram_id = $request->input("instagram_id");
        $category = $request->input("category");
        $country = $request->input("country");
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
