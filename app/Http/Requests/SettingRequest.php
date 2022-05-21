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
                'currency_ar' => ['required', 'string', 'max:255'],
                'currency_en' => ['required', 'string', 'max:255'],
                'about_ar'    => ['required', 'string', 'max:255'],
                'about_en'    => ['required', 'string', 'max:255'],
                'terms_ar'    => ['required', 'string', 'max:255'],
                'terms_en'    => ['required', 'string', 'max:255'],
                'theme'       => ['required', 'string', 'max:255'],
                'image'       => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'color'       => ['required', 'boolean'],
                'size'        => ['required', 'boolean'],
                'store'       => ['required', 'boolean'],
                'delivery'    => ['required', 'boolean'],
                'payment'     => ['required', 'boolean'],
            ];
        } elseif ($this->method() == 'PUT' || $this->method() == 'PATCH') {
            return [
                'currency_ar' => ['sometimes', 'string', 'max:255'],
                'currency_en' => ['sometimes', 'string', 'max:255'],
                'about_ar'    => ['sometimes', 'string', 'max:255'],
                'about_en'    => ['sometimes', 'string', 'max:255'],
                'terms_ar'    => ['sometimes', 'string', 'max:255'],
                'terms_en'    => ['sometimes', 'string', 'max:255'],
                'theme'       => ['sometimes', 'string', 'max:255'],
                'image'       => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
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
        if ($this->header('Accept-Language') == 'ar') {
            return [
                'currency_ar.required' => 'يجب تحديد العملة المعتمدة للعربية',
                'currency_ar.string'  => 'يجب أن يكون العملة المعتمدة للعربية سلسلة حروف',
                'currency_ar.max'     => 'يجب أن لا يتجاوز العملة المعتمدة للعربية 255 حرف',
                'currency_en.required' => 'يجب تحديد العملة المعتمدة للإنجليزية',
                'currency_en.string'  => 'يجب أن يكون العملة المعتمدة للإنجليزية سلسلة حروف',
                'currency_en.max'     => 'يجب أن لا يتجاوز العملة المعتمدة للإنجليزية 255 حرف',
                'about_ar.required'    => 'يجب تحديد العنوان للعربية',
                'about_ar.string'     => 'يجب أن يكون العنوان للعربية سلسلة حروف',
                'about_ar.max'        => 'يجب أن لا يتجاوز العنوان للعربية 255 حرف',
                'about_en.required'    => 'يجب تحديد العنوان للإنجليزية',
                'about_en.string'     => 'يجب أن يكون العنوان للإنجليزية سلسلة حروف',
                'about_en.max'        => 'يجب أن لا يتجاوز العنوان للإنجليزية 255 حرف',
                'terms_ar.required'    => 'يجب تحديد الشروط للعربية',
                'terms_ar.string'     => 'يجب أن يكون الشروط للعربية سلسلة حروف',
                'terms_ar.max'        => 'يجب أن لا يتجاوز الشروط للعربية 255 حرف',
                'terms_en.required'    => 'يجب تحديد الشروط للإنجليزية',
                'terms_en.string'     => 'يجب أن يكون الشروط للإنجليزية سلسلة حروف',
                'terms_en.max'        => 'يجب أن لا يتجاوز الشروط للإنجليزية 255 حرف',
                'theme.required'       => 'يجب تحديد الثيم',
                'theme.string'        => 'يجب أن يكون الثيم سلسلة حروف',
                'theme.max'           => 'يجب أن لا يتجاوز الثيم 255 حرف',
                'image.required'       => 'يجب تحديد الصورة',
                'image.image'         => 'يجب أن يكون الصورة صورة',
                'image.mimes'         => 'يجب أن يكون الصورة صورة من نوع jpeg,png,jpg,gif,svg',
                'image.max'           => 'يجب أن لا يتجاوز الصورة 2048 بايت',
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
        } else {
            return [
                'currency_ar.required' => 'You must select the currency for Arabic',
                'currency_ar.string'   => 'The currency for Arabic must be a string',
                'currency_ar.max'      => 'The currency for Arabic must not exceed 255 characters',
                'currency_en.required' => 'You must select the currency for English',
                'currency_en.string'   => 'The currency for English must be a string',
                'currency_en.max'      => 'The currency for English must not exceed 255 characters',
                'about_ar.required'    => 'You must select the about for Arabic',
                'about_ar.string'      => 'The about for Arabic must be a string',
                'about_ar.max'         => 'The about for Arabic must not exceed 255 characters',
                'about_en.required'    => 'You must select the about for English',
                'about_en.string'      => 'The about for English must be a string',
                'about_en.max'         => 'The about for English must not exceed 255 characters',
                'terms_ar.required'    => 'You must select the terms for Arabic',
                'terms_ar.string'      => 'The terms for Arabic must be a string',
                'terms_ar.max'         => 'The terms for Arabic must not exceed 255 characters',
                'terms_en.required'    => 'You must select the terms for English',
                'terms_en.string'      => 'The terms for English must be a string',
                'terms_en.max'         => 'The terms for English must not exceed 255 characters',
                'theme.required'       => 'You must select the theme',
                'theme.string'         => 'The theme must be a string',
                'theme.max'            => 'The theme must not exceed 255 characters',
                'image.required'       => 'You must select the image',
                'image.image'          => 'The image must be an image',
                'image.mimes'          => 'The image must be an image of type jpeg,png,jpg,gif,svg',
                'image.max'            => 'The image must not exceed 2048 bytes',
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
