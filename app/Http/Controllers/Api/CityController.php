<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{

    public function index()
    {
        return CityResource::collection(City::latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(CityRequest $request)
    {
        $city = City::create($request->validated());
        return (new CityResource($city))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(City $city)
    {
        return (new CityResource($city))->additional(['status' => true]);
    }

    public function update(CityRequest $request, City $city)
    {
        $city->update($request->validated());
        return (new CityResource($city))
            ->additional(['status' => true, 'message' => __('messages.update_success')]);
    }

    public function destroy(City $city)
    {
        $city->delete();
        return new BasicResource(true, __('messages.delete_success'), 'message');
    }
}
