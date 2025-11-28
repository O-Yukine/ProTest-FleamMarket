@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/update_profile.css') }}">
@endsection


@section('content')
    <div class="update_profile">
        <div class="profile__title">
            <h2>プロフィール設定</h2>
        </div>
        <form class="form" action="/mypage/profile" enctype="multipart/form-data" method="post">
            @csrf
            <div class="profile-image">
                <img src="{{ $profile->profile_image ? asset('storage/profile_images/' . $profile->profile_image) : '' }}">
                <label class="custom-file-input" for="profile_image">
                    <span class="file-text">画像を選択する</span>
                </label>
                <input type="file" name="profile_image" id="profile_image">
            </div>
            <div class="form__error">
                @error('profile_image')
                    {{ $message }}
                @enderror
            </div>
            <div class="profile__contents">
                <div class="input__gropu">
                    <div class="input__group-title">
                        <p>ユーザー名</p>
                    </div>
                    <div class="input__group-input">
                        <input type="text" name="name" value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="form__error">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="input__gropu">
                    <div class="input__group-title">
                        <p>郵便番号</p>
                    </div>
                    <div class="input__group-input">
                        <input type="text" name="post_code" value={{ old('post_code', $profile->post_code) }}>
                    </div>
                    <div class="form__error">
                        @error('post_code')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="input__gropu">
                    <div class="input__group-title">
                        <p>住所</p>
                    </div>
                    <div class="input__group-input">
                        <input type="text" name="address" value="{{ old('address', $profile->address) }}">
                    </div>
                    <div class="form__error">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="input__gropu">
                    <div class="input__group-title">
                        <p>建物名</p>
                    </div>
                    <div class="input__group-input">
                        <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}">
                    </div>
                </div>
                <div class="update-profile__button">
                    <button class="button__submit" type="submit">更新する</button>
                </div>
        </form>
        <script>
            document.getElementById('profile_image').addEventListener('change', function(e) {
                const fileName = e.target.files.length > 0 ? e.target.files[0].name : '画像を選択する';
                e.target.previousElementSibling.textContent = fileName;
            });
        </script>
    </div>
@endsection
