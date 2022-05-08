<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FavouriteRequest extends FormRequest
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
            'item_id' => ['required', 'integer', 'exists:items,id' , 'unique:favourites,item_id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * @return array
     */
    public function messages()
    {
        if($this->header('Accept-Language') == 'ar'){
            return [
                'item_id.required' => 'يرجى اختيار منتج للإضافة إلى المفضلة',
                'item_id.integer'  => 'منتج غير صحيح',
                'item_id.exists'   => 'منتج غير موجود',
                'item_id.unique'   => 'تمت إضافة هذا المنتج إلى المفضلة من قبل',
            ];
        }else{
            return [
                'item_id.required' => 'Please select an item to favourite.',
                'item_id.integer'  => 'Invalid item selected.',
                'item_id.exists'   => 'Item does not exist.',
                'item_id.unique'   => 'This item is already favourited.'
            ];
        }

    }
}
