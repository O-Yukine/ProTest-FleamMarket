@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell_item.css') }}">
@endsection
@section('content')
    <div class="sell-item">
        <div class="sell-item__title">
            <h2>商品の出品</h2>
        </div>
        <div class="sell-item__contents">
            <form class="form" action="/sell" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form__group">
                    <div class="form__group-title"><span>商品画像</span>
                    </div>
                    <div class="form__group-image">
                        <label for="product_image" class="custom-file-input">
                            <span class="file-text">画像を選択する</span>
                        </label>
                        <input type="file" name="product_image" id="product_image">
                    </div>
                    <div class="form__error">
                        @error('product_image')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="contents__subtitle">
                    <h3>商品の詳細</h3>
                </div>
                <div class="form__group">
                    <div class="form__group-title">
                        <span>カテゴリー</span>
                    </div>
                    <div class="form__group-chips">
                        @foreach ($categories as $category)
                            <input id="category_{{ $category->id }}" type="checkbox" name="categories[]"
                                value="{{ $category->id }}"
                                {{ in_array($category->id, old('categories', $selectedCategories ?? [])) ? 'checked' : '' }}>
                            <label for="category_{{ $category->id }}"> {{ $category->name }}</label>
                        @endforeach
                    </div>
                    <div class="form__error">
                        @error('categories')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title"><span>商品の状態</span>
                    </div>
                    <div class="form__group-select">
                        <select name="condition_id">
                            <option value="">選択してください</option>
                            @foreach ($conditions as $condition)
                                <option value="{{ $condition->id }}">
                                    {{ old('condition_id', $selectedConditionId ?? '') == $condition->id ? 'selected' : '' }}
                                    {{ $condition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form__error">
                        @error('condition_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="contents__subtitle">
                    <h3>商品名と説明</h3>
                </div>
                <div class="form__group">
                    <div class="form__group-title"><span>商品名</span>
                    </div>
                    <div class="form__group-input">
                        <input type="text" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form__error">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title"><span>ブランド名</span>
                    </div>
                    <div class="form__group-input">
                        <input type="text" name="brand" value="{{ old('brand') }}">
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title"><span>商品の説明</span>
                    </div>
                    <div class="form__group-input">
                        <textarea name="content" cols="30" rows="10">{{ old('content') }}</textarea>
                    </div>
                    <div class="form__error">
                        @error('content')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__group">
                    <div class="form__group-title"><span>販売価格</span>
                    </div>
                    <div class="form__group-input price-input">
                        <span class="price-symbol">¥</span>
                        <input type="text" name="price" value="{{ old('price') }}">
                    </div>
                    <div class="form__error">
                        @error('price')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="sell-item__button">
                    <button class="button__submit" type="submit">出品する</button>
                </div>
            </form>
        </div>

        <script>
            document.getElementById('product_image').addEventListener('change', function(e) {
                const fileName = e.target.files.length > 0 ? e.target.files[0].name : '画像を選択する';
                e.target.previousElementSibling.textContent = fileName;
            });
        </script>
    </div>
@endsection
