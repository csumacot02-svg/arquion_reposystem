<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryRequest::with(['item', 'requestItems']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('requester_name', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'    => InventoryRequest::count(),
            'pending'  => InventoryRequest::where('status', 'pending')->count(),
            'approved' => InventoryRequest::where('status', 'approved')->count(),
            'rejected' => InventoryRequest::where('status', 'rejected')->count(),
        ];

        return view('warehouse.requests.index', compact('requests', 'stats'));
    }

    public function approve(Request $request, InventoryRequest $inventoryRequest)
    {
        $request->validate([
            'warehouse_notes' => 'nullable|string|max:500',
        ]);

        if ($inventoryRequest->status !== 'pending') {
            return redirect()
                ->route('warehouse.requests.index')
                ->with('error', 'Only pending requests can be approved.');
        }

        try {
            DB::transaction(function () use ($request, $inventoryRequest) {
                $requestItems = $inventoryRequest->requestItems()->lockForUpdate()->get();

                if ($requestItems->isEmpty()) {
                    throw new \RuntimeException('This request has no items.');
                }

                foreach ($requestItems as $requestItem) {
                    $itemQuery = Item::query();

                    if ($requestItem->item_id) {
                        $itemQuery->where('id', $requestItem->item_id);
                    } else {
                        $itemQuery->where('name', $requestItem->item_name)
                                  ->where('category', $requestItem->category);
                    }

                    $item = $itemQuery->lockForUpdate()->first();

                    if (!$item) {
                        throw new \RuntimeException("Requested item not found: {$requestItem->item_name}");
                    }

                    if ((int) $item->quantity < (int) $requestItem->quantity_requested) {
                        throw new \RuntimeException("Not enough stock for {$requestItem->item_name}");
                    }

                    $item->quantity = (int) $item->quantity - (int) $requestItem->quantity_requested;

                    if ($item->quantity <= 0) {
                        $item->status = 'out_of_stock';
                    } elseif ($item->quantity < 10) {
                        $item->status = 'low_stock';
                    } else {
                        $item->status = 'active';
                    }

                    $item->save();

                    if (!$requestItem->item_id) {
                        $requestItem->update([
                            'item_id' => $item->id,
                        ]);
                    }
                }

                $inventoryRequest->update([
                    'status'          => 'approved',
                    'warehouse_notes' => $request->input('warehouse_notes'),
                    'reviewed_at'     => now(),
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()
                ->route('warehouse.requests.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('warehouse.requests.index')
            ->with('success', "Request from {$inventoryRequest->requester_name} approved and stock was deducted.");
    }

    public function reject(Request $request, InventoryRequest $inventoryRequest)
    {
        $request->validate([
            'warehouse_notes' => 'required|string|min:5|max:500',
        ], [
            'warehouse_notes.required' => 'Please provide a reason for rejection.',
        ]);

        if ($inventoryRequest->status !== 'pending') {
            return redirect()
                ->route('warehouse.requests.index')
                ->with('error', 'Only pending requests can be rejected.');
        }

        $inventoryRequest->update([
            'status'          => 'rejected',
            'warehouse_notes' => $request->input('warehouse_notes'),
            'reviewed_at'     => now(),
        ]);

        return redirect()
            ->route('warehouse.requests.index')
            ->with('error', "Request from {$inventoryRequest->requester_name} has been rejected.");
    }

    public function destroy(InventoryRequest $inventoryRequest)
    {
        $requestLabel = '#' . str_pad($inventoryRequest->id, 5, '0', STR_PAD_LEFT);

        $inventoryRequest->delete();

        return redirect()
            ->route('warehouse.requests.index')
            ->with('success', "Request {$requestLabel} was removed successfully.");
    }

    public function availableItems()
    {
        $items = Item::query()
            ->where('quantity', '>', 0)
            ->whereIn('status', ['active', 'low_stock'])
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'quantity', 'status']);

        return response()->json([
            'success' => true,
            'items'   => $items,
        ]);
    }

    public function receiveFromForm(Request $request)
    {
        $data = $request->validate([
            'requester_name'  => 'required|string|min:3|max:255',
            'requester_email' => 'required|email|max:255',
            'department'      => 'required|string|max:100',
            'date_needed'     => 'required|date|after:today',
            'purpose'         => 'required|string|min:10|max:1000',
            'remarks'         => 'nullable|string|max:500',

            'items'                       => 'required|array|min:1',
            'items.*.item_id'             => 'nullable|integer|exists:items,id',
            'items.*.item_name'           => 'required|string|min:2|max:255',
            'items.*.category'            => 'required|string|max:100',
            'items.*.quantity_requested'  => 'required|integer|min:1',
            'items.*.priority'            => 'required|in:low,normal,high,urgent',
        ]);

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

        return response()->json([
            'success'    => true,
            'message'    => 'Request received by Warehouse IMS.',
            'request_id' => $inventoryRequest->id,
        ], 201);
    }
}