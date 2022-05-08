<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SizeRequest extends FormRequest
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
                'name_ar' => ['required', 'string', 'max:255', 'unique:sizes,name_ar'],
                'name_en' => ['required', 'string', 'max:255', 'unique:sizes,name_en'],
                'image'   => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'online'  => ['required', 'boolean'],
            ];
        }elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'name_ar' => ['sometimes', 'string', 'max:255', 'unique:sizes,name_ar,'.$this->route('size')->id],
                'name_en' => ['sometimes', 'string', 'max:255', 'unique:sizes,name_en,'.$this->route('size')->id],
                'image'   => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'online'  => ['sometimes', 'boolean'],
            ];
        }
    }

    public function messages()
    {
        if($this->header('Accept-Language') == 'ar'){
            return [
                'name_ar.required' => 'اسم المقاس باللغة العربية مطلوب',
                'name_ar.string'   => 'اسم المقاس باللغة العربية يجب ان يكون حروف',
                'name_ar.max'      => 'اسم المقاس باللغة العربية يجب ان لا يزيد عن 255 حرف',
                'name_ar.unique'   => 'اسم المقاس باللغة العربية موجود مسبقا',
                'name_en.required' => 'اسم المقاس باللغة الانجليزية مطلوب',
                'name_en.string'   => 'اسم المقاس باللغة الانجليزية يجب ان يكون حروف',
                'name_en.max'      => 'اسم المقاس باللغة الانجليزية يجب ان لا يزيد عن 255 حرف',
                'name_en.unique'   => 'اسم المقاس باللغة الانجليزية موجود مسبقا',
                'image.required'   => 'صورة المقاس مطلوبة',
                'image.image'      => 'صورة المقاس يجب ان تكون صورة',
                'image.mimes'      => 'صورة المقاس يجب ان تكون صورة',
                'image.max'        => 'صورة المقاس يجب ان لا تكون اكبر من 2 ميجا بايت',
                'online.required'  => 'حالة المقاس مطلوبة',
            ];
        }else{
            return [
                'name_ar.required' => 'Size name in arabic is required',
                'name_ar.string'   => 'Size name in arabic must be string',
                'name_ar.max'      => 'Size name in arabic must be less than 255 characters',
                'name_ar.unique'   => 'Size name in arabic is already taken',
                'name_en.required' => 'Size name in english is required',
                'name_en.string'   => 'Size name in english must be string',
                'name_en.max'      => 'Size name in english must be less than 255 characters',
                'name_en.unique'   => 'Size name in english is already taken',
                'image.required'   => 'Size image is required',
                'image.image'      => 'Size image must be an image',
                'image.mimes'      => 'Size image must be an image',
                'image.max'        => 'Size image must be less than 2MB',
                'online.required'  => 'Size status is required',
            ];
        }
    }
}
