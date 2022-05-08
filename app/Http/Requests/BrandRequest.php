<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api')->check() && Auth::guard('api')->user()->type == 'merchant';
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
                'name_ar'        => ['required', 'string', 'max:255', 'unique:brands,name_ar'],
                'name_en'        => ['required', 'string', 'max:255', 'unique:brands,name_en'],
                'image'          => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'description_ar' => ['required', 'string', 'max:255'],
                'description_en' => ['required', 'string', 'max:255'],
                'online'         => ['required', 'boolean'],
                'code'           => ['required', 'string', 'max:255'],
            ];
        }
        elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'name_ar'        => ['sometimes', 'string', 'max:255', 'unique:brands,name_ar,' . $this->route('brand')->id],
                'name_en'        => ['sometimes', 'string', 'max:255', 'unique:brands,name_en,' . $this->route('brand')->id],
                'image'          => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'description_ar' => ['sometimes', 'string', 'max:255'],
                'description_en' => ['sometimes', 'string', 'max:255'],
                'online'         => ['sometimes', 'boolean'],
                'code'           => ['sometimes', 'string', 'max:255'],
            ];
        }
    }

    public function messages()
    {
        if ($this->header('Accept-Language') == 'ar') {
            return [
                'name_ar.required'        => 'حقل الاسم باللغة العربية مطلوب',
                'name_ar.string'          => 'حقل الاسم باللغة العربية يجب ان يكون نص',
                'name_ar.max'             => 'حقل الاسم باللغة العربية يجب ان لا يزيد عن 255 حرف',
                'name_ar.unique'          => 'الاسم باللغة العربية موجود مسبقا',
                'name_en.required'        => 'حقل الاسم باللغة الانجليزية مطلوب',
                'name_en.string'          => 'حقل الاسم باللغة الانجليزية يجب ان يكون نص',
                'name_en.max'             => 'حقل الاسم باللغة الانجليزية يجب ان لا يزيد عن 255 حرف',
                'name_en.unique'          => 'الاسم باللغة الانجليزية موجود مسبقا',
                'image.required'          => 'حقل الصورة مطلوب',
                'image.image'             => 'حقل الصورة يجب ان يكون صورة',
                'image.mimes'             => 'حقل الصورة يجب ان يكون صورة من نوع (jpeg,png,jpg,gif,svg)',
                'image.max'               => 'حقل الصورة يجب ان لا يزيد عن 2048 بايت',
                'description_ar.required' => 'حقل الوصف باللغة العربية مطلوب',
                'description_ar.string'   => 'حقل الوصف باللغة العربية يجب ان يكون نص',
                'description_ar.max'      => 'حقل الوصف باللغة العربية يجب ان لا يزيد عن 255 حرف',
                'description_en.required' => 'حقل الوصف باللغة الانجليزية مطلوب',
                'description_en.string'   => 'حقل الوصف باللغة الانجليزية يجب ان يكون نص',
                'description_en.max'      => 'حقل الوصف باللغة الانجليزية يجب ان لا يزيد عن 255 حرف',
                'online.required'         => 'حقل الحالة مطلوب',
                'online.boolean'          => 'حقل الحالة يجب ان يكون مفعل او غير مفعل',
                'code.required'           => 'حقل الكود مطلوب',
                'code.string'             => 'حقل الكود يجب ان يكون نص',
                'code.max'                => 'حقل الكود يجب ان لا يزيد عن 255 حرف',
            ];
        } else {
            return [
                'name_ar.required'        => 'Brand name in Arabic is required',
                'name_ar.string'          => 'Brand name in Arabic must be a string',
                'name_ar.max'             => 'Brand name in Arabic must be less than 255 characters',
                'name_ar.unique'          => 'Brand name in Arabic already exists',
                'name_en.required'        => 'Brand name in English is required',
                'name_en.string'          => 'Brand name in English must be a string',
                'name_en.max'             => 'Brand name in English must be less than 255 characters',
                'name_en.unique'          => 'Brand name in English already exists',
                'image.required'          => 'Brand image is required',
                'image.image'             => 'Brand image must be an image',
                'image.mimes'             => 'Brand image must be an image of type (jpeg,png,jpg,gif,svg)',
                'image.max'               => 'Brand image must be less than 2048 bytes',
                'description_ar.required' => 'Brand description in Arabic is required',
                'description_ar.string'   => 'Brand description in Arabic must be a string',
                'description_ar.max'      => 'Brand description in Arabic must be less than 255 characters',
                'description_en.required' => 'Brand description in English is required',
                'description_en.string'   => 'Brand description in English must be a string',
                'description_en.max'      => 'Brand description in English must be less than 255 characters',
                'online.required'         => 'Brand status is required',
                'online.boolean'          => 'Brand status must be online or offline',
                'code.required'           => 'Brand code is required',
                'code.string'             => 'Brand code must be a string',
                'code.max'                => 'Brand code must be less than 255 characters',
            ];
        }
    }
}
