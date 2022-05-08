<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class ForgetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        if ($this->header('Accept-Language') == 'ar') {
            return [
                'email.required' => 'البريد الالكتروني مطلوب',
                'email.email'    => 'البريد الالكتروني غير صحيح',
            ];
        } else {
            return [
                'email.required' => 'Email is required',
                'email.email'    => 'Email is not valid',
            ];
        }
    }
}
