<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class TranslateRequest extends FormRequest
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
            'translate_name' => 'required',
            'translate_canonical' => [
                'required',
                function ($attribute, $value, $fail){
                    $option = $this->input('option');
                    $exist = DB::table('routers')
                        ->where('canonical', $value)
                        ->where('language_id', '<>',$option['languageId'])
                        ->where('id', '<>', $option['id'])
                    ->exists();

                    // dd($exist);

                    if($exist){
                        $fail('Đường dẫn đã tồn tại. Hãy chọn đường dẫn khác');
                    }
                }
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'translate_name.required' => 'Bạn chưa nhập vào tên ngôn ngữ.',
            'translate_canonical.required' => 'Bạn chưa nhập vào từ khóa của ngôn ngữ.',
            'translate_canonical.unique' => 'Từ khóa đã tồn tại hãy chọn từ khóa khác'
        ];
    }
}
