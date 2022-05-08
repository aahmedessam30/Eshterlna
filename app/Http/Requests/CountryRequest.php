<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CountryRequest extends FormRequest
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
                'name_ar'     => ['required', 'string', 'max:255', 'unique:countries,name_ar'],
                'name_en'     => ['required', 'string', 'max:255', 'unique:countries,name_en'],
                'zip_code'    => ['required', 'string', 'max:255', 'unique:countries,zip_code'],
            ];
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'name_ar'     => ['sometimes', 'string', 'max:255', 'unique:countries,name_ar,' . $this->country->id],
                'name_en'     => ['sometimes', 'string', 'max:255', 'unique:countries,name_en,' . $this->country->id],
                'zip_code'    => ['sometimes', 'max:255', 'unique:countries,zip_code,' . $this->country->id],
            ];
        }
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
                'name_ar.required'     => 'حقل الاسم باللغة العربية مطلوب.',
                'name_ar.string'       => 'حقل الاسم باللغة العربية يجب ان يكون نص.',
                'name_ar.max'          => 'حقل الاسم باللغة العربية يجب ان لا يزيد عن 255 حرف.',
                'name_ar.unique'       => 'الاسم باللغة العربية موجود مسبقا.',
                'name_en.required'     => 'حقل الاسم باللغة الانجليزية مطلوب.',
                'name_en.string'       => 'حقل الاسم باللغة الانجليزية يجب ان يكون نص.',
                'name_en.max'          => 'حقل الاسم باللغة الانجليزية يجب ان لا يزيد عن 255 حرف.',
                'name_en.unique'       => 'الاسم باللغة الانجليزية موجود مسبقا.',
                'zip_code.required'    => 'حقل الرقم البريدي مطلوب.',
                'zip_code.max'         => 'حقل الرقم البريدي يجب ان لا يزيد عن 255 حرف.',
                'zip_code.unique'      => 'الرقم البريدي موجود مسبقا.',
            ];
        }else{
            return [
                'name_ar.required'     => 'The name in arabic is required.',
                'name_ar.string'       => 'The name in arabic must be a string.',
                'name_ar.max'          => 'The name in arabic must be less than 255 characters.',
                'name_ar.unique'       => 'The name in arabic is already taken.',
                'name_en.required'     => 'The name in english is required.',
                'name_en.string'       => 'The name in english must be a string.',
                'name_en.max'          => 'The name in english must be less than 255 characters.',
                'name_en.unique'       => 'The name in english is already taken.',
                'zip_code.max'         => 'The zip code must be less than 255 characters.',
                'zip_code.unique'      => 'The zip code is already taken.',
            ];
        }
    }
}
