<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\Product;
use App\Models\Purchase;

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

        if ($tab == 'sell') {

            $sell_items = Product::with('purchases')->where('user_id', $user->id)->get();
        }

        if ($tab == 'buy') {
            $purchased_items = Purchase::with('product')->where('user_id', $user->id)->get();
        }

        return view('mypage', compact('user', 'profile', 'sell_items', 'purchased_items', 'tab'));
    }
}
