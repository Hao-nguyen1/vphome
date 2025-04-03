<?php

namespace App\Http\Requests\Generate;

use Illuminate\Foundation\Http\FormRequest;

class StoreGenerateRequest extends FormRequest
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
            'name' => 'required|unique:generates',
            'schema' => 'required',
            // 'module_type' => 'gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào tên module.',
            'name.unique' => 'Module đã tồn tại.',
            // 'module_type.gt' => 'Bạn phải chọn kiểu module.',
            'schema.required' => 'Bạn chưa nhập vào schema của module.',
        ];
    }
}
