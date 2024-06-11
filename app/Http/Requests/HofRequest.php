<?php

namespace App\Http\Requests;

use App\Helpers\CommonHelper;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class HofRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    public function rules()
    {
        $route = Route::currentRouteName();
        $type = request()->get('type');
        $rules = [
            'prefix' => 'required|min:1|max:10',
            'first_name' => 'required|min:3|max:255',
            'middle_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            'marital_status' => 'required',
            'occupation_id' => [
                'required',
                Rule::exists('occupations', 'id')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
            'education_id' => [
                'required',
                Rule::exists('education', 'id')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
            'native_village_id' => [
                'required',
                Rule::exists('native_villages', 'id')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
            'address' => 'required',
            'country_id' => [
                'required',
                Rule::exists('countries', 'id')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
            'state_id' => [
                'required',
                Rule::exists('states', 'id')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
            'height' => 'sometimes',
            'weight' => 'sometimes|numeric|min:1|max:300',
            'image' => 'image|mimes:jpeg,bmp,png|max:1048',
            
            
        ];
        if ($route == "hof.store" || $route == "hof.add.member") {
            $rules['email'] = 'required_if:type,1|email|max:100|unique:users,email,NULL,id';
            $rules['phone'] = 'required_if:type,1|regex:/^\d{4,15}$/|unique:users,phone,NULL,id,deleted_at,NULL';
            
        }elseif($route == "hof.update"){
            $userId = decrypt(request()->route('user'));
            
          
            $rules['email'] = [
                ($type == User::HOF) ? 'required' : 'nullable',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($userId)
            ];

            $rules['phone'] = [
                  ($type == User::HOF) ? 'required' : 'nullable',
                'regex:/^\d{4,15}$/',
                Rule::unique('users', 'phone')->ignore($userId)->whereNull('deleted_at')
            ];
            
        }
        return $rules; 
    }

    public function messages()
    {
        return [
            'phone.exists' => 'The provided phone number is associated with other member.',
            'email.exists' => 'The provided email is associated with other member.',
            'phone.required_if' => 'The phone number is required for HOF member.',
            'email.required_if' => 'The email address is required for HOF member.',
            'country_id.required' => 'Country is required.',
            'country_id.exists' => 'Invalid Country.',
            'state_id.required' => 'State is required.',
            'state_id.exists' => 'Invalid State.',
            'city_id.required' => 'City is required.',
            'city_id.exists' => 'Invalid City.',
            'native_village_id.required' => 'Native village is required.',
            'native_village_id.exists' => 'Invalid Native village.',
            'education_id.required' => 'Education is required.',
            'education_id.exists' => 'Education village.',
            'occupation_id.required' => 'Occupation is required.',
            'occupation_id.exists' => 'Occupation village.',
        ];
    }
    
}
