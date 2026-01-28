<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Chat;
use App\Notifications\TransactionCompleteNotification;

class ReviewController extends Controller
{
    public function submitReview(Request $request)
    {
        $user = auth()->user();

        $chat = Chat::findOrFail($request->chat_id);

        $existingReview = Review::where('chat_id', $chat->id)
            ->where('reviewer_id', $user->id)
            ->first();

        if (!$existingReview) {
            Review::create([
                'chat_id' => $chat->id,
                'reviewer_id' => $user->id,
                'reviewee_id' => $request->reviewee_id,
                'score' => $request->score,
            ]);
        }


        $buyerReviewed = $chat->reviews()->where('reviewer_id', $chat->buyer_id)->exists();
        $sellerReviewed = $chat->reviews()->where('reviewer_id', $chat->seller_id)->exists();


        if ($buyerReviewed && $sellerReviewed) {
            $chat->status = 'completed';
        } elseif ($user->id === $chat->buyer_id) {
            $chat->status = 'buyer_reviewed';
            $chat->seller->notify(new TransactionCompleteNotification($chat));
        } elseif ($user->id === $chat->seller_id) {
            $chat->status = 'seller_reviewed';
        } else {
            $chat->status = 'open';
        }

        $chat->save();

        return redirect('/');
    }
}
