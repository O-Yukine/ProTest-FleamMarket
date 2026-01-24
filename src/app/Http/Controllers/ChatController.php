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

    public function sendMessage(Request $request, $chat_id)
    {

        $message =  Message::create([
            'chat_id' => $chat_id,
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'is_read' => false,
        ]);

        if ($request->hasFile('chat_image')) {
            $filename = $request->file('chat_image')->getClientOriginalName();
            $request->file('chat_image')->storeAs('public/chat_images', $filename);
            $message['chat_image'] = $filename;
            $message->save();
        }

        $message->chat->openIfPending();

        return redirect("/chat-room/{$chat_id}");
    }

    public function updateMessage(Request $request, $message_id)
    {

        $message = Message::findOrFail($message_id);
        $message->update([
            'content' => $request->content
        ]);

        return redirect("/chat-room/{$message->chat_id}");
    }

    public function deleteMessage($message_id)
    {
        $message = Message::findOrFail($message_id);
        $chat_id = $message->chat_id;
        $message->delete();

        return redirect("/chat-room/{$chat_id}");
    }
}
