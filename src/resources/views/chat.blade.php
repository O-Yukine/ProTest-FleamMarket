@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
    <div class="chat">
        <div class="chat-lef-tcontent">
            <h2>その他の取引</h2>
            @foreach ($transactionOnGoings as $transactionOngoing)
                <div class="on_going_transaction">
                    {{ $transactionOngoing->product->name }}
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
                <a href="">取引を完了する</a>
                <livewire:review />
            </div>
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
