<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
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
                'quantity'   => 'required|integer',
                'session_id' => 'required|string|exists:shopping_sessions,id',
                'product_id' => 'required|string|exists:products,id',
            ],
            default => [],
        };
    }
}
