<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class AdminForgotPasswordRequest extends FormRequest
{
 
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required', 'string', 'email',
                Rule::exists('users')->where(function ($query) {
                    $query->where('status', User::ACTIVE)
                    ->whereExists(function ($subQuery) {
                        $subQuery->select('user_id')
                            ->from('user_roles')
                            ->whereColumn('users.id', 'user_roles.user_id')
                            ->whereIn('role_id', function ($roleQuery) {
                                $roleQuery->select('id')
                                    ->from('roles')
                                    ->whereIn('slug', [Role::SUPER_ADMIN, Role::ADMIN]);
                            });
                    })
                    ->where('email', request()->input('email'));
                }),
            ],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
     
    public function messages()
    {
        return [
            'email.exists' => 'These credentials do not match our records.'
        ];
    }
}
