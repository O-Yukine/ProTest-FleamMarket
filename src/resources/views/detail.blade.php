@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" type="text/css" />
@endsection

@section('content')
    <div class="product">
        <div class="left-content">
            {{-- <img src="{{ url($product->product_image) }}" alt="{{ $product->name }}"> --}}
            <img src="{{ asset('storage/product_images/' . $product->product_image) }}" alt="商品画像">
        </div>
        <div class="right-content">
            <div class="product-detail">
                <h2 class="product-title">{{ $product->name }}</h2>
                <p class="brand-name">{{ $product->brand }}</p>
                <p>¥<span class="product-price">{{ number_format($product->price) }}</span>(税込)</p>
                <div class="product-icons">
                    <form class="like-form" action="/item/{{ $product->id }}/like" method="post">
                        @csrf
                        @php
                            $heartImage = $product->likedBy->contains(auth()->id())
                                ? asset('images/hart_like.png')
                                : asset('images/hart_unlike.png');
                        @endphp

                        <button class="like__button" type="submit">
                            <img src="{{ $heartImage }}">
                        </button>
                        <p data-like-count="{{ $product->likedBy->count() }}">{{ $product->likedBy->count() }}</p>

                        {{-- @if ($product->likedBy->contains(auth()->id()))
                        <button><i class="fa-solid fa-star fa-lg"></i>
                        </button>
                    @else
                        <button><i class="fa-regular fa-star fa-lg"></i>
                        </button>
                    @endif --}}
                    </form>
                    <div class="comment-icon">
                        <img src="{{ asset('images/comment.png') }}" alt="/comment_icon">
                        <p>{{ $product->comments->count() }}</p>
                    </div>
                </div>
                <form class="form.order" action="/purchase/{{ $product->id }}" method="get">
                    @csrf
                    <button class="order__button-submit" type="submit">購入手続きへ</button>
                </form>

                <h3>商品説明</h3>
                <p class="product-contents">{{ $product->content }} </p>
                <h3>商品の情報</h3>
                <div class="product-info">
                    <div class="product-category">
                        <span class="info-label">カテゴリー</span>
                        <div class="category-list">
                            @foreach ($product->categories as $category)
                                <span class="category-chips">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="product-condition-box">
                        <span class="info-label">商品の状態</span>
                        <span class="product-condition">{{ $product->condition->name }}</span>
                    </div>
                </div>
            </div>
            <div class="product__comments">
                <h3>コメント({{ $product->comments->count() }})
                </h3>
                @isset($product->comments)
                    @foreach ($product->comments as $comment)
                        <div class="comment__title">
                            <img src="{{ asset('storage/profile_images/' . $comment->user->profile->profile_image) }}"
                                alt="プロフィール写真">
                            {{ $comment->user->name }}
                        </div>
                        <div class="comment__contents">
                            {{ $comment->comment }}
                        </div>
                    @endforeach
                @endisset
                <form class="comments-form" action="/item/{{ $product->id }}/comment" method="post">
                    @csrf
                    <p>商品へのコメント</p>
                    <div class="form__error">
                        @error('comment')
                            {{ $message }}
                        @enderror
                    </div>
                    <textarea name="comment"> </textarea>
                    <div class="comment__button">
                        <button class="comment__button-submit">コメントを送信する</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
@endsection
