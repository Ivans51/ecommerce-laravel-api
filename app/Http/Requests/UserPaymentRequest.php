<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPaymentRequest extends FormRequest
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
                'payment_type' => 'required|string',
                'provider'     => 'required|string',
                'account_no'   => 'required|integer',
                'expiry'       => 'string',
                'user_id'      => 'required|string|exists:users,id',
            ],
            default => [],
        };
    }
}
