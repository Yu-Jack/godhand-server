<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;
use Session;
use App\User;
use App\Activity;
use App\ActivityMember;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $activitysWithUserInfo = [];
        $activitys = Activity::all();
        foreach ($activitys as $activity) {
            $auth = User::where('id', $activity->user_id)->get()[0];

            $activity = array_add($activity, 'auth', $auth->name);
            $activity = array_add($activity, 'authavatar', $auth->avatar);

            array_push($activitysWithUserInfo, $activity);
        }
        return Response()->json(['activitys' => $activitysWithUserInfo])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $activity_image = $request->input('image');
        $activity_image_name = $request->input('name');
        $activity_title = $request->input('title');
        $activity_description = $request->input('description');
        $activity_position = $request->input('position');
        $activity_start = $request->input('start_time');
        $activity_end = $request->input('end_time');
        $activity_author = $request->input('user');
        
        // handle base64 image
        $activity_image = explode(';base64,' , $activity_image);
        $activity_image_type = explode('image/' , $activity_image[0])[1];
        $activity_image = str_replace(' ', '+', $activity_image[1]);
        $data = base64_decode($activity_image);
        $file = 'img/activity/' . md5(User::where('id',$activity_author)->get()[0]->email . $activity_image_name) . '.' . $activity_image_type;
        $success = file_put_contents($file, $data);

        // create a new row to Activity
        $activity = new Activity;
        $activity->user_id = $activity_author;
        $activity->title = $activity_title;
        $activity->description = $activity_description;
        $activity->image_url = $file;
        $activity->position = $activity_position;
        $activity->start_at = date_format(date_create($activity_start),'Y-m-d H:i:s');
        $activity->end_at = date_format(date_create($activity_end),'Y-m-d H:i:s');
        $activity->save();

        return Response()->json(['success' => true, 'activity_id'=>$activity->id])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);

        
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
        $activity = Activity::where('id', $request->input('id'))->get()[0];

        $isAttend = false;
        if( count(ActivityMember::where('user_id',$request->input('user'))->where('activity_id', $request->input('id'))->get()) > 0){
            $isAttend = true;
        }else{
            $isAttend = false;
        }
        // ActivityMember::where('activity_id',$id)->where('user_id',$user_id)->get();
        // 

        return Response()->json(['activity' => $activity, 'isAttend'=>$isAttend])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function attend(Request $request)
    {
        $activity_id = $request->input('id');
        $user_id = $request->input('user');
        $isAttend = false;

        if( count(ActivityMember::where('user_id',$user_id)->where('activity_id', $activity_id)->get()) > 0){
            $isAttend = false;
            ActivityMember::where('user_id',$user_id)->where('activity_id', $activity_id)->delete();
        }else{
            $isAttend = true;
            $new_attend = new ActivityMember;
            $new_attend->user_id = $user_id;
            $new_attend->activity_id = $activity_id;
            $new_attend->save();
        }
        Activity::where('id', $activity_id)->update(['member' => count(Activity::where('id', $activity_id)->get()[0]->member_table) ]);
        return Response()->json(['success' => true, 'isAttend'=>$isAttend])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
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
