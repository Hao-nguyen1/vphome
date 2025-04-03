<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
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
            'email' => 'required|email|string|unique:customers|max:191',
            'password' => 'required|string|min:6',
            're_password' => 'required|string|same:password'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào Họ tên.',
            'email.required' => 'Bạn chưa nhập vào Email.',
            'email.email' => 'Email chưa đúng định dạng. Ví dụ: abc@gmail.com',
            'email.unique' => 'Email đã tồn tại. Hãy chọn email khác',
            'email.string' => 'Email phải là dạng ký tự',
            'email.max' => 'Độ dài email tối đa 191 ký tự',
            'password.required' => 'Bạn chưa nhập vào mật khẩu.',
            'password.min' => 'Mật khẩu phải có tối thiểu 6 ký tự.',
            're_password.required' => 'Bạn chưa nhập vào ô Nhập lại mật khẩu.',
            're_password.same' => 'Mật khẩu không khớp.',
        ];
    }
}