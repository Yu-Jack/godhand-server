<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use App\Image;
use App\Follow;
use Auth;
use Input;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function checked(Request $request)
    {
        $email =  $request->input('email');
        $password =  $request->input('password');
        // $email =  'jacktest@gmail.com';
        // $password =  'secret';
        $logged = false;
        $user_id = 0;
        if( Auth::attempt(['email'=>$email, 'password'=>$password])){
            $logged = true;
            $user_id = User::where('email', $email)->get()[0]->id;
        }
        // $request->session()->put('user_id', $user_id]);
        // Session::put('user_id',$user_id);
        $request->session()->put('user_id' , $user_id);
        return Response()->json(['logged' => $logged, 'user_id' => $user_id])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
        
        
    }

    public function insert(Request $request)
    {   
        // $images = User::where('name','jack')->get();
        // $images = User::find(1);
        // dd($images[0]->image);
        return Response()->json(['img' => 'http://yujack.docker:8080/img/2.jpg'])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    public function follow(Request $request){

        $user_id = $request->input('userId');
        $follow_id = $request->input('followId');
        $isFollow = false;

        if( count(Follow::where('user_id', $follow_id)->where('follow_id', $user_id)->get()) > 0 ){
            $isFollow = false;
            Follow::where('user_id', $follow_id)->where('follow_id', $user_id)->delete();
        }else{
            $isFollow = true;
            $follow = new Follow;
            $follow->user_id = $follow_id;
            $follow->follow_id = $user_id;
            $follow->save();
        }

        // hande followed and following number
        $current_user = User::where('id',$user_id);
        $follow_user = User::where('id',$follow_id);
        $current_user->update(['followed' => count($current_user->get()[0]->followed_table), 'following' => count($current_user->get()[0]->following_table)]);
        $follow_user->update(['followed' => count($follow_user->get()[0]->followed_table), 'following' => count($follow_user->get()[0]->following_table)]);

        return Response()->json(['success' => true, 'isFollow'=>$isFollow, 'target'=>User::where('id',$follow_id)->get()[0]])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    public function followed($id)
    {
        // get all the followed
        $followeds = User::where('id',$id)->get()[0]->followed_table;
        $followedf_info = [];
        foreach ($followeds as $followed) {
            $user = $followed->user[0];
            $user_info = array('id' => $user->id, 'name' => $user->name, 'avatar' => $user->avatar);
            array_push($followedf_info, $user_info);
        }

        return Response()->json(['followeds' => $followedf_info])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    public function following($id)
    {
        // get all the followed
        
        $followings = Follow::where('follow_id',$id)->get();
        $imagewithauth = [];
        $users_following = [];
        foreach ($followings as $following) {
            $user = User::where('id',$following->user_id)->get()[0];
            array_push($users_following, $user);
            $images = $user->image;
            foreach ($images as $image) {
                $auth = User::where('id', $image->user_id)->get()[0];
                $image = array_add($image, 'auth', $auth->name);
                $image = array_add($image, 'authavatar', $auth->avatar);
                $image = array_add($image, 'count_favorite', count($image->favorite));
                $image = array_add($image, 'count_views', $image->views);
                $image = array_add($image, 'count_comments', count($image->comment));
                array_push($imagewithauth, $image);
            }
        }
        
        return Response()->json(['followings' => $users_following, 'images' => $imagewithauth])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    public function favorite($id){

        // get all the favorite image id
        $favorites = User::where('id',$id)->get()[0]->favorite;
        $favorite_image = [];
        foreach ($favorites as $favorite) {
            $auth = User::where('id', $favorite->image->user_id)->get()[0];
            // use relation of ORM with id of image
            $image = $favorite->image ;
            $image = array_add($image, 'auth', $auth->name);
            $image = array_add($image, 'authavatar', $auth->avatar);
            $image = array_add($image, 'count_favorite', count($image->favorite));
            $image = array_add($image, 'count_views', $image->views);
            $image = array_add($image, 'count_comments', count($image->comment));

            array_push($favorite_image, $image);
        }
        return Response()->json(['favorites' => $favorite_image])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);

    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $user_name = $request->input('name');
        $user_email = $request->input('email');
        $user_password = $request->input('password');

        $new_user = new User;
        $new_user->name = $user_name;
        $new_user->email = $user_email;
        $new_user->password = $user_password;
        $new_user->save();

        return Response()->json(['success' => true, 'user_id' => $new_user->id])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
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
    public function show(Request $request)
    {
        $targetId = $request->input('targetId');
        $userId = $request->input('userId');
        if( is_null($targetId) ){
            $user_data = User::where('id', $userId)->get()[0];
        }else{
            $user_data = User::where('id', $targetId)->get()[0];
        }
        $images = $user_data->image;
        $image_detail = [];

        $isFollow = false;
        if(count(Follow::where('user_id', $targetId)->where('follow_id', $userId)->get()) > 0 ){
            // 有追蹤過了
            $isFollow = true;
        }else{
            // 沒追蹤過了
            $isFollow = false;
        }

        foreach ($images as $image) {
            $image = array_add($image, 'count_favorite', count($image->favorite));
            $image = array_add($image, 'count_views', $image->views);
            $image = array_add($image, 'count_comments', count($image->comment));

            array_push($image_detail, $image);
        }

        return Response()->json(['user'=>$user_data, 'images'=>$image_detail, 'isFollow'=>$isFollow])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request)
    {
        $user_avatar = $request->input('image');
        $user_name = $request->input('name');
        $image_name = $request->input('image_name');
        $user_description = $request->input('description');
        $user_id = $request->input('user');
        User::where('id',$user_id)->update(['description'=>$user_description, 'name'=>$user_name]);

        if( strpos($user_avatar, 'base64') === false ){
            // nothing to do
        }else{
            // 如果是新辦的話 , 不能先去刪除
            $user_old_avatar = User::where('id',$user_id)->get()[0]->avatar;
            if (is_file ($user_old_avatar)){
                unlink($user_old_avatar);
            }
            $user_avatar = explode(';base64,' , $user_avatar);
            $user_avatar_type = explode('image/' , $user_avatar[0])[1];
            $user_avatar = str_replace(' ', '+', $user_avatar[1]);
            $data = base64_decode($user_avatar);
            $file = 'img/user_avatar/' . md5(User::where('id',$user_id)->get()[0]->email . $image_name) . '.' . $user_avatar_type;
            $success = file_put_contents($file, $data);
            User::where('id',$user_id)->update(['avatar' => $file]);
        }

        return Response()->json(['success' => true])->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
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
