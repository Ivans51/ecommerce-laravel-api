<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleRequest extends FormRequest
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
                'type'        => 'required|string|unique:user_roles,type',
                'permissions' => 'required|string',
            ],
            'update' => [
                'type'        => 'required|string',
                'permissions' => 'required|string',
            ],
            default => [],
        };
    }
}
