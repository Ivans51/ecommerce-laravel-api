<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $arr    = explode('@', $this->route()->getActionName());
        $method = $arr[1];  // The controller method

        return match ($method) {
            'store' => [
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'telephone'  => 'string',
                'type'       => 'required|string',
                'email'      => 'required|string|email|unique:users',
            ],
            'updateImageProfile' => [
                'id'            => 'required|string|exists:users',
                'image_profile' => 'required|file|image',
            ],
            'updatePassword' => [
                'id'       => 'required|string|exists:users',
                'password' => 'required|string|confirmed'
            ],
            'destroy' => [
                'id' => 'required|string|exists:users'
            ],
            default => [],
        };
    }

    public function messages(): array
    {
        return [
            'email.exists'       => 'Este email no esta registrado',
            'password.confirmed' => 'La contraseña no son la mismas',
            "id.exists"          => __('validation.exists', ['attribute' => 'id']),

            "id.required"       => __('validation.required', ['attribute' => 'id']),
            "email.required"    => __('validation.required', ['attribute' => 'email']),
            "password.required" => __('validation.required', ['attribute' => 'password']),
            "token.required"    => __('validation.required', ['attribute' => 'token']),
            "user_id.required"  => __('validation.required', ['attribute' => 'user_id']),

            "first_name.required"           => __('validation.required', ['attribute' => 'first_name']),
            "last_name.required"            => __('validation.required', ['attribute' => 'last_name']),
            "telephone.required"            => __('validation.required', ['attribute' => 'telephone']),
            "type.required"                 => __('validation.required', ['attribute' => 'type']),
            "g-recaptcha-response.required" => __('validation.required', ['attribute' => 'g-recaptcha-response']),

            "id.string"       => __('validation.string', ['attribute' => 'id']),
            "email.string"    => __('validation.string', ['attribute' => 'email']),
            "password.string" => __('validation.string', ['attribute' => 'password']),
            "token.string"    => __('validation.string', ['attribute' => 'token']),
            "user_id.string"  => __('validation.string', ['attribute' => 'user_id']),
        ];
    }
}
