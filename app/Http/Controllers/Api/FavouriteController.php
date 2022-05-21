<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavouriteRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\FavouriteResource;
use App\Models\Favourite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FavouriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return FavouriteResource::collection(Favourite::latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => 'success']);
    }

    public function store(FavouriteRequest $request)
    {
        $favourite = Favourite::create($request->safe()->merge(['user_id' => Auth::id()])->all());

        // Send Notification Authinticated User
        sendFireBaseNotification(Auth::user(), __('notification.favourite_added', ['name' => $favourite->item->name]));

        return (new FavouriteResource($favourite))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);

    }

    public function destroy(Favourite $favourite)
    {
        $response = Gate::inspect('delete', $favourite);

        if ($response->allowed()) {

            $favourite->delete();

            // Send Notification Authinticated User
            sendFireBaseNotification(Auth::user(), __('notification.favourite_removed', ['name' => $favourite->item->name]));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
