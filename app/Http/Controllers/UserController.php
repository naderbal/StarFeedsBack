<?php

namespace App\Http\Controllers;

use App\Category;
use App\Celebrity;
use App\Like;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;

class UserController extends Controller
{
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

    public function followCeleb(Request $request){
        $userId = $request->input("user_id");
        $celebId = $request->input("celeb_id");

        $user = User::find($userId);
        $celeb = Celebrity::find($celebId);

        if( count($user->celebrity()->where('name',$celeb->name)->get()) > 0){
            return;
        }

        $user->celebrity()->save($celeb);

        $category = $celeb->category;

        $likes = $user ->likes()->get();
        $isFound = false;
        foreach($likes as $li){
            if(!isset($li->category)){
                continue;
            }
            foreach($category as $cat){
                if($cat->category == $li->category->category) {
                    $li->score++;
                    $li->save();
                    $isFound = true;
                }
            }

        }
            if($isFound){
            echo'found';
            return;
            };


        foreach($category as $cat){
            $user->likes()->save(new Like(["score"=>1]));
            $like = $user->likes()->latest()->first();
            $like->category()->save($cat);
        }


        //return $like->category;
        //return $user->likes()->category()->get();
        //$user->likes()->category($category) ->score = $user->likes()->category() ->score+ 1 ;

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
