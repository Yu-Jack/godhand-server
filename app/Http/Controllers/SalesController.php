<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Input;
use App\User;
use App\Sales;
use App\SalesImage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $saleswithauth = [];
        $sales = Sales::all();

        foreach ($sales as $sale) {
            $auth = User::where('id', $sale->user_id)->get()[0];

            $sale = array_add($sale, 'auth', $auth->name);
            $sale = array_add($sale, 'authId', $auth->id);
            $sale = array_add($sale, 'authavatar', $auth->avatar);
            $sale = array_add($sale, 'count_views', $sale->views);
            $sale = array_add($sale, 'count_comments', count($sale->comment));

            array_push($saleswithauth, $sale);
        }
        return Response()->json(['sales'=>$saleswithauth])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $imgs = $request->input('images');
        $user_id = $request->input('userId');
        $title = $request->input('title');
        $moeny = $request->input('money');
        $content = $request->input('content');
        $temp_urls = [];

        foreach ($imgs as $img) {

            $image_data = $img['data'];
            $image_name = $img['name'];

            $image_data = explode(';base64,' , $image_data);
            $img_type = explode('image/' , $image_data[0])[1];
            $image_data = str_replace(' ', '+', $image_data[1]);
            $data = base64_decode($image_data);
            $file = 'img/' . md5($image_name) . '.' . $img_type;
            array_push($temp_urls, $file);
            $success = file_put_contents($file, $data);
        }

        $sales = new Sales;
        $sales->user_id = $user_id;
        $sales->title = $title;
        $sales->description = $content;
        $sales->money = $moeny;
        $sales->image_url = $temp_urls[0];
        $sales->save();

        foreach ($temp_urls as $temp_url) {
            $sales_image = new SalesImage;
            $sales_image->sales_id = $sales->id;
            $sales_image->image_url = $temp_url;
            $sales_image->save();
        }

        return Response()->json(['success'=>true, 'sales_id'=>$sales->id])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true);    
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
        $sales = Sales::where('id', $id)->get()[0];
        Sales::where('id', $id)->update(['views'=>$sales->views + 1]);
        $comments = $sales->comment;
        $comments_with_userInfo = [];

        foreach ($comments as $comment) {
            $comment = array_add($comment, 'name', $comment->user[0]->name);
            $comment = array_add($comment, 'avatar', $comment->user[0]->avatar);
            array_push($comments_with_userInfo, $comment);
        }
        
        // dd($comments);
        // $users = $comments->user;
        return Response()->json(['images'=>$sales->image, 'sales' => $sales, 'comments' => $comments_with_userInfo])->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With')->header('Access-Control-Allow-Credentials', true); 
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
