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
            <div class="chat-title"><img src="" alt="ユーザープロフィール写真">
                <h1>ユーザー名さんとの取引画面</h1>
                <a href="">取引を完了する</a>
                <livewire:review />
            </div>
            <div class="chat-product"><img src="" alt="商品画像">
                <h2>商品名</h2>
                <h3>商品価格</h3>
            </div>
            <div class="chat-contents">
                <img src="" alt="プロフィール画像">
                <p>ユーザー名</p>
                <p>チャット内容がここに出る</p>
            </div>
            <div class="chat-actions">
                <a href="">編集</a>
                <form action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit">削除</button>
                </form>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="chat-message">
                    <input type="text" name="content">
                    <input type="file" name="image">
                </div>
                <button type="submit">送信</button>
            </form>
        </div>
    @endsection
