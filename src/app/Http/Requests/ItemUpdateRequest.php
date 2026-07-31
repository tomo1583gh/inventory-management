<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // ルートモデルバインディング　items/{item} を想定
        $itemId = $this->route('item')?->id ?? $this->route('item');

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', Rule::unique('items', 'sku')->ignore($itemId)],
            'unit' => ['required', 'string', 'max:50'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'カテゴリーを選択してください。',
            'category_id.integer' => 'カテゴリーが不正です。',
            'category_id.exists' => '選択したカテゴリーは存在しません。',

            'name.required' => '商品名を入力してください。',
            'name.string' => '商品名を正しく入力してください。',
            'name.max' => '商品名は255文字以内で入力してください。',

            'sku.required' => '管理番号を入力してください。',
            'sku.string' => '管理番号を正しく入力してください。',
            'sku.max' => '管理番号は255文字以内で入力してください。',
            'sku.unique' => 'この管理番号はすでに他の商品で使用されています。',

            'unit.required' => '単位を入力してください。',
            'unit.string' => '単位を正しく入力してください。',
            'unit.max' => '単位は50文字以内で入力してください。',

            'minimum_stock.required' => '最低在庫数を入力してください。',
            'minimum_stock.integer' => '最低在庫数は整数で入力してください。',
            'minimum_stock.min' => '最低在庫数は0以上で入力してください。',

            'note.string' => '商品メモを正しく入力してください。',
            'note.max' => '商品メモは1000文字以内で入力してください。',
        ];
    }
}
