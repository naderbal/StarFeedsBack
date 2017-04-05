<?php

namespace App\Http\Controllers;

use App\Category;
use App\Celebrity;
use ErrorException;
use Illuminate\Http\Request;

use App\Http\Requests;

class CelebrityController extends Controller
{
    public function getCelebsByName($celebName)
    {
        //todo ask kinane like instead of equals
        return Celebrity::where('name','=',$celebName)->get();
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
            $decoded = json_decode($result, true);
        }catch (ErrorException $e){
            echo 'exe'. $e;
            return null;
        }
        return $decoded["data"]["url"];
    }

}
