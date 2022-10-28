<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
                'name'         => 'required|string',
                'desc'         => 'required|string',
                'SKU'          => 'required|string',
                'price'        => 'required|integer',
                'category_id'  => 'required|string|exists:product_categories,id',
                'inventory_id' => 'required|string|exists:product_inventories,id',
                'discount_id'  => 'required|string|exists:discounts,id',
            ],
            default => [],
        };
    }
}
