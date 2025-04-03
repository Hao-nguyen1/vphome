<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class RecoverCustomerPasswordRequest extends FormRequest
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
    public function rules()
    {
        return [
            'password' => 'required|string',
            'new_password' => 'required|string|min:8', // Đảm bảo mật khẩu mới có ít nhất 8 ký tự
            're_new_password' => 'required|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Bạn chưa nhập mật khẩu hiện tại.',
            'new_password.required' => 'Bạn chưa nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            're_new_password.required' => 'Bạn chưa nhập lại mật khẩu mới.',
            're_new_password.same' => 'Mật khẩu nhập lại không khớp với mật khẩu mới.',
        ];
    }
}
