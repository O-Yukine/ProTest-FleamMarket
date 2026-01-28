@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection
@section('content')
    <div class="order">
        <form class="form" action="/purchase/{{ $product->id }}" method="post">
            @csrf
            <div class="left-content">
                <div class="order-product">
                    <div class="order-image">
                        <img src="{{ asset('storage/product_images/' . $product->product_image) }}" alt="商品画像">
                    </div>
                    <div class="order-information">
                        <h1>{{ $product->name }}</h1>
                        <p>¥{{ number_format($product->price) }}</p>
                    </div>
                </div>
                <div class="payment-type">
                    <div class="payment-type__title">
                        <h2>支払い方法</h2>
                    </div>
                    <div class="form__error">
                        @error('payment_method')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="payment-type__select">
                        <livewire:payment />
                    </div>
                </div>
                <div class="shipping-address">
                    <div class="shipping-address__title">
                        <h2>配送先</h2>
                        <a href="/purchase/address/{{ $product->id }}">変更する</a>
                    </div>
                    <div class="shipping-address__contents">
                        <div class="form__error">
                            @error('post_code')
                                {{ $message }}
                            @enderror
                        </div>
                        <p>〒<input type="hidden" name="post_code"
                                value="{{ $shipping_address['post_code'] ?? $user->profile->post_code }}">
                            {{ $shipping_address['post_code'] ?? $user->profile->post_code }}</p>
                        <div class="form__error">
                            @error('address')
                                {{ $message }}
                            @enderror
                        </div>
                        <p><input type="hidden" name="address"
                                value="{{ $shipping_address['address'] ?? $user->profile->address }}">
                            {{ $shipping_address['address'] ?? $user->profile->address }}</p>

                        <p><input type="hidden" name="building"
                                value="{{ $shipping_address['building'] ?? ($user->profile->building ?? '') }}">
                            {{ $shipping_address['building'] ?? ($user->profile->building ?? '') }}</p>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <table class="total-price">
                    <tr>
                        <th>商品代金</th>
                        <td>¥{{ number_format($product->price) }}</td>
                    </tr>
                    <tr>
                        <th>支払い方法</th>
                        <td id="payment-info">
                            <livewire:payment-display />
                        </td>
                    </tr>
                </table>
                <div class="order__submit">
                    <button type="submit" class="order__submit-button">
                        購入する</button>
                </div>
        </form>
    </div>
    </div>
@endsection
