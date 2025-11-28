@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection
@section('content')
    <div class="mypage">
        <div class="mypage-header">
            <div class="mypage__profile">
                <img
                    src="{{ $profile->profile_image ? asset('storage/profile_images/' . $profile->profile_image) : asset('images/default_profile.png') }}">
                <h3>{{ $user->name }}</h3>
            </div>
            <div class="mypage__update">
                <a class ="mypage__update-link" href="/mypage/profile">プロフィールを編集</a>
            </div>
        </div>
        <div class="mypage-list">
            <div class="products_nav">
                <a href="/mypage?page=sell" class="{{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
                <a href="/mypage?page=buy" class="{{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
            </div>
            <div class="products__list">
                @if ($tab === 'sell')
                    @foreach ($sell_items as $item)
                        <div class="card">
                            <img src="{{ asset('storage/product_images/' . $item->product_image) }}" alt="商品画像">
                            <div class="card-info">
                                <p>{{ $item->name }}</p>
                                @if ($item->is_sold)
                                    <span class="sold-label">sold</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
                @if ($tab === 'buy')
                    @foreach ($purchased_items as $purchase)
                        <div class="card">
                            <img src="{{ asset('storage/product_images/' . $purchase->product->product_image) }}" <div>
                            <div class="card-info">
                                <p>{{ $purchase->product->name }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection
