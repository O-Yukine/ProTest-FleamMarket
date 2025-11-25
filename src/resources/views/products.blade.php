@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
@endsection
@section('content')
    <div class="products">
        @if (request('message'))
            <script>
                alert("{{ request('message') }}");
            </script>
        @endif
        <div class="products_nav">
            <a href="/?tab=recommended" class="{{ $tab == 'recommended' ? 'active' : '' }}">おすすめ</a>
            <a href="/?tab=mylist" class="{{ $tab == 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
        <div class="products__list">
            @foreach ($products as $product)
                <div class="card">
                    <a href="/item/{{ $product->id }}">
                        <img src="{{ asset('storage/product_images/' . $product->product_image) }}" alt="商品画像">
                        <div class="card-info">
                            <p>{{ $product->name }}</p>
                            @if ($product->is_sold)
                                <span class="sold-label">sold</span>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
