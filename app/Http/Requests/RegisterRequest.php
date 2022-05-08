<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'fname'      => ['required', 'string', 'max:100'],
            'lname'      => ['required', 'string', 'max:100'],
            'email'      => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'phone'      => ['required', 'string', 'max:20', 'unique:users,phone'],
            'username'   => ['required', 'string', 'max:150', 'unique:users,username'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'device_key' => ['required', 'string'],
            'type'       => ['string', 'max:10' ,'in:merchant,customer'],
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
                'fname.required'      => 'الاسم الأول مطلوب',
                'fname.max'           => 'الاسم الأول يجب أن لا يزيد عن 100 حرف',
                'lname.required'      => 'الاسم الأخير مطلوب',
                'lname.max'           => 'الاسم الأخير يجب أن لا يزيد عن 100 حرف',
                'email.required'      => 'البريد الإلكتروني مطلوب',
                'email.email'         => 'البريد الإلكتروني يجب أن يكون بنود الحروف',
                'email.max'           => 'البريد الإلكتروني يجب أن لا يزيد عن 150 حرف',
                'email.unique'        => 'البريد الإلكتروني موجود بالفعل',
                'phone.required'      => 'رقم الهاتف مطلوب',
                'phone.max'           => 'رقم الهاتف يجب أن لا يزيد عن 20 حرف',
                'phone.unique'        => 'رقم الهاتف موجود بالفعل',
                'username.required'   => 'اسم المستخدم مطلوب',
                'username.max'        => 'اسم المستخدم يجب أن لا يزيد عن 150 حرف',
                'username.unique'     => 'اسم المستخدم موجود بالفعل',
                'password.required'   => 'كلمة المرور مطلوبة',
                'password.min'        => 'كلمة المرور يجب أن لا يقل عن 8 حرف',
                'password.confirmed'  => 'كلمة المرور غير متطابقة',
                'device_key.required' => 'معرف الجهاز مطلوب',
                'type.max'            => 'نوع المستخدم يجب أن لا يزيد عن 10 حرف',
                'type.in'             => 'نوع المستخدم غير صحيح',
            ];
        } else {
            return [
                'fname.required'      => 'First Name is required',
                'fname.max'           => 'First Name must be less than 100 characters',
                'lname.required'      => 'Last Name is required',
                'lname.max'           => 'Last Name must be less than 100 characters',
                'email.required'      => 'Email is required',
                'email.email'         => 'Email must be valid',
                'email.max'           => 'Email must be less than 150 characters',
                'email.unique'        => 'Email is already taken',
                'phone.required'      => 'Phone is required',
                'phone.max'           => 'Phone must be less than 20 characters',
                'phone.unique'        => 'Phone is already taken',
                'username.required'   => 'User Name is required',
                'username.max'        => 'User Name must be less than 150 characters',
                'username.unique'     => 'User Name is already taken',
                'password.required'   => 'Password is required',
                'password.min'        => 'Password must be at least 8 characters',
                'password.confirmed'  => 'Password confirmation does not match',
                'device_key.required' => 'Device Key is required',
                'type.max'            => 'User Type must be less than 10 characters',
                'type.in'             => 'User Type is invalid',
            ];
        }
    }
}
