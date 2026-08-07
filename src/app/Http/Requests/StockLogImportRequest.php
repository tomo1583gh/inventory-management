<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockLogImportRequest extends FormRequest
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
            'csv_file' => [
                'required',
                'file',
                'mimes:csv,txt',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'csv_file.required' => 'CSVファイルを選択してください。',
            'csv_file.file' => 'ファイルを選択してください。',
            'csv_file.mimes' => 'CSV形式のファイルを選択してください。',
            'csv_file.max' => 'CSVファイルは2MB以内にしてください。',
        ];
    }
}
