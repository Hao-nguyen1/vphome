<?php

namespace App\Http\Requests\Widget;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWidgetRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'keyword' => 'required|unique:widgets,keyword, '.$this->id.'',
            'short_code' => 'required|unique:widgets,short_code, '.$this->id.'',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên của Widget',
            'keyword.required' => 'Bạn chưa nhập từ khóa của Widget',
            'keyword.unique' => 'Từ khóa đã tồn tại, hãy chọn từ khóa khác',
            'short_code.unique' => 'Shortcode đã tồn tại, hãy chọn tên shortcode khác',
        ];
    }
}
