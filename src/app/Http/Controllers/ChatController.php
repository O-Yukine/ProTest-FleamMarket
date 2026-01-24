<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;


class ChatController extends Controller
{
    public function showChatRoom($chat_id)
    {

        $chat = Chat::with(['buyer', 'seller', 'product', 'messages.sender'])
            ->findOrFail($chat_id);

        $partner = $chat->seller_id === auth()->id() ? $chat->buyer : $chat->seller;
        $messages = $chat->messages->sortBy('created_at');

        return view('chat', compact('chat', 'partner', 'messages'));
    }
}
