<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequestFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requester_name'  => 'required|string|min:3|max:255',
            'requester_email' => 'required|email|max:255',
            'department'      => 'required|string|max:100',
            'date_needed'     => 'required|date|after:today',
            'purpose'         => 'required|string|min:10|max:1000',
            'remarks'         => 'nullable|string|max:500',

            'items'                    => 'required|array|min:1',
            'items.*.item_id'          => 'nullable|integer|exists:items,id',
            'items.*.item_name'        => 'required|string|min:2|max:255',
            'items.*.category'         => 'required|string|max:100',
            'items.*.quantity_requested' => 'required|integer|min:1|max:10000',
            'items.*.priority'         => 'required|in:low,normal,high,urgent',
        ];
    }

    public function messages(): array
    {
        return [
            'requester_name.required' => 'Your full name is required.',
            'requester_name.min'      => 'Name must be at least 3 characters.',
            'requester_email.required'=> 'Your email address is required.',
            'requester_email.email'   => 'Please enter a valid email address.',
            'department.required'     => 'Please select your department.',
            'date_needed.required'    => 'Please select the date needed.',
            'date_needed.after'       => 'Date needed must be a future date.',
            'purpose.required'        => 'Please explain the purpose of this request.',
            'purpose.min'             => 'Purpose must be at least 10 characters.',

            'items.required'                  => 'Please add at least one item.',
            'items.array'                     => 'Items must be a valid list.',
            'items.min'                       => 'Please add at least one item.',
            'items.*.item_name.required'      => 'Each item must have a name.',
            'items.*.category.required'       => 'Each item must have a category.',
            'items.*.quantity_requested.required' => 'Each item must have a quantity.',
            'items.*.quantity_requested.min'  => 'Each item quantity must be at least 1.',
            'items.*.priority.required'       => 'Each item must have a priority.',
        ];
    }
}