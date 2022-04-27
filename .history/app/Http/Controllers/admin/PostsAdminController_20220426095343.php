<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PostsAdminController extends Controller
{
    public function showAdminPosts(){
        // $categories = Category::select()->get();
        $user=User::with(['posts'])->get();
        // $laravelPosts = Category::find(1)->post()->where('title', 'laravel')->all();
        // $user = user::find('1')->posts()->get();
        return $user;
        foreach($user as $users)
        {

              foreach($users->posts as $posts)
        {
            return $posts->name;
        }
        }
         //return  $user;
        $postsAll = Post::select('posts.*','categories.name as category_name')
                            ->join("categories", "categories.id", "=", "posts.category_id");
        
        $route = Route::current()->getName();
        if($route == 'admin_posts'){
            return view('admin.adminManagePosts', [
                'postsAll' => $postsAll->where('posts.is_active', 0)->get(),
            ]);
        }elseif($route == 'Start_auction'){
            return view('admin.adminManageStartedAuction', [
                'postsAll' => $postsAll->where('posts.is_active', 1)
                                        ->where('end_date', '>=', date('Y-m-d'))->get()
            ]);
        }
    }
 
}