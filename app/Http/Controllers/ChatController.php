<?php

namespace App\Http\Controllers;

use App\Events\SendChat;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('chat');
    }

    public function fetchMessages(){
        return Chat::with('user')->get();
    }

    public function getUsers(Request $request){
        return User::where('id', '!=', auth()->user()->id)->get();
    }

    public function getUser(Request $request){
        return auth()->user();
    }

    public function sendMessage(Request $request){
        $chat = auth()->user()->chats()->create([
            'message' => $request->message,
        ]);

        broadcast(new SendChat($chat->load('user')))->toOthers();

        return ['status' => 'success', 'data' => $chat];
    }
}
