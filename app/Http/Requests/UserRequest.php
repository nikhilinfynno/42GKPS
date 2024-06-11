<?php

namespace App\Http\Requests;

use App\Rules\AddUserRoleRule;
use App\Traits\ApiResponseTraits;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $route = Route::currentRouteName();
        if ($route == "user.store") {
            return [
                'name' => 'required|min:3|max:100',
                'email' => 'required|string|email|max:100|unique:users,email,NULL,id',
                'password' => 'required|confirmed|min:8',
                // 'role_name' => ['required', new AddUserRoleRule()]
            ];
        } elseif ($route == "user.show") {
            $ulid = $this->route('ulid');
            request()->merge(['ulid' => $ulid]);
            return [
                'ulid' => 'required|string|max:100|unique:users,ulid',
            ];
        } else if ($route == "user.update") {
            $userId = request()->route('id');
            return [
                'name' => 'required|min:3|max:100',
                'email' => 'required|string|email|max:100|unique:users,email,' . $userId . ',ulid',
                'password' => [
                    'nullable',
                    'min:8',
                    Rule::requiredIf(function () {
                        return !is_null($this->input('password'));
                    }),
                    'confirmed',
                ]
            ];
        } elseif ($route == "update-user-profile") {
            return [
                'name' => 'sometimes|string|min:3|max:100',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:100',
                    Rule::unique('users', 'email')->ignore(auth()->id()),
                ],
                'password' => [
                    'nullable',
                    'min:8',
                    Rule::requiredIf(function () {
                        return !is_null($this->input('password'));
                    }),
                    'confirmed',
                ],
            ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        $route = Route::currentRouteName();
        if ($route == "api-v1-user-list") {
            throw new HttpResponseException($this->errorResponse(400, "Request param is invalid"));
        } else {
            throw new HttpResponseException($this->errorResponse(422, "Validation errors", $validator->errors()));
        }
    }

    public function messages()
    {
        $route = Route::currentRouteName();
        if ($route == "api-v1-user-list") {
            return [
                'status.in' => 'Status param is invalid',
            ];
        } else {
            return [];
        }
    }
}
