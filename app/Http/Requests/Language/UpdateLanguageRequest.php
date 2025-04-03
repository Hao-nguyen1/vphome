<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest
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
            'canonical' => 'required|unique:languages,canonical, '.$this->id.'',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào tên ngôn ngữ.',
            'canonical.required' => 'Bạn chưa nhập vào từ khóa của ngôn ngữ.',
            'canonical.unique' => 'Từ khóa đã tồn tại hãy chọn từ khóa khác'
        ];
    }
}
