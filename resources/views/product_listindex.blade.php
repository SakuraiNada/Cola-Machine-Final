@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報一覧画面</h1>

    <form action="{{ route('product.search') }}" method="GET">
        @csrf
        <div style="display: flex; gap: 50px;">
            <input type="text" name="keyword" placeholder="検索キーワード" style="width: 150px;">
            <select name="company" id="company_id">
                <option value="">メーカー名</option> 
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary" style="width: 150px;">検索</button>
        </div>
    </form>
        <br>
        <div>
        <table style="width: 80%;" >
            <thead style="width: 80%;">
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th>操作</th>
                    <th> <a href="{{ route('product.registration.store') }}" style="color: white; background-color: gray; padding: 5px; border-radius: 5px; text-align:center;">新検登録</a>
                </tr>
            </thead>
            <br>
            <tbody  style="width: 70%; flex: 1;">
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td><img src="{{ asset('storage/' . $product->img_path) }}" style="max-width: 30px; height: auto;"></td>
                        <td>{{ $product->product_name }}</td>
                        <td>¥{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->company->company_name }}</td>
                        <td>
                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary">詳細</a>
                            <form method="POST" action="{{ route('product.delete', $product->id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@if(isset($keyword) && isset($results))
        <h2>Search Results for "{{ $keyword }}"</h2>
        <table style="width: 80%;" >
            <thead style="width: 80%;">
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th>操作</th>
                    <th> <a href="{{ route('product.registration.store') }}" style="color: white; background-color: gray; padding: 5px; border-radius: 5px; text-align:center;">新検登録</a>
                </tr>
            </thead>
            <br>
            <tbody  style="width: 70%; flex: 1;">
                @forelse($results as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->company }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->company->company_name }}</td>
                        <td>
                            <a href="{{ route('product.show', ['id' => $product->id]) }}" class="btn btn-primary">詳細</a>
                            <a href="{{ route('product.edit', ['id' => $product->id]) }}" class="btn btn-warning">削除</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No search results found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>
@endsection


