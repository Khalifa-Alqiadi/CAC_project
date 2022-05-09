<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chat = Chat::select()->where('aw_user_id',3)->where('owner_user_id',1)->get();
        
        return view('client.chat')->with(compact('chat'));
    
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|max:255',
        
        ]);
        $chat = Chat::create($data);
        return Response::json($chat);
    }
}
