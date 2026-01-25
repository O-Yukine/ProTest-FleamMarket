@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
    <div class="chat">
        <div class="chat-lef-tcontent">
            <h2>その他の取引</h2>
            @foreach ($transactionOnGoings as $transaction)
                <div class="on_going_transaction">
                    <a href="/mypage/profile/item/{{ $transaction->product_id }}">
                        {{ $transaction->product->name }}</a>
                </div>
            @endforeach
        </div>
        <div class="chat-right-contents">
            <div class="chat-title">
                <img src="{{ $partner->profile_image
                    ? asset('storage/profile_images/' . $partner->profile_image)
                    : asset('images/default_profile.png') }}"
                    alt="ユーザープロフィール写真">
                <h1>{{ $partner->name }}さんとの取引画面</h1>
                @if ($chat->buyer_id === auth()->id())
                    <button id="openReviewBtn">取引を完了する</button>
                @endif
            </div>

            {{-- モーダル  --}}
            <div id="reviewModal" class="review hidden">
                <h1>取引が完了しました</h1>
                <p>今回の取引相手はどうでしたか?</p>
                <div class="star-container">
                    <span class="star" data-value="1">⭐︎</span>
                    <span class="star" data-value="2">⭐︎</span>
                    <span class="star" data-value="3">⭐︎</span>
                    <span class="star" data-value="4">⭐︎</span>
                    <span class="star" data-value="5">⭐︎</span>
                </div>
                <form action="/review" id="reviewForm" method="POST">
                    @csrf
                    <input type="hidden" name="score" id="ratingInput" value="0">
                    <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                    <input type="hidden" name="reviewee_id" value="{{ $partner->id }}">
                    <button type="submit">送信する</button>
                </form>
            </div>

            {{-- チャットメッセージの表示 --}}
            <div class="chat-product">
                <img src="{{ asset('storage/product_images/' . $chat->product->product_image) }}" alt="商品画像">
                <h2>{{ $chat->product->name }}</h2>
                <h3>{{ $chat->product->price }}</h3>
            </div>
            @foreach ($messages as $message)
                @if ($message->sender_id === auth()->id())
                    <div class="chat-contents__right">
                        <img src="{{ $message->sender->profile_image
                            ? asset('storage/profile_images/' . $message->sender->profile_image)
                            : asset('images/default_profile.png') }}"
                            alt="ユーザープロフィール写真">
                        <p>{{ $message->sender->name }}</p>
                        <p>{{ $message->content }}</p>
                    </div>
                @else<div class="chat-contents__left">
                        <img src="{{ $message->sender->profile_image
                            ? asset('storage/profile_images/' . $message->sender->profile_image)
                            : asset('images/default_profile.png') }}"
                            alt="ユーザープロフィール写真">
                        <p>{{ $message->sender->name }}</p>
                        <p>{{ $message->content }}</p>
                    </div>
                @endif
                @if ($latestMessage && $message->id === $latestMessage->id && $message->sender_id === auth()->id())
                    <div class="chat-actions">
                        <form action="/chat-room/{{ $message->id }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="content" value="{{ $message->content }}">
                            <button type="submit">編集</button>
                        </form>

                        <form action="/chat-room/{{ $message->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
                        </form>
                    </div>
                @endif
            @endforeach
            <form action="/chat-room/{{ $chat->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="chat-message">
                    <input type="hidden" name="receiver_id" value="{{ $partner->id }}">
                    <input type="text" name="content" value="{{ old('content') }}">
                    <input type="file" name="chat_image" id="chat_image">
                    <label for="chat_image" class="custom-file-input">
                        <span class="file-text">画像を追加</span>
                    </label>
                </div>
                <button type="submit">送信</button>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openReviewBtn');
        const modal = document.getElementById('reviewModal');
        const stars = modal.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');
        let currentRating = 0;

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        stars.forEach(star => {
            star.addEventListener('click', () => {
                currentRating = star.dataset.value;
                ratingInput.value = currentRating;

                stars.forEach(s => {
                    if (s.dataset.value <= currentRating) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
            });
        });
    });
</script>
