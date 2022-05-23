<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\BasicResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except('index', 'show');
    }

    public function index()
    {
        $brands = Auth::check() && Auth::user()->type == 'merchant'
            ? Brand::whereOnline()->auth()
            : Brand::whereOnline();

        return BrandResource::collection($brands->latest('id')->paginate(config('global.paginate')))
            ->additional(['status' => true]);
    }

    public function store(BrandRequest $request)
    {
        $brand = Brand::create($request->safe()->merge(['user_id' => auth()->id()])->all());

        // Send Notification For All Customers
        sendFireBaseNotification(User::customer()->get(), __('notification.new_brand', ['name' => $brand->name]));

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

            $brand->update($request->safe()->merge(['user_id' => auth()->id()])->all());

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.update_brand', ['name' => $brand->name]));

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

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.delete_brand', ['name' => $brand->name]));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
