<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\StoreItemRequest;
use App\Http\Requests\Warehouse\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $items      = $query->latest()->paginate(10)->withQueryString();
        $categories = Item::select('category')->distinct()->pluck('category');

        return view('warehouse.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Item::select('category')->distinct()->pluck('category');
        return view('warehouse.items.create', compact('categories'));
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('warehouse.items.index')
                         ->with('success', 'Item added to inventory successfully!');
    }

    public function show(Item $item)
    {
        return view('warehouse.items.show', compact('item'));
    }

    public function image(Item $item)
    {
        if (!$item->image || !Storage::disk('public')->exists($item->image)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($item->image));
    }

    public function edit(Item $item)
    {
        $categories = Item::select('category')->distinct()->pluck('category');
        return view('warehouse.items.edit', compact('item', 'categories'));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('warehouse.items.index')
                         ->with('success', 'Item updated successfully!');
    }

    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('warehouse.items.index')
                         ->with('success', 'Item deleted from inventory.');
    }
}