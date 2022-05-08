<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ColorRequest extends FormRequest
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
                'name_ar'   => ['required', 'max:255', 'unique:colors,name_ar'],
                'name_en'   => ['required', 'max:255', 'unique:colors,name_en'],
                'colorHash' => ['required', 'max:255', 'unique:colors,colorHash'],
                'image'     => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'online'    => ['required', 'boolean'],
            ];
        }elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')){
            return [
                'name_ar'   => ['sometimes', 'max:255', 'unique:colors,name_ar,' . $this->route('color')->id],
                'name_en'   => ['sometimes', 'max:255', 'unique:colors,name_en,' . $this->route('color')->id],
                'colorHash' => ['sometimes', 'max:255', 'unique:colors,colorHash,' . $this->route('color')->id],
                'image'     => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'online'    => ['sometimes', 'boolean'],
            ];
        }
    }

    public function messages()
    {
        if($this->header('Accept-Language') == 'ar'){
            return [
              'name_ar.required'   => 'حقل الاسم باللغة العربية مطلوب',
              'name_ar.max'        => 'حقل الاسم باللغة العربية يجب ان لا يزيد عن 255 حرف',
              'name_ar.unique'     => 'هذا الاسم موجود بالفعل',
              'name_en.required'   => 'حقل الاسم باللغة الانجليزية مطلوب',
              'name_en.max'        => 'حقل الاسم باللغة الانجليزية يجب ان لا يزيد عن 255 حرف',
              'name_en.unique'     => 'هذا الاسم موجود بالفعل',
              'colorHash.required' => 'حقل كود اللون مطلوب',
              'colorHash.max'      => 'حقل كود اللون يجب ان لا يزيد عن 255 حرف',
              'colorHash.unique'   => 'كود اللون موجود بالفعل',
              'image.required'     => 'حقل الصورة مطلوب',
              'image.image'        => 'يجب ان يكون الملف صورة',
              'image.mimes'        => 'يجب ان يكون الملف صورة',
              'image.max'          => 'يجب ان لا يزيد الملف عن 2048 بايت',
              'online.required'    => 'حقل الحالة مطلوب',
              'online.boolean'     => 'يجب ان يكون الحقل بالفعل صحيح',
            ];
        }else{
            return [
                'name_ar.required'   => 'Arabic name field is required',
                'name_ar.max'        => 'Arabic name field must be less than 255 characters',
                'name_ar.unique'     => 'This name is already taken',
                'name_en.required'   => 'English name field is required',
                'name_en.max'        => 'English name field must be less than 255 characters',
                'name_en.unique'     => 'This name is already taken',
                'colorHash.required' => 'Color code field is required',
                'colorHash.max'      => 'Color code field must be less than 255 characters',
                'colorHash.unique'   => 'Color code is already taken',
                'image.required'     => 'Image field is required',
                'image.image'        => 'The file must be an image',
                'image.mimes'        => 'The file must be an image',
                'image.max'          => 'The file must be less than 2048 bytes',
                'online.required'    => 'Status field is required',
                'online.boolean'     => 'The field must be a valid value',
            ];
        }
    }
}
