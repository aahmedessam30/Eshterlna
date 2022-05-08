<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
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
        if ($this->isMethod('POST')) {
            return [
                'name_ar'    => ['required', 'string', 'max:255', 'unique:stores,name_ar'],
                'name_en'    => ['required', 'string', 'max:255', 'unique:stores,name_en'],
                'image'      => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'address_ar' => ['required', 'string', 'max:255'],
                'address_en' => ['required', 'string', 'max:255'],
                'phone'      => ['required', 'max:255'],
                'email'      => ['required', 'email', 'max:255', 'unique:stores,email'],
                'online'     => ['required', 'boolean'],
                'lat'        => ['required', 'string', 'max:255'],
                'lng'        => ['required', 'string', 'max:255'],
                'city_id'    => ['required', 'integer', 'exists:cities,id'],
            ];
        }elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')){
            return [
                'name_ar'    => ['sometimes', 'string', 'max:255', 'unique:stores,name_ar,'.$this->route('store')->id],
                'name_en'    => ['sometimes', 'string', 'max:255', 'unique:stores,name_en,'.$this->route('store')->id],
                'image'      => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'address_ar' => ['sometimes', 'string', 'max:255'],
                'address_en' => ['sometimes', 'string', 'max:255'],
                'phone'      => ['sometimes', 'string', 'max:255'],
                'email'      => ['sometimes', 'email', 'max:255', 'unique:stores,email,'.$this->route('store')->id],
                'online'     => ['sometimes', 'boolean'],
                'lat'        => ['sometimes', 'max:255'],
                'lng'        => ['sometimes', 'max:255'],
                'city_id'    => ['sometimes', 'integer', 'exists:cities,id'],
            ];
        }
    }

    public function messages()
    {
        if ($this->header('Accept-Language') == 'ar'){
            return [
                'name_ar.required'    => 'حقل الاسم باللغة العربية مطلوب.',
                'name_ar.string'      => 'حقل الاسم باللغة العربية يجب ان يكون نص.',
                'name_ar.max'         => 'حقل الاسم باللغة العربية يجب ان لا يزيد عن 255 حرف.',
                'name_ar.unique'      => 'هذا الاسم موجود بالفعل.',
                'name_en.required'    => 'حقل الاسم باللغة الانجليزية مطلوب.',
                'name_en.string'      => 'حقل الاسم باللغة الانجليزية يجب ان يكون نص.',
                'name_en.max'         => 'حقل الاسم باللغة الانجليزية يجب ان لا يزيد عن 255 حرف.',
                'name_en.unique'      => 'هذا الاسم موجود بالفعل.',
                'image.required'      => 'حقل الصورة مطلوب.',
                'image.image'         => 'حقل الصورة يجب ان يكون صورة.',
                'image.mimes'         => 'حقل الصورة يجب ان يكون صورة من نوع (jpeg,png,jpg,gif,svg).',
                'image.max'           => 'حقل الصورة يجب ان لا يزيد عن 2048 بايت.',
                'address_ar.required' => 'حقل العنوان باللغة العربية مطلوب.',
                'address_ar.string'   => 'حقل العنوان باللغة العربية يجب ان يكون نص.',
                'address_ar.max'      => 'حقل العنوان باللغة العربية يجب ان لا يزيد عن 255 حرف.',
                'address_en.required' => 'حقل العنوان باللغة الانجليزية مطلوب.',
                'address_en.string'   => 'حقل العنوان باللغة الانجليزية يجب ان يكون نص.',
                'address_en.max'      => 'حقل العنوان باللغة الانجليزية يجب ان لا يزيد عن 255 حرف.',
                'phone.required'      => 'حقل الهاتف مطلوب.',
                'phone.max'           => 'حقل الهاتف يجب ان لا يزيد عن 255 حرف.',
                'email.required'      => 'حقل البريد الالكتروني مطلوب.',
                'email.email'         => 'حقل البريد الالكتروني يجب ان يكون بريد الكتروني.',
                'email.max'           => 'حقل البريد الالكتروني يجب ان لا يزيد عن 255 حرف.',
                'email.unique'        => 'هذا البريد الالكتروني موجود بالفعل.',
                'online.required'     => 'حقل الحالة مطلوب.',
                'online.boolean'      => 'حقل الحالة يجب ان يكون بولاين او غير بولاين.',
                'lat.required'        => 'حقل الطول مطلوب.',
                'lat.max'             => 'حقل الطول يجب ان لا يزيد عن 255 حرف.',
                'lng.required'        => 'حقل العرض مطلوب.',
                'lng.max'             => 'حقل العرض يجب ان لا يزيد عن 255 حرف.',
                'city_id.required'    => 'المدينة مطلوبة',
                'city_id.exists'      => 'المدينة غير موجودة',
                'city_id.integer'     => 'المدينة يجب أن تكون رقم',
            ];
        }else{
            return [
                'name_ar.required'    => 'Arabic name field is required',
                'name_ar.max'         => 'Arabic name field must be less than 255 characters',
                'name_ar.unique'      => 'This name is already taken',
                'name_en.required'    => 'English name field is required',
                'name_en.max'         => 'English name field must be less than 255 characters',
                'name_en.unique'      => 'This name is already taken',
                'image.required'      => 'Image field is required',
                'image.image'         => 'The file must be an image',
                'image.mimes'         => 'The file must be an image',
                'image.max'           => 'The file must be less than 2048 bytes',
                'address_ar.required' => 'Arabic address field is required',
                'address_ar.string'   => 'Arabic address field must be a string',
                'address_ar.max'      => 'Arabic address field must be less than 255 characters',
                'address_en.required' => 'English address field is required',
                'address_en.string'   => 'English address field must be a string',
                'address_en.max'      => 'English address field must be less than 255 characters',
                'phone.required'      => 'Phone field is required',
                'phone.max'           => 'Phone number must be less than 20 characters',
                'email.required'      => 'Email field is required',
                'email.email'         => 'Email must be a valid email',
                'email.max'           => 'Email must be less than 150 characters',
                'email.unique'        => 'Email already exists',
                'phone.unique'        => 'Phone number already exists',
                'online.required'     => 'Online field is required',
                'lat.required'        => 'Latitude field is required',
                'lat.max'             => 'Latitude field must be less than 255 characters',
                'lng.required'        => 'Longitude field is required',
                'lng.max'             => 'Longitude field must be less than 255 characters',
                'city_id.required'    => 'City field is required',
                'city_id.exists'      => 'City not found',
                'city_id.integer'     => 'City must be an integer',
            ];
        }
    }
}
