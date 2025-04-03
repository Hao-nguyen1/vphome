<?php

namespace App\Http\Requests\Construct;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConstructRequest extends FormRequest
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
            'name' => 'required',
            'code'=> 'required|unique:constructions,code, '.$this->id.'',
            'agency_id' => 'gt:0',
            'customer_id' => 'gt:0',
            'address' => 'required',
            'workshop' => 'required',
            'point' => 'required',
            'invester' => 'required',
            // 'province_id' => 'gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào tên công trình.',
            'code.unique' => 'Mã công trình đã tồn tại. Hãy nhập lại mã.',
            'code.required' => 'Bạn chưa nhập mã công trình',
            'agency_id.gt' => 'Bạn chưa chọn thông tin đại lý.',
            'customer_id.gt' => 'Bạn chưa chọn thông tin khách hàng.',
            'provine.gt' => 'Bạn chưa chọn thành phố.',
            'address.required' => 'Bạn chưa nhập địa điểm.',
            'workshop.required' => 'Bạn chưa nhập xưởng.',
            'invester.required' => 'Bạn chưa nhập chủ đầu tư.',
            'point.required' => 'Bạn chưa nhập điểm tích lũy.',
            'point.integer' => 'Điểm tích lũy không đúng định dạng',
        ];
    }
}
