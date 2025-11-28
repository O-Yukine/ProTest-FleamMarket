<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'content' => 'required|string|max:255',
            'product_image' => 'required|file|mimetypes:image/jpeg,image/png',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'condition_id' => 'required|exists:conditions,id',
            'price' => 'required|integer|min:0'
        ];
    }


    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'content.required' => '商品説明を入力してください',
            'content.max' => '商品説明は255文字以内で入力してください',
            'product_image.required' => '商品画像をアップロードしてください',
            'product_image.mimetypes' => '拡張子は.jpegもしくは.pngの画像のみ選択できます',
            'categories.required' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格は数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください'
        ];
    }
}
