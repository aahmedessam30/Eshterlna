<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class ResetPasswordRequest extends FormRequest
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
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'max:20', 'confirmed'],
            'token'    => ['required', 'string']
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
                'email.required'     => 'البريد الالكتروني مطلوب',
                'email.email'        => 'البريد الالكتروني غير صحيح',
                'password.required'  => 'كلمة المرور مطلوبة',
                'password.min'       => 'كلمة المرور يجب أن لا تقل عن 3 أحرف',
                'password.max'       => 'كلمة المرور يجب أن لا تزيد عن 20 أحرف',
                'password.confirmed' => 'كلمة المرور غير متطابقة',
                'token.required'     => 'الرمز الذي تم إرساله غير صحيح',
            ];
        } else {
            return [
                'email.required'     => 'Email is required',
                'email.email'        => 'Email is not valid',
                'password.required'  => 'Password is required',
                'password.min'       => 'Password must be at least 3 characters',
                'password.max'       => 'Password must be at most 20 characters',
                'password.confirmed' => 'Password confirmation does not match',
                'token.required'     => 'Token is required',
            ];
        }
    }
}
