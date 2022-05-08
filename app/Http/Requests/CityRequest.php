<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CityRequest extends FormRequest
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
        if($this->isMethod('POST')){
            return [
                'name_ar'    => ['required', 'string', 'max:255' , 'unique:cities,name_ar'],
                'name_en'    => ['required', 'string', 'max:255' , 'unique:cities,name_en'],
                'zip_code'   => ['required', 'string', 'max:255' , 'unique:cities,zip_code'],
                'country_id' => ['required', 'integer' , 'exists:countries,id'],
            ];
        }elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')){
            return [
                'name_ar'    => ['sometimes', 'string', 'max:255' , 'unique:cities,name_ar,'.$this->route('city')->id],
                'name_en'    => ['sometimes', 'string', 'max:255' , 'unique:cities,name_en,'.$this->route('city')->id],
                'zip_code'   => ['sometimes', 'string', 'max:255' , 'unique:cities,zip_code,'.$this->route('city')->id],
                'country_id' => ['sometimes', 'integer' , 'exists:countries,id'],
            ];
        }
    }

    public function messages()
    {
        if($this->header('Accept-Language') == 'ar'){
            return [
                'name_ar.required'    => 'الاسم بالعربية مطلوب',
                'name_ar.string'      => 'الاسم بالعربية يجب ان يكون حروف',
                'name_ar.max'         => 'الاسم بالعربية يجب ان لا يزيد عن 255 حرف',
                'name_ar.unique'      => 'الاسم بالعربية موجود مسبقا',
                'name_en.required'    => 'الاسم بالانجليزية مطلوب',
                'name_en.string'      => 'الاسم بالانجليزية يجب ان يكون حروف',
                'name_en.max'         => 'الاسم بالانجليزية يجب ان لا يزيد عن 255 حرف',
                'name_en.unique'      => 'الاسم بالانجليزية موجود مسبقا',
                'zip_code.required'   => 'الرمز البريدي مطلوب',
                'zip_code.string'     => 'الرمز البريدي يجب ان يكون حروف',
                'zip_code.max'        => 'الرمز البريدي يجب ان لا يزيد عن 255 حرف',
                'zip_code.unique'     => 'الرمز البريدي موجود مسبقا',
                'country_id.required' => 'الدولة مطلوبة',
                'country_id.integer'  => 'الدولة يجب ان تكون رقم',
                'country_id.exists'   => 'الدولة غير موجودة',
            ];
        }else{
            return [
                'name_ar.required'    => 'name in arabic is required',
                'name_ar.string'      => 'name in arabic must be string',
                'name_ar.max'         => 'name in arabic must be less than 255 characters',
                'name_ar.unique'      => 'name in arabic is already taken',
                'name_en.required'    => 'name in english is required',
                'name_en.string'      => 'name in english must be string',
                'name_en.max'         => 'name in english must be less than 255 characters',
                'name_en.unique'      => 'name in english is already taken',
                'zip_code.required'   => 'zip code is required',
                'zip_code.string'     => 'zip code must be string',
                'zip_code.max'        => 'zip code must be less than 255 characters',
                'zip_code.unique'     => 'zip code is already taken',
                'country_id.required' => 'country is required',
                'country_id.integer'  => 'country must be an integer',
                'country_id.exists'   => 'country not found',
            ];
        }
    }
}
