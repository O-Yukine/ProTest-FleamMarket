@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
    <div class="chat">
        <div class="chat-lef-tcontent">
            <h2>その他の取引</h2>
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
                        <img src="{{ $message->receiver->profile_image
                            ? asset('storage/profile_images/' . $message->receiver->profile_image)
                            : asset('images/default_profile.png') }}"
                            alt="ユーザープロフィール写真">
                        <p>{{ $message->receiver->name }}</p>
                        <p>{{ $message->content }}</p>
                    </div>
                @endif
            @endforeach
            <div class="chat-actions">
                <a href="">編集</a>
                <form action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit">削除</button>
                </form>
            </div>
            <form action="'/mypage/profile/chat'" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="chat-message">
                    <input type="text" name="content">
                    <input type="file" name="img">
                </div>
                <button type="submit">送信</button>
            </form>
        </div>
    @endsection
