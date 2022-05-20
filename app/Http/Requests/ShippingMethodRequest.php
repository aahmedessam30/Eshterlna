<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ShippingMethodRequest extends FormRequest
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
                'name_ar'        => ['required', 'string', 'max:255' , Rule::unique('shipping_methods')
                    ->where(fn ($query) => $query->where('user_id', Auth::guard('api')->user()->id))],
                'name_en'        => ['required', 'string', 'max:255' , Rule::unique('shipping_methods')
                    ->where(fn ($query) => $query->where('user_id', Auth::guard('api')->user()->id))],
                'price'          => ['required', 'numeric'],
            ];
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'name_ar'        => ['sometimes', 'string', 'max:255' , Rule::unique('shipping_methods')
                    ->where(fn ($query)=> $query->where('user_id', Auth::guard('api')->user()->id))
                    ->ignore($this->route('shipping_method')->id)],
                'name_en'        => ['sometimes', 'string', 'max:255' , Rule::unique('shipping_methods')
                    ->where(fn ($query) => $query->where('user_id', Auth::guard('api')->user()->id))
                    ->ignore($this->route('shipping_method')->id)],
                'price'          => ['sometimes', 'numeric'],
            ];
        }
    }

    public function messages()
    {
        if ($this->header('Accept-Language') == 'ar'){
            return [
                'name_ar.required' => 'اسم الدفعة بالعربية مطلوب',
                'name_ar.string'   => 'اسم الدفعة بالعربية يجب ان يكون سلسلة',
                'name_ar.max'      => 'اسم الدفعة بالعربية يجب ان لا يزيد عن 255 حرف',
                'name_ar.unique'   => 'اسم الدفعة بالعربية موجود مسبقا',
                'name_en.required' => 'اسم الدفعة بالانجليزية مطلوب',
                'name_en.string'   => 'اسم الدفعة بالانجليزية يجب ان يكون سلسلة',
                'name_en.max'      => 'اسم الدفعة بالانجليزية يجب ان لا يزيد عن 255 حرف',
                'name_en.unique'   => 'اسم الدفعة بالانجليزية موجود مسبقا',
                'price.required'   => 'سعر الدفعة مطلوب',
                'price.numeric'    => 'سعر الدفعة يجب ان يكون رقم',
            ];
        }else{
            return [
                'name_ar.required' => 'Payment Method Name in Arabic is required',
                'name_ar.string'   => 'Payment Method Name in Arabic must be a string',
                'name_ar.max'      => 'Payment Method Name in Arabic must be less than 255 characters',
                'name_ar.unique'   => 'Payment Method Name in Arabic is already taken',
                'name_en.required' => 'Payment Method Name in English is required',
                'name_en.string'   => 'Payment Method Name in English must be a string',
                'name_en.max'      => 'Payment Method Name in English must be less than 255 characters',
                'name_en.unique'   => 'Payment Method Name in English is already taken',
                'price.required'   => 'Payment Method Price is required',
                'price.numeric'    => 'Payment Method Price must be a number',
            ];
        }
    }
}
