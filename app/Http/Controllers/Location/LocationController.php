<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LocationController extends Controller
{
    public function getCountries(Request $request)
    {
        $countries = Country::all();

        $view = View::make('common.options', [
            'options' => $countries,
            'value_variable' => 'id',
            'label_variable' => 'name',
            'selected_condition' => [
                'column' => 'id',
                'value' => (isset($request->data) & !empty($request->data)) ? $request->data :Country::DEFAULT_SELECTED,
            ]
        ]);
        $html = $view->render();
        return response()->json(['html' => $html, 'message' => 'Data Fetched Successfully', 'success' => 1]);
    }
    public function getStates(Request $request,$country_id = null)
    {
        $states = State::when($country_id, function ($query, $country_id) {
            return $query->where('country_id', $country_id);
        })->get();

        $view = View::make('common.options', [
            'options' => $states,
            'value_variable' => 'id',
            'label_variable' => 'name',
            'selected_condition' => [
                'column' => 'id',
                'value' => (isset($request->data) & !empty($request->data)) ? $request->data : State::DEFAULT_SELECTED,
            ]
        ]);
        $html = $view->render();
        return response()->json(['html' => $html, 'message' => 'Data Fetched Successfully', 'success' => 1]);
    }

    public function getCities(Request $request,$state_id)
    {
        $cities = City::when($state_id, function ($query, $state_id) {
            return $query->where('state_id', $state_id);
        })->get();
        $view = View::make('common.options', [
            'options' => $cities,
            'value_variable' => 'id',
            'label_variable' => 'name',
            'selected_condition' => [
                'column' => 'id',
                'value' => (isset($request->data) & !empty($request->data)) ? $request->data : City::DEFAULT_SELECTED,
            ]
        ]);
        $html = $view->render();
        return response()->json(['html' => $html, 'message' => 'Data Fetched Successfully', 'success' => 1]);
    }
}
