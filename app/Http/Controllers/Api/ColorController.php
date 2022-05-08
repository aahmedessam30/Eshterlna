<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except('index', 'show');
    }

    public function index()
    {
        return ColorResource::collection(Color::whereOnline()->latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(ColorRequest $request)
    {
        $color = Color::create($request->validated());
        return (new ColorResource($color))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Color $color)
    {
        return (new ColorResource($color))
            ->additional(['status' => true]);
    }

    public function update(ColorRequest $request, Color $color)
    {
        $response = Gate::inspect('update', $color);

        if ($response->allowed()) {

            $color->update($request->validated());
            return (new ColorResource($color))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(Color $color)
    {
        $response = Gate::inspect('delete', $color);

        if ($response->allowed()) {

            $color->delete();
            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
