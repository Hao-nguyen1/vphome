<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgencyRequest extends FormRequest
{
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
            'email' => 'required|string|email|unique:agencys|max:191',
            'name' => 'required|string',
            'code' => 'required|unique:agencys',
            'agency_catalogue_id' => 'gt:0',
            'password' => 'required|string|min:6',
            're_password' => 'required|string|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Bạn chưa nhập vào email.',
            'email.email' => 'Email chưa đúng định dạng. Ví dụ: abc@gmail.com',
            'email.unique' => 'Email đã tồn tại. Hãy chọn email khác',
            'code.required' => 'Bạn chưa nhập vào mã đại lý.',
            'code.unique' => 'Mã đại lý đã tồn tại. Hãy chọn lại mã',
            'email.string' => 'Email phải là dạng ký tự',
            'email.max' => 'Độ dài email tối đa 191 ký tự',
            'name.required' => 'Bạn chưa nhập tên đại lý',
            'agency_catalogue_id.gt' => 'Bạn chưa chọn nhóm đại lý',
            'name.string' => 'Tên đại lý phải là dạng ký tự',
            'password.required' => 'Bạn chưa nhập vào ô mật khẩu.',
            're_password.required' => 'Bạn phải nhập vào ô Nhập lại mật khẩu.',
            're_password.same' => 'Mật khẩu không khớp.',
        ];
    }
}
