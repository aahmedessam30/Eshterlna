<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingMethodRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ShippingMethodResource;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ShippingMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except(['index', 'show']);
    }

    public function index()
    {
        return ShippingMethodResource::collection(ShippingMethod::latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(ShippingMethodRequest $request)
    {
        $shippingMethod = ShippingMethod::create($request->safe()->merge(['user_id' => Auth::id()])->all());

        return (new ShippingMethodResource($shippingMethod))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(ShippingMethod $shippingMethod)
    {
        return (new ShippingMethodResource($shippingMethod))->additional(['status' => true]);
    }

    public function update(ShippingMethodRequest $request, ShippingMethod $shippingMethod)
    {
        $response = Gate::inspect('update', $shippingMethod);

        if ($response->allowed()) {
            $shippingMethod->update($request->safe()->merge(['user_id' => Auth::id()])->all());

            return (new ShippingMethodResource($shippingMethod))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $response = Gate::inspect('delete', $shippingMethod);

        if ($response->allowed()) {
            $shippingMethod->delete();

            return new BasicResource(true, __('messages.delete_success'));
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
