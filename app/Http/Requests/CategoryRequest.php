<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class CategoryRequest extends FormRequest
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
                'name_ar'        => ['required', 'string', 'max:255', 'unique:categories,name_ar'],
                'name_en'        => ['required', 'string', 'max:255', 'unique:categories,name_en'],
                'description_ar' => ['required', 'string', 'unique:categories,description_ar'],
                'description_en' => ['required', 'string', 'unique:categories,description_en'],
                'image'          => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'online'         => ['required', 'boolean'],
                'category_id'    => ['required', 'integer'],
            ];
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'name_ar'        => ['sometimes', 'string', 'max:255', 'unique:categories,name_ar,' . $this->route('category')->id],
                'name_en'        => ['sometimes', 'string', 'max:255', 'unique:categories,name_en,' . $this->route('category')->id],
                'description_ar' => ['sometimes', 'string', 'unique:categories,description_ar'],
                'description_en' => ['sometimes', 'string', 'unique:categories,description_en'],
                'image'          => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'online'         => ['sometimes', 'boolean'],
                'category_id'    => ['sometimes', 'integer'],
            ];
        }
    }

    public function messages()
    {
        if ($this->header('Accept-Language') == 'ar') {
            return [
                'name_ar.required'        => 'اسم القسم باللغة العربية مطلوب',
                'name_ar.string'          => 'اسم القسم باللغة العربية يجب ان يكون حروف',
                'name_ar.max'             => 'اسم القسم باللغة العربية يجب ان لا يزيد عن 255 حرف',
                'name_ar.unique'          => 'اسم القسم باللغة العربية موجود مسبقا',
                'name_en.required'        => 'اسم القسم باللغة الانجليزية مطلوب',
                'name_en.string'          => 'اسم القسم باللغة الانجليزية يجب ان يكون حروف',
                'name_en.max'             => 'اسم القسم باللغة الانجليزية يجب ان لا يزيد عن 255 حرف',
                'name_en.unique'          => 'اسم القسم باللغة الانجليزية موجود مسبقا',
                'description_ar.required' => 'وصف القسم باللغة العربية مطلوب',
                'description_ar.string'   => 'وصف القسم باللغة العربية يجب ان يكون حروف',
                'description_ar.unique'   => 'وصف القسم باللغة العربية موجود مسبقا',
                'description_en.required' => 'وصف القسم باللغة الانجليزية مطلوب',
                'description_en.string'   => 'وصف القسم باللغة الانجليزية يجب ان يكون حروف',
                'description_en.unique'   => 'وصف القسم باللغة الانجليزية موجود مسبقا',
                'image.required'          => 'صورة القسم مطلوبة',
                'image.image'             => 'صورة القسم يجب ان تكون صورة',
                'image.mimes'             => 'صورة القسم يجب ان تكون بصيغة jpeg,png,jpg,gif,svg',
                'image.max'               => 'صورة القسم يجب ان لا تزيد عن 2048 بايت',
                'online.required'         => 'حالة القسم مطلوبة',
                'category_id.required'    => 'القسم مطلوب',
                'category_id.integer'     => 'القسم يجب ان يكون رقم',
            ];
        } else {
            return [
                'name_en.required'        => 'Category name in English is required',
                'name_en.string'          => 'Category name in English must be string',
                'name_en.max'             => 'Category name in English must be less than 255 characters',
                'name_ar.required'        => 'Category name in Arabic is required',
                'name_ar.string'          => 'Category name in Arabic must be string',
                'name_ar.max'             => 'Category name in Arabic must be less than 255 characters',
                'description_en.required' => 'Category description in English is required',
                'description_en.string'   => 'Category description in English must be string',
                'description_ar.required' => 'Category description in Arabic is required',
                'description_ar.string'   => 'Category description in Arabic must be string',
                'image.required'          => 'Category image is required',
                'image.image'             => 'Category image must be image',
                'image.mimes'             => 'Category image must be jpeg,png,jpg,gif,svg',
                'image.max'               => 'Category image must be less than 2048 kilobytes',
                'online.required'         => 'Category status is required',
                'category_id.required'    => 'Category is required',
                'category_id.integer'     => 'Category must be integer',
            ];
        }
    }
}
