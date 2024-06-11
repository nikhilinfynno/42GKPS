<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppSettingRequest extends FormRequest
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
        return [
            'android_latest_version' => 'required|regex:/^\d+\.\d+\.\d+$/',
            'ios_latest_version' => 'required|regex:/^\d+\.\d+\.\d+$/',
            'android_is_force_update' => 'required|in:0,1',
            'ios_is_force_update' => 'required|in:0,1',
            'android_app_url' => 'required|url',
            'ios_app_url' => 'required|url',
            'android_info' => 'required|min:3',
            'ios_info' => 'required|min:3',
            'other_headline_text' => 'required|min:3',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $response = response()->json([
                'errors' => $validator->errors()
            ], 422);
        } else {
            $response = redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        throw new HttpResponseException($response);
    }

    public function messages()
    {
       return [];
    }
}
