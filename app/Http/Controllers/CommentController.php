<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use App\Image;
use App\Comment;
use App\SalesComment;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        //
        $user_id = $request->input('user');
        $image_id = $request->input('imageId');
        $text = $request->input('text');

        $comment = new Comment;
        $comment->image_id = $image_id;
        $comment->user_id = $user_id;
        $comment->comment = $text;
        $comment->save();

        return Response()->json(['success' => true])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
    }

    public function sales_comment(Request $request){
        $user_id = $request->input('user');
        $sales_id = $request->input('salesId');
        $text = $request->input('text');

        $comment = new SalesComment;
        $comment->sales_id = $sales_id;
        $comment->user_id = $user_id;
        $comment->comment = $text;
        $comment->save();

        return Response()->json(['success' => true])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);
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
        //
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
