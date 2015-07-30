<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Input;
use App\User;
use App\View;
use App\Image;
use App\Favorite;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $imagewithauth = [];
        $images = Image::all();

        $user_id = $request->input('user_id');

        foreach ($images as $image) {
            $auth = User::where('id', $image->user_id)->get()[0];

            // after logined
            $isLiked = Favorite::where('user_id', $user_id)->where('image_id', $image->id )->get();

            if(count($isLiked) == 1){
                $image = array_add($image, 'isLiked', true);
            }else{
                $image = array_add($image, 'isLiked', false);
            }

            
            $image = array_add($image, 'auth', $auth->name);
            $image = array_add($image, 'authId', $auth->id);
            $image = array_add($image, 'authavatar', $auth->avatar);
            $image = array_add($image, 'count_favorite', count($image->favorite));
            $image = array_add($image, 'count_views', $image->views);
            $image = array_add($image, 'count_comments', count($image->comment));

            array_push($imagewithauth, $image);
        }
        return Response()->json(['images'=>$imagewithauth])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);;
    }

    public function like(Request $request){
        $user_id = $request->input('user_id');
        $image_id = $request->input('image_id');

        if($user_id == 0 ){
            return Response()->json(['exist'=>false])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);;
        }
        $duplicate = Favorite::where('user_id', $user_id)->where('image_id', $image_id)->get();

        if(count($duplicate) == 0){
            $favorite = new Favorite;
            $favorite->user_id = $user_id;
            $favorite->image_id = $image_id;
            $favorite->save();
            return Response()->json(['insert'=>true])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);;
        }else{
            Favorite::where('user_id', $user_id)->where('image_id', $image_id)->delete();
            return Response()->json(['delete'=>true])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {   
        $img = $request->input('image');
        $img_name = $request->input('name');
        $title = $request->input('title');
        $content = $request->input('content');
        $user = $request->input('user');

        $img = explode(';base64,' , $img);
        $img_type = explode('image/' , $img[0])[1];
        $img = str_replace(' ', '+', $img[1]);
        $data = base64_decode($img);
        $file = 'img/' . md5($img_name) . '.' . $img_type;
        $success = file_put_contents($file, $data);

        $new_image = new Image;
        $new_image->user_id = $user;
        $new_image->image_url = $file;
        $new_image->title = $title;
        $new_image->description = $content;
        $new_image->views = 0;
        $new_image->save();

        $imageId = Image::where('user_id', $user)->where('image_url', $file)->get()[0]->id;

        return Response()->json(['success' => true, 'imageId' => $imageId])->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $image = Image::where('id', $id)->get()[0];
        Image::where('id', $id)->update(['views'=>$image->views + 1]);
        $comments = $image->comment;
        $comments_with_userInfo = [];

        foreach ($comments as $comment) {
            $comment = array_add($comment, 'name', $comment->user[0]->name);
            $comment = array_add($comment, 'avatar', $comment->user[0]->avatar);
            array_push($comments_with_userInfo, $comment);
        }
        
        // dd($comments);
        // $users = $comments->user;
        return Response()->json(['image' => $image, 'comments' => $comments_with_userInfo])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
