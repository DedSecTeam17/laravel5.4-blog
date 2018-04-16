<?php


namespace App\Http\Controllers;

use App\Category;
use App\Tag;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;


use  App\Post;
use Illuminate\Support\Facades\Session;

use Mews\Purifier\Facades\Purifier;



//inside the controller class
//use ValidatesRequests;
class PostController extends Controller
{


    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->Paginate(10);
        return view('posts.index')->withPosts($posts);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $categorise = Category::all();
        $tags=Tag::all();
        return view('posts.create')->withCategorise($categorise)->withTags($tags);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


//        dd($request);  day and dumb
        $this->validate($request, [
                'title' => 'required|max:255',
                'body' => 'required',
                'slug' => 'required | alpha_dash | min:5 | max:255|unique:posts,slug',
                'category_id' => 'required|integer'
            ]
        );


        $post = new Post();


        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id=$request->category_id;
        $post->body =Purifier::clean($request->body);

        $post->save();

        $post->tags()->sync($request->tags,false);


        Session::flash('success', 'Success now you post somethings');



        return redirect()->route('posts.show', $post->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $post = Post::find($id);
        return view('posts.show')->withPost($post);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        $categorise=Category::all();
        $tags=Tag::all();
        return view('posts.edit')->withPost($post)->withCategorise($categorise)->withTags($tags);

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate the data


        $post = Post::find($id);


        if ($request->input('slug') == $post->slug) {
            $this->validate($request, [
                    'title' => 'required|max:255',
                    'body' => 'required',
                    'category_id'=>'required|integer'

                ]
            );
        } else {
            $this->validate($request, [
                    'title' => 'required|max:255',
                    'body' => 'required',
                    'category_id'=>'required|integer',
                    'slug' => 'required | alpha_dash | min:5 | max:255|unique:posts,slug'
                ]
            );
        }


        $post->title = $request->input('title');
        $post->body =  Purifier::clean($request->input('body')) ;
        $post->slug = $request->input('slug');
        $post->category_id=$request->input('category_id');
        $post->save();
        $tags = $request->input('tags', []);
        $post->tags()->sync($tags, true);


//        $post->tags()->sync($request->tags,false);

        Session::flash('success', 'your post successfully posted');


        return redirect()->route('posts.show', $post->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $post = Post::find($id);
        $post->tags()->detach();


        $post->delete();

        Session::flash("success", "Your post has been deleted ");
        return redirect()->route('posts.index');
        //
    }

    public function  getSinglePost($id){
        $post=Post::find($id);
        return view('pages.single')->withPost($post);

    }
}
