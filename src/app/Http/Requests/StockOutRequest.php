<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockOutRequest extends FormRequest
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
        return [
            'item_id' => ['required', 'exists:items,id'],
            'qty' => ['required', 'numeric', 'gt:0'],
            'note' => ['nullable', 'string', 'max:255'],
            'acted_at' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'item_id.required' => '商品を選択してください。',
            'item_id.exists' => '選択した商品は存在しません。',

            'qty.required' => '数量を入力してください。',
            'qty.numeric' => '数量は数値で入力してください。',
            'qty.gt' => '数量は0より大きい値を入力してください。',

            'note.string' => '入出庫メモを正しく入力してください。',
            'note.max' => '入出庫メモは255文字以内で入力してください。',

            'acted_at.required' => '日時を入力してください。',
            'acted_at.date' => '日時を正しく入力してください。',
        ];
    }
}
