<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRequestItem extends Model
{
    protected $fillable = [
        'inventory_request_id',
        'item_id',
        'item_name',
        'category',
        'quantity_requested',
        'priority',
    ];

    public function inventoryRequest()
    {
        return $this->belongsTo(InventoryRequest::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'danger',
            'high'   => 'warning',
            'normal' => 'primary',
            'low'    => 'secondary',
            default  => 'secondary',
        };
    }
}