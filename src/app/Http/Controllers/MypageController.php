<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Review;



class MypageController extends Controller
{
    public function showProfile()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('update_profile', compact('user', 'profile'));
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = auth()->user();

        $profile = $request->only(['post_code', 'address', 'building']);


        if ($request->hasFile('profile_image')) {
            $filename = $request->file('profile_image')->getClientOriginalName();
            $request->file('profile_image')->storeAs('public/profile_images', $filename);
            $profile['profile_image'] = $filename;
        }

        $user->update(['name' => $request->input('name')]);
        $user->profile->update($profile);

        return redirect('/');
    }


    public function showMypage(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        $tab = $request->query('page', 'sell');

        $sell_items = collect();
        $purchased_items = collect();
        $transaction_items = collect();

        if ($tab == 'sell') {
            $sell_items = Product::with('purchases')->where('user_id', $user->id)->get();
        }
        if ($tab == 'buy') {
            $purchased_items = Purchase::with('product')
                ->where('user_id', $user->id)
                ->where('status', 'paid')
                ->get();
        }
        if ($tab == 'transaction') {
            $transaction_items = Chat::with('product')
                ->withCount([
                    'messages as unread_count' => function ($q) use ($user) {
                        $q->where('receiver_id', $user->id)
                            ->where('is_read', false);
                    }
                ])->whereIn('status', ['open', 'buyer_reviewed', 'seller_reviewed'])
                ->where(function ($q) use ($user) {
                    $q->where('buyer_id', $user->id)
                        ->orWhere('seller_id', $user->id);
                })
                ->with('product')
                ->get();
        }

        $unReadCount = Message::with('chat')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        $review = Review::where('reviewee_id', $user->id)->avg('score') ?? 0;
        $averageReview = round($review, 2);

        return view('mypage', compact(
            'user',
            'profile',
            'sell_items',
            'purchased_items',
            'transaction_items',
            'unReadCount',
            'averageReview',
            'tab'
        ));
    }

    public function redirectItem($item_id)
    {
        $chat = Chat::where('product_id', $item_id)->first();

        if ($chat) {
            return   redirect("/chat-room/{$chat->id}");
        } else {
            return redirect("/item/{$item_id}");
        }
    }
}
