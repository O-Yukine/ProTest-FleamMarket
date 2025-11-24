<?php

namespace App\Http\Controllers;


use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Product;
use App\Models\User;
use App\Models\Purchase;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function showOrder($item_id)
    {
        $product = Product::find($item_id);
        $user = User::with('profile')->find(auth()->id());

        $shipping_address = session('shipping_address');

        return view('purchase', compact('product', 'user', 'shipping_address'));
    }

    public function completeOrder(Purchaserequest $request, $item_id)
    {
        $purchase =  Purchase::create([
            'status' => 'pending',
            'user_id' => auth()->id(),
            'product_id' => $item_id,
            'payment_method' => $request->payment_method,
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building
        ]);

        $product = Product::find($item_id);
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $method = $request->payment_method === 'credit' ? ['card'] : ['konbini'];

        $checkout = Session::create([
            'payment_method_types' => $method,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => intval($product->price),
                    'product_data' => [
                        'name' => $product->name,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/') . '?message=' . urlencode('決済が完了しました'),
            'metadata' => [
                'purchase_id' => $purchase->id
            ],
        ]);


        return redirect($checkout->url);
    }

    public function showShippingAddress($item_id)
    {
        $profile = auth()->user()->profile;

        return view('update_address', compact('item_id', 'profile'));
    }

    public function updateShippingAddress(AddressRequest $request, $item_id)
    {
        $shipping_address = [
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building
        ];

        return redirect("/purchase/{$item_id}")->with('shipping_address', $shipping_address);
    }
}
