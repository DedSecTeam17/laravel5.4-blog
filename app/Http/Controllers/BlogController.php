<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    //

    public function getSingle($slug)
    {
//        get and first : first stop when it,s find the first slug

        $post = Post::where('slug', '=', $slug)->first();

        return view('blog.single')->withPost($post);


    }

    public  function getBlogIndex(){

        $posts=Post::orderBy('id','desc')->paginate(10);

        return  view('blog.index')->withPosts($posts);
    }


}
