<!DOCTYPE html>
<head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報一覧画面</h1>
    <form action="{{ route('product.search') }}" method="GET">
        @csrf
        <div style="display: flex; gap: 50px;">
            <input type="text" name="keyword" placeholder="検索キーワード" style="width: 150px;" autocomplete="off">
            <select name="company" id="company_id">
                <option value="">メーカー名</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
            <input type="number" name="price_min" placeholder="最低価格" style="width: 100px;">
            <input type="number" name="price_max" placeholder="最高価格" style="width: 100px;">
            <input type="number" name="stock_min" placeholder="最低在庫数" style="width: 100px;">
            <input type="number" name="stock_max" placeholder="最高在庫数" style="width: 100px;">
            <button type="submit" class="btn btn-primary" style="width: 150px;">検索</button>
        </div>
    </form>
    <br>
    <br>
    <div><table>
    <thead>
    <tr>
        <th><a href="{{ route('product.index', ['sort' => 'id', 'order' => $sortField == 'id' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">ID</a></th>
        <th>商品画像</th>
        <th><a href="{{ route('product.index', ['sort' => 'product_name', 'order' => $sortField == 'product_name' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">商品名</a></th>
        <th><a href="{{ route('product.index', ['sort' => 'price', 'order' => $sortField == 'price' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">価格</a></th>
        <th><a href="{{ route('product.index', ['sort' => 'stock', 'order' => $sortField == 'stock' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">在庫数</a></th>
        <th>メーカー名</th>
        <th>操作</th>
        <th>
            <a href="{{ route('product.registration.create') }}" style="color: white; background-color: gray; padding: 5px; border-radius: 5px; text-align:center;">新検登録</a>
        </th>
    </tr>
</thead>
<tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td><img src="{{ asset('storage/'.$product->img_path) }}" style="max-width: 30px; height: auto;"></td>
            <td>{{ $product->product_name }}</td>
            <td>¥{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->company->company_name }}</td>
            <td>
                <a href="{{ route('product.show', ['id' => $product->id]) }}" class="btn btn-primary">詳細</a>
</td>
<td>
                <form method="POST" action="{{ route('product.destroyAsync', ['id' => $product->id]) }}" style="display: inline;">

    @csrf
    @method('DELETE')
    <button class="delete-button btn btn-danger" data-product-id="{{ $product->id }}">削除</button>
</td>
</form>
        </tr>
    @endforeach
</tbody>
        </table>
    </div>
    <script>
$(document).ready(function() {
    function updateSearchResults(sortField, sortOrder) {
        var keyword = $('#keyword').val();
        var company = $('#company_id').val();
        var price_min = $('#price_min').val();
        var price_max = $('#price_max').val();
        var stock_min = $('#stock_min').val();
        var stock_max = $('#stock_max').val();

        $.ajax({
            type: 'GET',
            url: '{{ route('product.search') }}',
            data: {
                keyword: keyword,
                company: company,
                price_min: price_min,
                price_max: price_max,
                stock_min: stock_min,
                stock_max: stock_max,
                sort: sortField,
                order: sortOrder
            },
            success: function(data) {
                $('#searchResults').html(data);
            }
        });
    }

    function handleDeleteResponse(data, productRow) {
        if (data.message) {
            productRow.hide();
            console.log('bad');
        } else if (data.error) {
            console.log('Error:', data.error);
        }
    }

    function purchaseProduct(productId) {
        $.ajax({
            type: 'POST',
            url: '/purchase/' + productId, 
            success: function(data) {
                console.log('Purchase successful:', data.message);
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
            }
        });
    }

    $('.purchase-button').on('click', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        purchaseProduct(productId);
    });

    $('.sortable-column').on('click', function(e) {
        e.preventDefault();
        var sortField = $(this).data('sort-field');
        var sortOrder = $(this).data('sort-order') === 'asc' ? 'desc' : 'asc';

        updateSearchResults(sortField, sortOrder);
    });

    $('.delete-button').on('click',function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var productRow = $(this).parents('tr');

        $.ajax({
            type: 'POST',
            data:{
                    '_method':'DELETE'
                },
                headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            url: 'products/async/' + productId,
            success:function(data) {
                handleDeleteResponse(data, productRow);
                productRow.remove();
                console.log('エラー');
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
            }
        });
    });

    updateSearchResults();

    $('#keyword, #company_id, #price_min, #price_max, #stock_min, #stock_max').on('input', function() {
        updateSearchResults();
    });
});
</script>

@endsection











