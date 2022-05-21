<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->isMethod('GET')){
            return [
                'item_id' => ['required', 'integer', 'exists:items,id'],
            ];
        }elseif($this->method() == 'POST'){
            return [
                'item_id' => ['required', 'integer', 'exists:items,id'],
                'review'  => ['required', 'string', 'max:255'],
                'rating'  => ['required', 'integer', 'between:1,5'],
            ];
        }elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')){
            return [
                'item_id' => ['required', 'integer', 'exists:items,id'],
                'review'  => ['sometimes', 'string', 'max:255'],
                'rating'  => ['sometimes', 'integer', 'between:1,5'],
            ];
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     * @return array
     */
    public function messages(){
        if ($this->header('Accept-Language') == 'ar') {
            return [
                'item_id.required' => 'يرجى اختيار المنتج',
                'item_id.integer'  => 'المنتج غير صحيح',
                'item_id.exists'   => 'المنتج غير موجود',
                'review.required'  => 'يرجى ادخال التعليق',
                'review.string'    => 'التعليق غير صحيح',
                'review.max'       => 'التعليق يجب ان لا يتعدى 255 حرف',
                'rating.required'  => 'يرجى ادخال التقييم',
                'rating.integer'   => 'التقييم غير صحيح',
                'rating.between'   => 'التقييم يجب ان يكون بين 1 و 5',
            ];
        }else{
            return [
                'item_id.required' => 'Please select the item',
                'item_id.integer'  => 'The item is not valid',
                'item_id.exists'   => 'The item is not exists',
                'review.required'  => 'Please enter the review',
                'review.string'    => 'The review is not valid',
                'review.max'       => 'The review must not exceed 255 characters',
                'rating.required'  => 'Please enter the rating',
                'rating.integer'   => 'The rating is not valid',
                'rating.between'   => 'The rating must be between 1 and 5',
            ];
        }
    }
}
