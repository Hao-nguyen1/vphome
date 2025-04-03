<?php

namespace App\Http\Requests\Slide;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlideRequest extends FormRequest
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
            'keyword' => 'required|unique:slides',
            'slide.image' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên của Slide',
            'keyword.required' => 'Bạn chưa nhập từ khóa của slide',
            'slide.image.required' => 'Bạn chưa chọn hình ảnh nào cho slide'
        ];
    }
}
