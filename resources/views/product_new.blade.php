@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>商品新検登録画面</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('product.registration.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="product_name">商品名<span class="required">*</span></label>
                <input type="text" name="product_name" id="product_name" required>
            </div>

            <div class="form-group">
                <label for="manufacturer_name">メーカー名<span class="required">*</span></label>
                <select name="company_id">
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">価格<span class="required">*</span></label>
                <input type="text" name="price" id="price" required>
            </div>

            <div class="form-group">
                <label for="stock">在庫数<span class="required">*</span></label>
                <input type="text" name="stock" id="stock" required>
            </div>

            <div class="form-group">
                <label for="comment">コメント</label>
                <input type="text" name="comment" id="comment">
            </div>

            <div class="form-group">
                <label for="img_path">商品画像</label>
                <input type="file" name="img_path" id="img_path" accept=".jpeg, .jpg, .png">
            </div>

            <button type="submit" class="btn btn-primary">新検登録</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('product.list') }}" class="btn btn-secondary">戻る</a>
    </div>
@endsection




