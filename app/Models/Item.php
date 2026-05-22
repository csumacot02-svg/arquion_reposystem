<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category',
        'quantity',
        'unit_price',
        'supplier',
        'location',
        'status',
        'description',
        'image',
    ];

    protected static function booted(): void
    {
        static::saving(function ($item) {
            if ($item->quantity <= 0) {
                $item->status = 'out_of_stock';
            } elseif ($item->quantity < 10) {
                $item->status = 'low_stock';
            } else {
                $item->status = 'active';
            }
        });
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₱' . number_format($this->unit_price, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active'       => 'success',
            'low_stock'    => 'warning',
            'out_of_stock' => 'danger',
            default        => 'secondary',
        };
    }
}