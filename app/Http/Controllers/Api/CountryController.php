<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        return CountryResource::collection(Country::with('cities')->latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(CountryRequest $request)
    {
        $country = Country::create($request->validated());
        return (new CountryResource($country->load('cities')))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Country $country)
    {
        return (new CountryResource($country->load('cities')))->additional(['status' => true]);
    }

    public function update(CountryRequest $request, Country $country)
    {
        $country->update($request->validated());
        return (new CountryResource($country->load('cities')))
            ->additional(['status' => true, 'message' => __('messages.update_success')]);
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return (new BasicResource(true, __('messages.delete_success'), 'message'));
    }
}
