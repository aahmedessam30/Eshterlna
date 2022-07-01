<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ItemResource;

class HomeController extends Controller
{
    /**
     * This Method is used to get the home page that includes the latest brands and categories.
     *
     * @return Resource
     */
    public function index()
    {
        return CategoryResource::collection(Category::withOnly('items')->whereOnline()->latest()->get())
            ->additional(['status' => true]);
    }

    /**
     * This Method is used to search in all applications.
     *
     * @param  Request $request
     * @return Resource
     */
    public function search(Request $request)
    {
        $items  = Item::whereOnline()
            ->where('brand_id', 'like', '%' . $request->search . '%')
            ->orWhere('category_id', 'like', '%' . $request->search . '%')
            ->orWhere('name_ar', 'like', '%' . $request->search . '%')
            ->orWhere('name_en', 'like', '%' . $request->search . '%')
            ->orWhere('description_ar', 'like', '%' . $request->search . '%')
            ->orWhere('description_en', 'like', '%' . $request->search . '%')
            ->orWhere('code', 'like', '%' . $request->search . '%')
            ->get();

        return count($items) > 0
            ? ItemResource::collection($items)->additional(['status' => true])
            : (new BasicResource(false, __('messages.items_notFound'), 'message'));
    }
}
