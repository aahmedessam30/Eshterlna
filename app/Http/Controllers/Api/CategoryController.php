<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BasicResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Helpers;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except('index', 'show');
    }

    public function index()
    {
        return CategoryResource::collection(Category::whereOnline()->latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return (new CategoryResource($category))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Category $category)
    {
        return (new CategoryResource($category))->additional(['status' => true]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $response = Gate::inspect('update', $category);

        if ($response->allowed()) {

            $category->update($request->validated());
            return (new CategoryResource($category))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(Category $category)
    {
        $response = Gate::inspect('delete', $category);

        if ($response->allowed()) {

            $category->delete();
            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}