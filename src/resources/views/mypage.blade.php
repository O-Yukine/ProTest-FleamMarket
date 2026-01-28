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
                <div class="profile__info">
                    <h1>{{ $user->name }}</h1>
                    @if ($averageReview <= 0)
                        <p>レビューはまだありません</p>
                    @else
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $averageReview)
                                <span class="star_filled">★</span>
                            @else
                                <span class="star">★</span>
                            @endif
                        @endfor
                    @endif
                </div>
            </div>
            <div class="mypage__update">
                <a class ="mypage__update-link" href="/mypage/profile">プロフィールを編集</a>
            </div>
        </div>
        <div class="mypage-list">
            <div class="products_nav">
                <a href="/mypage?page=sell" class="{{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
                <a href="/mypage?page=buy" class="{{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
                <a href="/mypage?page=transaction" class="{{ $tab === 'transaction' ? 'active' : '' }}">取引中の商品
                    @if ($unReadCount > 0)
                        <span class="badge__tab">{{ $unReadCount }}</span>
                    @endif
                </a>
            </div>
            <div class="products__list">
                @if ($tab === 'sell')
                    @foreach ($sell_items as $item)
                        <div class="card">
                            <a href="/mypage/profile/item/{{ $item->id }}">
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
                            <a href="/mypage/profile/item/{{ $purchase->product_id }}">
                                <img
                                    src="{{ asset('storage/product_images/' . $purchase->product->product_image) }}"alt="商品画像">
                                <div class="card-info">
                                    <p>{{ $purchase->product->name }}</p>
                                </div>
                        </div>
                    @endforeach
                @endif
                @if ($tab === 'transaction')
                    @foreach ($transaction_items as $transaction)
                        <div class="card">
                            <a href="/mypage/profile/item/{{ $transaction->product_id }}">
                                @if ($transaction->unread_count > 0)
                                    <span class="badge__product">{{ $transaction->unread_count }}</span>
                                @endif
                                <img
                                    src="{{ asset('storage/product_images/' . $transaction->product->product_image) }}"alt="商品画像">
                                <div class="card-info">
                                    <p>{{ $transaction->product->name }}</p>
                                </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
