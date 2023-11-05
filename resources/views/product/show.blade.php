@extends('layouts.app')

@section('content')
    <div class="container" style = "width:100%; flex: 5;">
        <h1>商品情報詳細画面</h1>
        <p><strong style="margin-right: 50px;">ID</strong> {{ $product->id }}</p>
    <p><strong style="margin-right: 50px;">商品画像</p></strong><img src="{{ asset('storage/' . $product->img_path) }}"style="max-width: 30px; height: auto;">
        <p><strong style="margin-right: 50px;">商品名</strong> {{ $product->product_name }}</p>
        <p><strong style="margin-right: 50px;">メーカー名</strong> {{ $product->company->company_name }}</p>
        <p><strong style="margin-right: 50px;">価格</strong> ¥{{ $product->price }}</p>
        <p><strong style="margin-right: 50px;">在庫数</strong> {{ $product->stock }}</p>
        <p><strong style="margin-right: 50px;">コメント</strong> {{ $product->comment }}</p>

        <div>
        <a href="{{ route('product.list') }}" class="btn btn-primary">戻る</a>

        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary">編集</a>
</div>
    </div>
@endsection




