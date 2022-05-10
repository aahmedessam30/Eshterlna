<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SettingRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            return [
                'currency_ar' => ['required', 'boolean'],
                'currency_en' => ['required', 'boolean'],
                'about_ar'    => ['required', 'boolean'],
                'about_en'    => ['required', 'boolean'],
                'terms_ar'    => ['required', 'boolean'],
                'terms_en'    => ['required', 'boolean'],
                'theme'       => ['required', 'boolean'],
                'image'       => ['required', 'boolean'],
                'color'       => ['required', 'boolean'],
                'size'        => ['required', 'boolean'],
                'store'       => ['required', 'boolean'],
                'delivery'    => ['required', 'boolean'],
                'payment'     => ['required', 'boolean'],
            ];
        } elseif ($this->method() == 'PUT' || $this->method() == 'PATCH') {
            return [
                'currency_ar' => ['sometimes', 'boolean'],
                'currency_en' => ['sometimes', 'boolean'],
                'about_ar'    => ['sometimes', 'boolean'],
                'about_en'    => ['sometimes', 'boolean'],
                'terms_ar'    => ['sometimes', 'boolean'],
                'terms_en'    => ['sometimes', 'boolean'],
                'theme'       => ['sometimes', 'boolean'],
                'image'       => ['sometimes', 'boolean'],
                'color'       => ['sometimes', 'boolean'],
                'size'        => ['sometimes', 'boolean'],
                'store'       => ['sometimes', 'boolean'],
                'delivery'    => ['sometimes', 'boolean'],
                'payment'     => ['sometimes', 'boolean'],
            ];
        }
    }

    public function messages()
    {
        if($this->header('Accept-Language') == 'ar'){
            return [
                'currency_ar.required' => 'يجب تحديد العملة المعتمدة للعربية',
                'currency_ar.boolean'  => 'يجب تحديد العملة المعتمدة للعربية',
                'currency_en.required' => 'يجب تحديد العملة المعتمدة للإنجليزية',
                'currency_en.boolean'  => 'يجب تحديد العملة المعتمدة للإنجليزية',
                'about_ar.required'    => 'يجب تحديد العنوان للعربية',
                'about_ar.boolean'     => 'يجب تحديد العنوان للعربية',
                'about_en.required'    => 'يجب تحديد العنوان للإنجليزية',
                'about_en.boolean'     => 'يجب تحديد العنوان للإنجليزية',
                'terms_ar.required'    => 'يجب تحديد الشروط للعربية',
                'terms_ar.boolean'     => 'يجب تحديد الشروط للعربية',
                'terms_en.required'    => 'يجب تحديد الشروط للإنجليزية',
                'terms_en.boolean'     => 'يجب تحديد الشروط للإنجليزية',
                'theme.required'       => 'يجب تحديد الثيم',
                'theme.boolean'        => 'يجب تحديد الثيم',
                'image.required'       => 'يجب تحديد الصورة',
                'image.boolean'        => 'يجب تحديد الصورة',
                'color.required'       => 'يجب تحديد اللون',
                'color.boolean'        => 'يجب تحديد اللون',
                'size.required'        => 'يجب تحديد المقاس',
                'size.boolean'         => 'يجب تحديد المقاس',
                'store.required'       => 'يجب تحديد المخزن',
                'store.boolean'        => 'يجب تحديد المخزن',
                'delivery.required'    => 'يجب تحديد التوصيل',
                'delivery.boolean'     => 'يجب تحديد التوصيل',
                'payment.required'     => 'يجب تحديد الدفع',
                'payment.boolean'      => 'يجب تحديد الدفع',
            ];
        }else{
            return [
                'currency_ar.required' => 'You must select the currency for Arabic',
                'currency_ar.boolean'  => 'You must select the currency for Arabic',
                'currency_en.required' => 'You must select the currency for English',
                'currency_en.boolean'  => 'You must select the currency for English',
                'about_ar.required'    => 'You must select the about for Arabic',
                'about_ar.boolean'     => 'You must select the about for Arabic',
                'about_en.required'    => 'You must select the about for English',
                'about_en.boolean'     => 'You must select the about for English',
                'terms_ar.required'    => 'You must select the terms for Arabic',
                'terms_ar.boolean'     => 'You must select the terms for Arabic',
                'terms_en.required'    => 'You must select the terms for English',
                'terms_en.boolean'     => 'You must select the terms for English',
                'theme.required'       => 'You must select the theme',
                'theme.boolean'        => 'You must select the theme',
                'image.required'       => 'You must select the image',
                'image.boolean'        => 'You must select the image',
                'color.required'       => 'You must select the color',
                'color.boolean'        => 'You must select the color',
                'size.required'        => 'You must select the size',
                'size.boolean'         => 'You must select the size',
                'store.required'       => 'You must select the store',
                'store.boolean'        => 'You must select the store',
                'delivery.required'    => 'You must select the delivery',
                'delivery.boolean'     => 'You must select the delivery',
                'payment.required'     => 'You must select the payment',
                'payment.boolean'      => 'You must select the payment',
            ];
        }
    }
}
