<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
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
            'store', 'update' => [
                'address_line1' => 'required|string',
                'address_line2' => 'string',
                'city'          => 'required|string',
                'postal_code'   => 'required|string',
                'country'       => 'required|string',
                'telephone'     => 'required|string',
                'mobile'        => 'required|string',
                'user_id'       => 'required|string|exists:users,id',
            ],
            default => [],
        };
    }
}
