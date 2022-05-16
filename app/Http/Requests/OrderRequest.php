<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_id'           => ['required', 'integer', 'exists:stores,id'],
            'items_id'           => ['required', 'array', 'exists:items,id'],
            'payment_method_id'  => ['required', 'integer', 'exists:payment_methods,id'],
            'shipping_method_id' => ['required', 'integer', 'exists:shipping_methods,id'],
            'quantity'           => ['required', 'array', 'min:1'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(){
        if($this->header('Accept-Language') == 'ar'){
            return [
                'store_id.required'           => 'من فضلك اختر المتجر',
                'store_id.integer'            => 'المتجر غير صحيح',
                'store_id.exists'             => 'المتجر غير موجود',
                'items_id.required'           => 'من فضلك اختر المنتج',
                'items_id.array'              => 'المنتج غير صحيح',
                'items_id.exists'             => 'المنتج غير موجود',
                'payment_method_id.required'  => 'من فضلك اختر طريقة الدفع',
                'payment_method_id.integer'   => 'طريقة الدفع غير صحيحة',
                'payment_method_id.exists'    => 'طريقة الدفع غير موجودة',
                'shipping_method_id.required' => 'من فضلك اختر طريقة الشحن',
                'shipping_method_id.integer'  => 'طريقة الشحن غير صحيحة',
                'shipping_method_id.exists'   => 'طريقة الشحن غير موجودة',
                'quantity.required'           => 'من فضلك ادخل الكمية',
                'quantity.array'              => 'الكمية غير صحيحة',
                'quantity.min'                => 'الكمية لا تقل عن 1',
            ];
        }else{
            return [
                'store_id.required'           => 'Please select store',
                'store_id.integer'            => 'Store is invalid',
                'store_id.exists'             => 'Store is not exists',
                'items_id.required'           => 'Please select item',
                'items_id.array'              => 'Item is invalid',
                'items_id.exists'             => 'Item is not exists',
                'payment_method_id.required'  => 'Please select payment method',
                'payment_method_id.integer'   => 'Payment method is invalid',
                'payment_method_id.exists'    => 'Payment method is not exists',
                'shipping_method_id.required' => 'Please select shipping method',
                'shipping_method_id.integer'  => 'Shipping method is invalid',
                'shipping_method_id.exists'   => 'Shipping method is not exists',
                'quantity.required'           => 'Please enter quantity',
                'quantity.array'              => 'Quantity is invalid',
                'quantity.min'                => 'Quantity must be greater than 1',
            ];
        }
    }
}
