<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ReviewResource;
use App\Models\Item;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index(ReviewRequest $request)
    {
        $reviews = Auth::check() && Auth::user()->type == 'merchant'
            ? Review::auth()->where('item_id', $request->safe()->item_id)
            : Review::where('item_id', $request->safe()->item_id);

        return ReviewResource::collection($reviews->latest()->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(ReviewRequest $request)
    {
        $review = Review::create($request->safe()->merge(['user_id' => Auth::id()])->all());

        // Send Notification For Authorized Customer
        sendFireBaseNotification(Auth::user(), __('notification.review_added',
            ['name' => Item::find($request->safe()->item_id)->name, 'customer' => Auth::user()->name]));

        return (new ReviewResource($review))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Review $review)
    {
        return (new ReviewResource($review))->additional(['status' => true]);
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $response = Gate::inspect('update', $review);

        if ($response->allowed()) {

            $review->update($request->safe()->merge(['user_id' => Auth::id()])->all());

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.review_updated',
                ['name' => Item::find($request->safe()->item_id)->name, 'customer' => Auth::user()->name]));

            return (new ReviewResource($review))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }

    }

    public function destroy(Review $review)
    {
        $response = Gate::inspect('delete', $review);

        if ($response->allowed()) {

            $review->delete();

            // Send Notification For Authorized Customer
            sendFireBaseNotification(Auth::user(), __('notification.review_deleted',
                ['name' => Item::find($review->item_id)->name, 'customer' => Auth::user()->name]));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
