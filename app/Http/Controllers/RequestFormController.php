<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequestFormRequest;
use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestFormController extends Controller
{
    public function create()
    {
        $availableItems = Item::query()
            ->where('quantity', '>', 0)
            ->whereIn('status', ['active', 'low_stock'])
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'quantity', 'status'])
            ->map(fn ($item) => [
                'id'       => $item->id,
                'name'     => $item->name,
                'category' => $item->category,
                'quantity' => $item->quantity,
                'status'   => $item->status,
            ])
            ->toArray();

        return view('request-form.create', compact('availableItems'));
    }

    public function store(InventoryRequestFormRequest $request)
    {
        $data = $request->validated();
        $items = collect($data['items']);

        $firstItem = $items->first();
        $itemCount = $items->count();

        $summaryName = $itemCount > 1
            ? $firstItem['item_name'] . ' +' . ($itemCount - 1) . ' more'
            : $firstItem['item_name'];

        $categories = $items->pluck('category')->unique();
        $summaryCategory = $categories->count() > 1
            ? 'Multiple Categories'
            : $categories->first();

        $totalQty = $items->sum('quantity_requested');
        $priorityWeight = ['low' => 1, 'normal' => 2, 'high' => 3, 'urgent' => 4];
        $highestPriority = $items
            ->sortByDesc(fn ($item) => $priorityWeight[$item['priority']] ?? 0)
            ->first()['priority'];

        $inventoryRequest = DB::transaction(function () use ($data, $items, $summaryName, $summaryCategory, $totalQty, $highestPriority) {
            $parent = InventoryRequest::create([
                'requester_name'     => $data['requester_name'],
                'requester_email'    => $data['requester_email'],
                'department'         => $data['department'],
                'item_id'            => $items->first()['item_id'] ?? null,
                'item_name'          => $summaryName,
                'category'           => $summaryCategory,
                'quantity_requested' => $totalQty,
                'priority'           => $highestPriority,
                'date_needed'        => $data['date_needed'],
                'purpose'            => $data['purpose'],
                'remarks'            => $data['remarks'] ?? null,
                'status'             => 'pending',
            ]);

            foreach ($items as $item) {
                InventoryRequestItem::create([
                    'inventory_request_id' => $parent->id,
                    'item_id'              => $item['item_id'] ?? null,
                    'item_name'            => $item['item_name'],
                    'category'             => $item['category'],
                    'quantity_requested'   => $item['quantity_requested'],
                    'priority'             => $item['priority'],
                ]);
            }

            return $parent;
        });

        return redirect()
            ->route('request-form.success')
            ->with('submission_result', [
                'batch_reference'      => 'BATCH-' . strtoupper(Str::random(8)),
                'requester_name'       => $data['requester_name'],
                'requester_email'      => $data['requester_email'],
                'department'           => $data['department'],
                'date_needed'          => $data['date_needed'],
                'purpose'              => $data['purpose'],
                'remarks'              => $data['remarks'] ?? null,
                'warehouse_request_id' => $inventoryRequest->id,
                'submission_status'    => 'sent',
                'items'                => $data['items'],
                'total_count'          => count($data['items']),
            ]);
    }

    public function success()
    {
        if (!session()->has('submission_result')) {
            return redirect()->route('request-form.create');
        }

        return view('request-form.success', [
            'result' => session('submission_result'),
        ]);
    }
}
