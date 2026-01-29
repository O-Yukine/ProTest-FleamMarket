<?php

namespace App\Http\Controllers;


use App\Models\Chat;
use App\Models\Message;
use App\Http\Requests\ChatRequest;


class ChatController extends Controller
{
    public function showChatRoom($chat_id)
    {
        $user = auth()->user();

        $chat = Chat::with([
            'buyer.profile',
            'seller.profile',
            'product',
            'messages.sender',
            'messages.sender.profile'
        ])
            ->findOrFail($chat_id);

        $chat->messages()
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()->orderBy('created_at')->get();

        $latestMessage = $messages->last();

        $partner = $chat->seller_id === $user->id ? $chat->buyer : $chat->seller;

        $transactionOnGoings = Chat::with('product')
            ->whereNotIn('status', ['pending', 'completed'])
            ->where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })->where('id', '!=', $chat->id)
            ->orderByDesc('last_message_at')
            ->get();

        return view('chat', compact('chat', 'partner', 'messages', 'transactionOnGoings', 'latestMessage'));
    }

    public function sendMessage(ChatRequest $request, $chat_id)
    {

        $message =  Message::create([
            'chat_id' => $chat_id,
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
            'is_read' => false,
        ]);

        $message->chat()->update(['last_message_at' => now()]);

        if ($request->hasFile('chat_image')) {
            $filename = $request->file('chat_image')->getClientOriginalName();
            $request->file('chat_image')->storeAs('public/chat_images', $filename);
            $message->chat_image = $filename;
            $message->save();
        }

        $message->chat->openIfPending();

        return redirect("/chat-room/{$chat_id}");
    }

    public function updateMessage(ChatRequest $request, $message_id)
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

        abort_if($message->sender_id !== auth()->id(), 403);

        $chat = $message->chat;

        $message->delete();
        $latestMessage = $chat->messages()
            ->latest('created_at')
            ->first();

        $chat->update([
            'last_message_at' => $latestMessage?->created_at
        ]);

        return redirect("/chat-room/{$chat->id}");
    }
}
