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
                        <img class="chat-image__profile"
                            src="{{ $message->sender->profile_image
                                ? asset('storage/profile_images/' . $message->sender->profile_image)
                                : asset('images/default_profile.png') }}"
                            alt="ユーザープロフィール写真">
                        <p>{{ $message->sender->name }}</p>
                        <p>{{ $message->content }}</p>
                        @if ($message->chat_image)
                            <img class="chat-image" src="{{ asset('storage/chat_images/' . $message->chat_image) }}"
                                alt="チャット送信画像">
                        @endif
                    </div>
                @else<div class="chat-contents__left">
                        <img class="chat-image__profile"
                            src="{{ $message->sender->profile_image
                                ? asset('storage/profile_images/' . $message->sender->profile_image)
                                : asset('images/default_profile.png') }}"
                            alt="ユーザープロフィール写真">
                        <p>{{ $message->sender->name }}</p>
                        <p>{{ $message->content }}</p>
                        @if ($message->chat_image)
                            <img class="chat-image" src="{{ asset('storage/chat_images/' . $message->chat_image) }}"
                                alt="チャット送信画像">
                        @endif
                    </div>
                @endif
                @if ($latestMessage && $message->id === $latestMessage->id && $message->sender_id === auth()->id())
                    <div class="chat-actions">
                        <button type="button" class="edit-btn" data-id="{{ $message->id }}"
                            data-content="{{ $message->content }}">
                            編集
                        </button>
                        <form action="/chat-room/{{ $message->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
                        </form>
                    </div>
                @endif
            @endforeach
            <form id="chatForm" action="/chat-room/{{ $chat->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="message_id" id="messageId">
                <input type="hidden" name="receiver_id" value="{{ $partner->id }}">
                <div class="chat-message">
                    <input type="text" name="content" id="chatInput" value="{{ old('content') }}">
                    <input type="file" name="chat_image" id="chat_image">
                    <label for="chat_image" class="custom-file-input">
                        <span class="file-text">画像を追加</span>
                    </label>
                </div>
                <button type="submit" id="submitBtn">送信</button>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const submitBtn = document.getElementById('submitBtn');
        const formMethod = document.getElementById('formMethod');
        const messageIdInput = document.getElementById('messageId');

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {

                chatInput.value = btn.dataset.content;

                chatForm.action = `/chat-room/${btn.dataset.id}`;
                formMethod.value = 'PATCH';
                messageIdInput.value = btn.dataset.id;

                submitBtn.textContent = '保存';
                chatInput.focus();
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openReviewBtn');
        const modal = document.getElementById('reviewModal');
        const stars = modal.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');
        let currentRating = 0;

        if (openBtn) {
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });
        }
        const showModalForSeller = @json($chat->status === 'buyer_reviewed' && auth()->id() === $chat->seller_id);
        if (showModalForSeller) {
            modal.classList.remove('hidden');
        }

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
