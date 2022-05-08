<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends FormRequest
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
            'fname'      => ['sometimes', 'string', 'max:100'],
            'lname'      => ['sometimes', 'string', 'max:100'],
            'email'      => ['sometimes', 'string', 'email', 'max:150', 'unique:users,email'],
            'phone'      => ['sometimes', 'string', 'max:20', 'unique:users,phone'],
            'user_name'  => ['sometimes', 'string', 'max:150', 'unique:users,user_name'],
            'image'      => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'address'    => ['sometimes', 'string', 'max:255'],
            'city_id'    => ['sometimes', 'integer', 'exists:cities,id'],
            'country_id' => ['sometimes', 'integer', 'exists:countries,id'],
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
                'fname.max'         => 'الاسم الأول يجب أن لا يزيد عن 100 حرف',
                'lname.max'         => 'الاسم الأخير يجب أن لا يزيد عن 100 حرف',
                'email.email'       => 'البريد الإلكتروني يجب أن يكون بريد إلكتروني صحيح',
                'email.max'         => 'البريد الإلكتروني يجب أن لا يزيد عن 150 حرف',
                'email.unique'      => 'البريد الإلكتروني موجود مسبقا',
                'phone.max'         => 'رقم الهاتف يجب أن لا يزيد عن 20 حرف',
                'phone.unique'      => 'رقم الهاتف موجود مسبقا',
                'user_name.max'     => 'اسم المستخدم يجب أن لا يزيد عن 150 حرف',
                'user_name.unique'  => 'اسم المستخدم موجود مسبقا',
                'image.image'       => 'الصورة يجب أن تكون صورة',
                'image.mimes'       => 'الصورة يجب أن تكون بصيغة jpeg,png,jpg,gif,svg',
                'image.max'         => 'الصورة يجب أن لا يزيد عن 2048 بايت',
                'address.max'       => 'العنوان يجب أن لا يزيد عن 255 حرف',
                'city_id.exists'    => 'المدينة غير موجودة',
                'city_id.integer'   => 'المدينة يجب أن تكون رقم',
                'country_id.exists' => 'البلد غير موجود',
                'country_id.integer'=> 'البلد يجب أن تكون رقم',
            ];
        } else {
            return [
                'fname.max'        => 'First name must be less than 100 characters',
                'lname.max'        => 'Last name must be less than 100 characters',
                'email.email'      => 'Email must be a valid email',
                'email.max'        => 'Email must be less than 150 characters',
                'email.unique'     => 'Email already exists',
                'phone.max'        => 'Phone number must be less than 20 characters',
                'phone.unique'     => 'Phone number already exists',
                'user_name.max'    => 'User name must be less than 150 characters',
                'user_name.unique' => 'User name already exists',
                'image.image'      => 'Image must be an image',
                'image.mimes'      => 'Image must be a jpeg,png,jpg,gif,svg',
                'image.max'        => 'Image must be less than 2048 bytes',
                'address.max'      => 'Address must be less than 255 characters',
                'city_id.exists'   => 'City not found',
                'city_id.integer'  => 'City must be an integer',
                'country_id.exists'=> 'Country not found',
                'country_id.integer'=> 'Country must be an integer',
            ];
        }
    }
}
