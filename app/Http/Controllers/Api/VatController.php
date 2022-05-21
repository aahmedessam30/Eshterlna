<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VatRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\VatResource;
use App\Models\Vat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class VatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant']);
    }

    public function index()
    {
        return VatResource::collection(Vat::auth()->latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(VatRequest $request)
    {
        $vat = Vat::create($request->safe()->merge(['user_id' => Auth::id()])->all());

        // Send Notification For Authorized Merchant
        sendFireBaseNotification($vat->user, __('notification.vat_added'));

        return (new VatResource($vat))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Vat $vat)
    {
        return (new VatResource($vat))->additional(['status' => true]);
    }

    public function update(VatRequest $request, Vat $vat)
    {
        $response = Gate::inspect('update', $vat);

        if ($response->allowed()) {
            $vat->update($request->safe()->merge(['user_id' => Auth::id()])->all());

            // Send Notification For Authorized Merchant
            sendFireBaseNotification($vat->user, __('notification.vat_updated'));

            return (new VatResource($vat))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(Vat $vat)
    {
        $response = Gate::inspect('delete', $vat);

        if ($response->allowed()) {
            $vat->delete();

            // Send Notification For Authorized Merchant
            sendFireBaseNotification($vat->user, __('notification.vat_deleted'));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
