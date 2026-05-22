<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ignore current record's own SKU on unique check
        $itemId = $this->route('item')->id ?? $this->route('item');

        return [
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:100|unique:items,sku,' . $itemId,
            'category'    => 'required|string|max:100',
            'quantity'    => 'required|integer|min:0',
            'unit_price'  => 'required|numeric|min:0',
            'supplier'    => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Item name is required.',
            'sku.required'        => 'SKU is required.',
            'sku.unique'          => 'This SKU is already in use.',
            'category.required'   => 'Category is required.',
            'quantity.required'   => 'Quantity is required.',
            'quantity.min'        => 'Quantity cannot be negative.',
            'unit_price.required' => 'Unit price is required.',
            'unit_price.min'      => 'Price cannot be negative.',
        ];
    }
}
