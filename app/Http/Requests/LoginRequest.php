<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username'   => ['required', 'string', 'exists:users,username'],
            'password'   => ['required', 'string', 'min:8'],
            'device_key' => ['required', 'string', 'exists:users,device_key'],
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
                'username.required'   => 'اسم المستخدم مطلوب',
                'username.exists'     => 'اسم المستخدم غير موجود',
                'password.required'   => 'كلمة المرور مطلوبة',
                'password.min'        => 'كلمة المرور يجب ان تكون على الاقل 8 حروف',
                'device_key.required' => 'معرف الجهاز مطلوب',
                'device_key.exists'   => 'معرف الجهاز غير موجود',
            ];
        } else {
            return [
                'username.required'   => 'User name is required',
                'username.exists'     => 'User name is not exists',
                'password.required'   => 'Password is required',
                'password.min'        => 'Password must be at least 8 characters',
                'device_key.required' => 'Device key is required',
                'device_key.exists'   => 'Device key is not exists',
            ];
        }
    }
}
