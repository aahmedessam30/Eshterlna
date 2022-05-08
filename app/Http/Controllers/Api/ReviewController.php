<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ReviewResource;
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

    public function index()
    {
        return ReviewResource::collection(Review::latest()->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(ReviewRequest $request)
    {
        $review = Review::create($request->safe()->merge(['user_id' => Auth::id()])->all());

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
            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
