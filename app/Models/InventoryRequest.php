<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    protected $fillable = [
        'requester_name',
        'requester_email',
        'department',
        'item_id',
        'item_name',
        'category',
        'quantity_requested',
        'priority',
        'date_needed',
        'purpose',
        'remarks',
        'status',
        'warehouse_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'date_needed' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function requestItems()
    {
        return $this->hasMany(InventoryRequestItem::class);
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

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            'pending'  => 'warning',
            default    => 'secondary',
        };
    }
}