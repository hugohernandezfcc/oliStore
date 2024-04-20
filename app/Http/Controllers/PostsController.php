<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    
    public function getPosts(Request $request) 
    {
        $posts = Post::with([
            'postedBy',
            'task',
            'likes'
        ])->where('task_id', $request->target_id)->get();
        
        return response()->json($posts);
    }

    public function createPost(Request $request){
        
        $post = new Post();
        $post->content = $request->content;
        $post->task_id = $request->task_id;
        $post->user_id = Auth::id();
        $post->save();
        $post->user_data = User::find(Auth::id());
        return response()->json($post);
    }

    public function createLike(Request $request){
        
        $like = new Like();
        $like->post_id = $request->post_id;
        $like->user_id = Auth::id();
        $like->type = 'Like';
        $like->save();
        return response()->json($like);
    }

}
