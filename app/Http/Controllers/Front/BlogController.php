<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status','active')->get();
        return view('front.blog.index',compact('blogs'));
    }

    public function show($id)
    {
        $blog = Blog::where('id',$id)->firstOrFail();
        return view('front.blog.show',compact('blog'));
    }





}
