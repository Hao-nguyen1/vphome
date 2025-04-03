<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuCatalogueRequest extends FormRequest
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
            'keyword' => 'required|unique:menu_catalogues',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào tên nhóm menu.',
            'keyword.required' => 'Bạn chưa nhập vào từ khóa của menu.',
            'keyword.unique' => 'Nhóm menu đã tồn tại'
        ];
    }
}
