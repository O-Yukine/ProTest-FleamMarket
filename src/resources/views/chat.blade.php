@extends('layouts.app', ['simpleHeader' => true])

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
    <div class="chat">
        <div class="chat-main">
            <div class="chat-main__title">
                <img src="{{ $partner->profile_image
                    ? asset('storage/profile_images/' . $partner->profile_image)
                    : asset('images/default_profile.png') }}"
                    alt="ユーザープロフィール写真">
                <h1>{{ $partner->name }}さんとの取引画面</h1>
                @if ($chat->buyer_id === auth()->id())
                    <button class="chat-complite__button" id="openReviewBtn">取引を完了する</button>
                @endif
            </div>
            {{-- モーダル  --}}
            <div id="reviewModal" class="review hidden">
                <div class="review-inner">
                    <div class="modal-title">
                        <h1>取引が完了しました。</h1>
                    </div>
                    <p>今回の取引相手はどうでしたか?</p>
                    <div class="star-container">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <form action="/review" id="reviewForm" method="POST">
                        @csrf
                        <input type="hidden" name="score" id="ratingInput" value="0">
                        <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                        <input type="hidden" name="reviewee_id" value="{{ $partner->id }}">
                        <button class="review__submit" type="submit">送信する</button>
                    </form>
                </div>

            </div>
            <div class="chat-main__product">
                <img src="{{ asset('storage/product_images/' . $chat->product->product_image) }}" alt="商品画像">
                <div class="product__info">
                    <h3>商品名：{{ $chat->product->name }}</h3>
                    <h4>価格：¥ {{ $chat->product->price }}</h4>
                </div>
            </div>
            <div class="chat-main__messages">
                @foreach ($messages as $message)
                    @if ($message->sender_id === auth()->id())
                        <div class="chat-main__messages--right">
                            <div class="message__profile--right">
                                <p>{{ $message->sender->name }}</p>
                                <img class="message__profile--image"
                                    src="{{ $message->sender->profile_image
                                        ? asset('storage/profile_images/' . $message->sender->profile_image)
                                        : asset('images/default_profile.png') }}"
                                    alt="ユーザープロフィール写真">
                            </div>
                            <div class="message__contents--right">
                                <p>{{ $message->content }}</p>
                                @if ($message->chat_image)
                                    <img class="message__chat--image"
                                        src="{{ asset('storage/chat_images/' . $message->chat_image) }}" alt="チャット送信画像">
                                @endif
                            </div>
                        </div>
                    @else<div class="chat-main__messages--left">
                            <div class="message__profile--left">
                                <img class="message__profile--image"
                                    src="{{ $message->sender->profile_image
                                        ? asset('storage/profile_images/' . $message->sender->profile_image)
                                        : asset('images/default_profile.png') }}"
                                    alt="ユーザープロフィール写真">
                                <p>{{ $message->sender->name }}</p>
                            </div>
                            <div class="message__contents--left">
                                <p>{{ $message->content }}</p>
                                @if ($message->chat_image)
                                    <img class="message__chat--image"
                                        src="{{ asset('storage/chat_images/' . $message->chat_image) }}" alt="チャット送信画像">
                                @endif
                            </div>
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
                                <button class="delet-btn" type="submit">削除</button>
                            </form>
                        </div>
                    @endif
                @endforeach
            </div>
            @if (!in_array($chat->status, ['buyer_reviewed', 'seller_reviewed', 'completed']))
                <form id="chatForm" action="/chat-room/{{ $chat->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="message_id" id="messageId">
                    <input type="hidden" name="receiver_id" value="{{ $partner->id }}">
                    @if (count($errors) > 0)
                        <ul class="chat__error">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="message__submit">
                        <input type="text" name="content" id="chatInput" value="{{ old('content') }}"
                            placeholder="取引メッセージを記入してください">
                        <input type="file" name="chat_image" id="chat_image">
                        <label for="chat_image" class="custom-file-input">
                            <span class="file-text">画像を追加</span>
                        </label>
                        <button class="message__submit--button"type="submit" id="submitBtn"><img
                                src="{{ asset('images/submit.jpg') }}" alt="送信画像"></button>
                    </div>
                </form>
            @else
                <div class="chat-closed">
                    <div class="chat-closed__icon">🔒</div>
                    <p class="chat-closed__title">この取引は完了しました</p>
                    <p class="chat-closed__text">
                        これ以上メッセージを送信することはできません。
                    </p>
                </div>
            @endif
        </div>
        <aside class="chat-sidebar">
            <h2>その他の取引</h2>
            @foreach ($transactionOnGoings as $transaction)
                <div class="on_going_transaction">
                    <a href="/mypage/profile/item/{{ $transaction->product_id }}">
                        {{ $transaction->product->name }}</a>
                </div>
            @endforeach
        </aside>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const submitBtn = document.getElementById('submitBtn');
        const formMethod = document.getElementById('formMethod');
        const messageIdInput = document.getElementById('messageId');

        // 編集ボタン
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

        // モーダル
        const openBtn = document.getElementById('openReviewBtn');
        const modal = document.getElementById('reviewModal');
        const stars = modal.querySelectorAll('.star');
        const ratingInput = document.getElementById('ratingInput');
        let currentRating = 0;

        if (openBtn) openBtn.addEventListener('click', () => modal.classList.remove('hidden'));

        const showModalForSeller = @json($chat->status === 'buyer_reviewed' && auth()->id() === $chat->seller_id);
        if (showModalForSeller) modal.classList.remove('hidden');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                currentRating = star.dataset.value;
                ratingInput.value = currentRating;
                stars.forEach(s => s.classList.toggle('selected', s.dataset.value <=
                    currentRating));
            });
        });

        // 下書き保存機能
        const saved = localStorage.getItem('chat_draft_{{ $chat->id }}');
        if (saved) chatInput.value = saved;

        chatInput.addEventListener('input', () => {
            localStorage.setItem('chat_draft_{{ $chat->id }}', chatInput.value);
        });

        chatForm.addEventListener('submit', () => {
            localStorage.removeItem('chat_draft_{{ $chat->id }}');
        });
    });
</script>
