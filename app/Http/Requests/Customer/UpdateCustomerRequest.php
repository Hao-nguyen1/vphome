<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the customer is authorized to make this request.
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
            'email' => 'required|string|email|unique:customers,email, '.$this->id.'|max:191',
            'name' => 'required|string',
            'customer_catalogue_id' => 'gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Bạn chưa nhập vào email.',
            'email.email' => 'Email chưa đúng định dạng. Ví dụ: abc@gmail.com',
            'email.unique' => 'Email đã tồn tại. Hãy chọn email khác',
            'email.string' => 'Email phải là dạng ký tự',
            'email.max' => 'Độ dài email tối đa 191 ký tự',
            'name.required' => 'Bạn chưa nhập Họ Tên',
            'name.string' => 'Họ Tên phải là dạng ký tự',
            'customer_catalogue_id.gt' => 'Bạn chưa chọn nhóm thành viên',
        ];
    }
}
