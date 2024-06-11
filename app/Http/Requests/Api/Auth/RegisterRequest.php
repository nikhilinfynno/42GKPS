<?php

namespace App\Http\Requests\Api\Auth;

use App\Traits\ApiResponseTraits;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    use ApiResponseTraits;
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
        return [
            'first_name' => 'required|string|min:3|max:100',
            'last_name' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,NULL,id,deleted_at,NULL',
            'country_code' => 'required|string|max:4',
            'phone' => 'required|regex:/^\d{4,15}$/|unique:users,phone,NULL,id,deleted_at,NULL',
            'timezone' => 'nullable|string|max:255|timezone',
            'work_profile' => 'nullable|array',
            'password' => [
                'required',
                'min:8',
                Rule::requiredIf(function () {
                    return !is_null($this->input('password'));
                }),
                'confirmed',
            ],
        ];
    }
}
