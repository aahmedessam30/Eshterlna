<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ItemRequest extends FormRequest
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
                'name_ar'        => ['required', 'string', 'max:255', 'unique:items,name_ar'],
                'name_en'        => ['required', 'string', 'max:255', 'unique:items,name_en'],
                'description_ar' => ['required', 'string', 'max:255'],
                'description_en' => ['required', 'string', 'max:255'],
                'pay_price'      => ['required', 'numeric'],
                'sale_price'     => ['required', 'numeric'],
                'lowest_price'   => ['required', 'numeric'],
                'discount'       => ['numeric'],
                'code'           => ['required', 'string', 'max:255'],
                'online'         => ['required', 'boolean'],
                'category_id'    => ['required', 'integer'],
                'brand_id'       => ['required', 'integer'],
                'colors'         => ['array', 'exists:colors,id'],
                'sizes'          => ['array', 'exists:sizes,id'],
                'stores'         => ['required', 'array' , 'exists:stores,id'],
                'quantity'       => ['required','array']
            ];
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'name_ar'        => ['sometimes', 'string', 'max:255', 'unique:items,name_ar,' . $this->route('item')->id],
                'name_en'        => ['sometimes', 'string', 'max:255', 'unique:items,name_en,' . $this->route('item')->id],
                'description_ar' => ['sometimes', 'string', 'max:255'],
                'description_en' => ['sometimes', 'string', 'max:255'],
                'pay_price'      => ['sometimes', 'numeric'],
                'sale_price'     => ['sometimes', 'numeric'],
                'lowest_price'   => ['sometimes', 'numeric'],
                'discount'       => ['sometimes', 'numeric'],
                'code'           => ['sometimes', 'string', 'max:255'],
                'online'         => ['sometimes', 'boolean'],
                'category_id'    => ['sometimes', 'integer'],
                'brand_id'       => ['sometimes', 'integer'],
                'colors'         => ['sometimes', 'array' , 'exists:colors,id'],
                'sizes'          => ['sometimes', 'array' , 'exists:sizes,id'],
                'store_id'       => ['sometimes', 'integer' , 'exists:stores,id'],
                'quantity'       => ['sometimes', 'integer'],
            ];
        }
    }

    public function messages()
    {
        if ($this->header('Accept-Language') == 'ar') {
            return [
                'name_ar.required'        => 'اسم المنتج باللغة العربية مطلوب',
                'name_ar.max'             => 'اسم المنتج باللغة العربية يجب ان يكون اقل من 255 حرف',
                'name_ar.unique'          => 'اسم المنتج باللغة العربية موجود مسبقا',
                'name_en.required'        => 'اسم المنتج باللغة الانجليزية مطلوب',
                'name_en.max'             => 'اسم المنتج باللغة الانجليزية يجب ان يكون اقل من 255 حرف',
                'name_en.unique'          => 'اسم المنتج باللغة الانجليزية موجود مسبقا',
                'description_ar.required' => 'وصف المنتج باللغة العربية مطلوب',
                'description_ar.max'      => 'وصف المنتج باللغة العربية يجب ان يكون اقل من 255 حرف',
                'description_en.required' => 'وصف المنتج باللغة الانجليزية مطلوب',
                'description_en.max'      => 'وصف المنتج باللغة الانجليزية يجب ان يكون اقل من 255 حرف',
                'pay_price.required'      => 'سعر الشراء مطلوب',
                'pay_price.numeric'       => 'سعر الشراء يجب ان يكون رقم',
                'sale_price.required'     => 'سعر البيع مطلوب',
                'sale_price.numeric'      => 'سعر البيع يجب ان يكون رقم',
                'lowest_price.required'   => 'سعر الحد الادنى مطلوب',
                'lowest_price.numeric'    => 'سعر الحد الادنى يجب ان يكون رقم',
                'discount.required'       => 'الخصم مطلوب',
                'discount.numeric'        => 'الخصم يجب ان يكون رقم',
                'code.required'           => 'كود المنتج مطلوب',
                'code.max'                => 'كود المنتج يجب ان يكون اقل من 255 حرف',
                'online.required'         => 'حالة المنتج مطلوب',
                'category_id.required'    => 'القسم مطلوب',
                'category_id.integer'     => 'القسم يجب ان يكون رقم',
                'brand_id.required'       => 'العلامة التجارية مطلوبة',
                'brand_id.integer'        => 'العلامة التجارية يجب ان تكون رقم',
                'colors.array'            => 'الالوان يجب ان تكون في نوع مصفوفة',
                'colors.exists'           => 'الالوان غير موجودة',
                'sizes.array'             => 'المقاسات يجب ان تكون في نوع مصفوفة',
                'sizes.exists'            => 'المقاسات غير موجودة',
                'stores.required'         => 'المخازن مطلوبة',
                'stores.array'            => 'المخازن يجب ان تكون في نوع مصفوفة',
                'stores.exists'           => 'المخازن غير موجودة',
                'store_id.required'       => 'المخزن مطلوب',
                'store_id.integer'        => 'المخزن يجب ان يكون رقم',
                'store_id.exists'         => 'المخزن غير موجود',
                'quantity.required'       => 'الكمية مطلوبة',
                'quantity.array'          => 'يجب وضع كميات المنتجات فى المخازن',
                'quantity.integer'        => 'الكمية يجب ان تكون رقم',
            ];
        } else {
            return [
                'name_ar.required'        => 'Product name in Arabic is required',
                'name_ar.max'             => 'Product name in Arabic must be less than 255 characters',
                'name_en.required'        => 'Product name in English is required',
                'name_en.max'             => 'Product name in English must be less than 255 characters',
                'description_ar.required' => 'Product description in Arabic is required',
                'description_ar.max'      => 'Product description in Arabic must be less than 255 characters',
                'description_en.required' => 'Product description in English is required',
                'description_en.max'      => 'Product description in English must be less than 255 characters',
                'pay_price.required'      => 'Purchase price is required',
                'pay_price.numeric'       => 'Purchase price must be a number',
                'sale_price.required'     => 'Sale price is required',
                'sale_price.numeric'      => 'Sale price must be a number',
                'lowest_price.required'   => 'Lowest price is required',
                'lowest_price.numeric'    => 'Lowest price must be a number',
                'discount.required'       => 'Discount is required',
                'discount.numeric'        => 'Discount must be a number',
                'code.required'           => 'Product code is required',
                'code.max'                => 'Product code must be less than 255 characters',
                'online.required'         => 'Product status is required',
                'category_id.required'    => 'Category is required',
                'category_id.integer'     => 'Category must be a number',
                'brand_id.required'       => 'Brand is required',
                'brand_id.integer'        => 'Brand must be a number',
                'colors.array'            => 'Colors must be an array',
                'colors.exists'           => 'Colors not found',
                'sizes.array'             => 'Sizes must be an array',
                'sizes.exists'            => 'Sizes not found',
                'stores.required'         => 'Stores is required',
                'stores.array'            => 'Stores must be an array',
                'stores.exists'           => 'Stores not found',
                'store_id.required'       => 'Store is required',
                'store_id.integer'        => 'Store must be a number',
                'store_id.exists'         => 'Store not found',
                'quantity.required'       => 'Quantity is required',
                'quantity.integer'        => 'Quantity must be a number',
                'quantity.array'          => 'Quantity must be an array',
            ];
        }
    }
}
