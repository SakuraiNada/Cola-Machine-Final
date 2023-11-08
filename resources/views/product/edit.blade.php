@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>商品情報編集画面</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('product.update', ['id' => $product->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="product_name">商品名<span class="required">*</span></label>
                <input type="text" name="product_name" id="product_id" value="{{ $product->product_name }}" required>
            </div>

            <div class="form-group">
                <label for="manufacturer_name">メーカー名<span class="required">*</span></label>
                <select name="company_id" id="company_id" required>
                    @foreach ($companies as $company)
                        <option value="{{$company->id}}" {{ $product->company_id == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">価格<span class="required">*</span></label>
                <input type="text" name="price" id="price" value="{{ $product->price }}" required>
            </div>

            <div class="form-group">
                <label for="stock">在庫数<span class="required">*</span></label>
                <input type="text" name="stock" id="stock" value="{{ $product->stock }}" required>
            </div>

            <div class="form-group">
                <label for="comment">コメント</label>
                <input type="text" name="comment" id="comment" value="{{ $product->comment }}">
            </div>

            <div class="form-group">
                <label for="img_path">商品画像</label>
                <input type="file" name="img_path" id="img_path">
            </div>

            <button type="submit" class="btn btn-orange">更新</button>
        </form>

        <a href="{{ route('product.show', ['id' => $product->id]) }}" class="btn btn-blue">戻る</a>
    </div>
@endsection


