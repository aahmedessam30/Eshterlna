<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\BasicResource;
use Illuminate\Support\Facades\Gate;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except('index', 'show');
    }

    public function index()
    {
        return BrandResource::collection(Brand::whereOnline()->latest('id')->paginate(config('global.paginate')))
            ->additional(['status' => true]);
    }

    public function store(BrandRequest $request)
    {
        $brand = Brand::create($request->validated());
        return (new BrandResource($brand))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        $response = Gate::inspect('update', $brand);

        if ($response->allowed()) {

            $brand->update($request->validated());
            return (new BrandResource($brand))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(Brand $brand)
    {
        $response = Gate::inspect('delete', $brand);

        if ($response->allowed()) {

            $brand->delete();
            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
