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
                'about_ar' => ['required', 'boolean'],
                'about_en' => ['required', 'boolean'],
                'terms_ar' => ['required', 'boolean'],
                'terms_en' => ['required', 'boolean'],
                'theme' => ['required', 'boolean'],
                'image' => ['required', 'boolean'],
                'color' => ['required', 'boolean'],
                'size' => ['required', 'boolean'],
                'store' => ['required', 'boolean'],
                'delivery' => ['required', 'boolean'],
                'payment' => ['required', 'boolean'],
            ];
        } elseif ($this->method() == 'PUT' || $this->method() == 'PATCH') {
            return [
                'currency_ar' => ['sometimes', 'boolean'],
                'currency_en' => ['sometimes', 'boolean'],
                'about_ar' => ['sometimes', 'boolean'],
                'about_en' => ['sometimes', 'boolean'],
                'terms_ar' => ['sometimes', 'boolean'],
                'terms_en' => ['sometimes', 'boolean'],
                'theme' => ['sometimes', 'boolean'],
                'image' => ['sometimes', 'boolean'],
                'color' => ['sometimes', 'boolean'],
                'size' => ['sometimes', 'boolean'],
                'store' => ['sometimes', 'boolean'],
                'delivery' => ['sometimes', 'boolean'],
                'payment' => ['sometimes', 'boolean'],
            ];
        }
    }

    public function messages()
    {
        if($this->header('Accept-Language') == 'ar'){
            return [

            ];
        }
    }
}
