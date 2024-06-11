<?php

namespace App\Http\Requests\Api\Auth;

use App\Traits\ApiResponseTraits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PhoneVerificationRequest extends FormRequest
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
            'country_code' => 'required|string|max:4',
            'phone' => [
                'required',
                'regex:/^\d{4,15}$/',
                Rule::exists('users')->where(function ($query) {
                    $query->where('status', 1)
                    ->where('phone', request()->input('phone'))
                    ->where('country_code', request()->input('country_code'))
                    ->where('status', 1);
                    
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => 'The provided phone number is not associated with any account.'
        ];
    }
}
