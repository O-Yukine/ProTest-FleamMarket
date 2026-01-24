<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    function showChatRoom($chat_id)
    {

        $chat = Chat::findOrFail($chat_id);

        return view('chat', compact('chat'));
    }
}
