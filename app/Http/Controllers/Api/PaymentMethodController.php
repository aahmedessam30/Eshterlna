<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethodRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except(['index', 'show']);
    }

    public function index()
    {
        return PaymentMethodResource::collection(PaymentMethod::latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(PaymentMethodRequest $request)
    {
        $paymentMethod = PaymentMethod::create($request->safe()->merge(['user_id' => Auth::id()])->all());

        return (new PaymentMethodResource($paymentMethod))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return (new PaymentMethodResource($paymentMethod))->additional(['status' => true]);
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $response = Gate::inspect('update', $paymentMethod);

        if ($response->allowed()) {
            $paymentMethod->update($request->safe()->merge(['user_id' => Auth::id()])->all());

            return (new PaymentMethodResource($paymentMethod))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        }else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $response = Gate::inspect('delete', $paymentMethod);

        if ($response->allowed()) {
            $paymentMethod->delete();

            return new BasicResource(true, __('messages.delete_success'));
        }else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
