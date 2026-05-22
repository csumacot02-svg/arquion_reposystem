<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmittedRequest extends Model
{
    protected $fillable = [
        'warehouse_request_id',
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
        'submission_status',
    ];

    protected $casts = [
        'date_needed' => 'date',
    ];
}
