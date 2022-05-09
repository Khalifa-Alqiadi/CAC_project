<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Models\Todo;
class ChatController extends Controller
{
    public function index()
    {
        $chat = Chat::select()->where('aw_user_id',3)->where('owner_user_id',1)->get();
        
        return view('client.chat')->with(compact('chat'));
     
    }

    public function store(Request $request)
    {
        // $data = $request->validate([
        //     'title' => 'required|max:255',
        //     'description' => 'required'
        // ]);
        // $chat = Todo::create($data);
        // return Response::json($chat);

        $data = $request->validate([
            'message' => '',
            'aw_user_id' => '',
            'owner_user_id' => '',
            'post_id' => '', 
             'admin_id' => '',
        ]);
        
        $chat = Chat::create($data);
        return Response::json($chat);
    

    }

}