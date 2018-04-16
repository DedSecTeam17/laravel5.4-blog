<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use ValidatesRequests;

class CommentController extends Controller
{


    function __construct()
    {
        $this->middleware('auth',['except'=>'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$post_id)
    {
        $this->validate($request,[
            'name'=>'required | min:5 ',
            'email'=>'required | email '
            ,'comment_body'=>'required | min:20 '

        ]);

        $comment=new Comment();
        $post=Post::find($post_id);

        $comment->name=$request->name;
        $comment->email=$request->email;
        $comment->comment_body=$request->comment_body;
        $comment->post()->associate($post);
        $comment->approved=true;


        $comment->save();
        Session::flash('success',"your comment has been added");


      return  redirect()->route('single',$post->id);



        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $comment=Comment::find($id);


        return view('comments.edit')->withComment($comment);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $post_id)
    {
        //
        $this->validate($request,[
            'name'=>'required | min:5 ',
            'email'=>'required | email '
            ,'comment_body'=>'required | min:20 '

        ]);

        $comment=Comment::find($post_id);

        $comment->name=$request->name;
        $comment->email=$request->email;
        $comment->comment_body=$request->comment_body;


        $comment->save();
        Session::flash('success',"your comment has been updated");


        return  redirect()->route('posts.show',$comment->post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //
        $comment=Comment::find($id);

        $comment->delete();

        Session::flash('success','comment has been deleted');



        return redirect()->route('posts.index');





    }
}
