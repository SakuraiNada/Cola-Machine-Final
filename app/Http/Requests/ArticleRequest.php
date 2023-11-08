<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Allow the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable|string|max:255',
        ];
    }


    public function attributes()
    {
        return [
            'product_name' => '商品名',
            'price' => '価格',
            'stock' => '在庫',
            'comment' => 'コメント'
        ];
    }

    /**
     * エラーメッセージ
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_name.required' => '商品名を入力してください',
            'product_name.string' => '商品名は文字で入力してください',
            'price.required' => '価格を入力してください',
            'price.numeric' => '価格は数字で入力してください',
            'stock.required' => '在庫を入力してください',
            'stock.numeric' => '在庫は数字で入力してください',
            'comment.max' => 'コメントは255文字以内で入力してください',
        ];
    }

}

