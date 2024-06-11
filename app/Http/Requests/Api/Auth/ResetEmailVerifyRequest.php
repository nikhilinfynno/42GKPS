<?php

namespace App\Http\Requests\Api\Auth;

use App\Traits\ApiResponseTraits;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResetEmailVerifyRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
            'otp' => 'required'
        ];
         
    }
}
