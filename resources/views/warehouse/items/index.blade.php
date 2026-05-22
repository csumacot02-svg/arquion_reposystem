@extends('warehouse.layouts.app')

@section('title', 'Inventory - Warehouse IMS')
@section('page-title', 'Inventory Items')

@section('breadcrumb')
    <li class="breadcrumb-item active">Inventory</li>
@endsection

@section('content')

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ $items->total() }}</div>
                    <div class="small">Total Items</div>
                </div>
                <i class="bi bi-boxes fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ \App\Models\Item::where('status','active')->count() }}</div>
                    <div class="small">Active Items</div>
                </div>
                <i class="bi bi-check-circle fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ \App\Models\Item::where('status','low_stock')->count() }}</div>
                    <div class="small">Low Stock</div>
                </div>
                <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ \App\Models\Item::where('status','out_of_stock')->count() }}</div>
                    <div class="small">Out of Stock</div>
                </div>
                <i class="bi bi-x-circle fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('warehouse.items.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Name, SKU, category, supplier..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active"        {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="low_stock"     {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock"  {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('warehouse.items.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Items Table --}}
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-semibold">Inventory List</h6>
        <span class="text-muted small">{{ $items->total() }} item(s) found</span>
    </div>
    <div class="card-body p-0">
        @if($items->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No items found. <a href="{{ route('warehouse.items.create') }}">Add one now</a>.
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    @php
                        $imagePath = $item->image ? ltrim($item->image, '/') : null;

                        if ($imagePath && \Illuminate\Support\Str::startsWith($imagePath, 'storage/')) {
                            $imagePath = preg_replace('#^storage/#', '', $imagePath);
                        }

                        if ($imagePath && \Illuminate\Support\Str::startsWith($imagePath, 'public/')) {
                            $imagePath = preg_replace('#^public/#', '', $imagePath);
                        }

                        $imageUrl = $imagePath ? asset('storage/' . $imagePath) : null;
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $item->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">

                                @if($item->image)
                                    <img src="{{ asset('storage/' . ltrim($item->image, '/')) }}"
                                        alt="{{ $item->name }}"
                                        style="width:45px; height:45px; object-fit:cover; border-radius:6px;">
                                @endif

                                <span class="fw-semibold">{{ $item->name }}</span>

                            </div>
                        </td>
                        <td><code>{{ $item->sku }}</code></td>
                        <td>{{ $item->category }}</td>
                        <td>
                            <span class="{{ $item->quantity < 10 ? 'text-danger fw-bold' : '' }}">
                                {{ $item->quantity }}
                            </span>
                        </td>
                        <td>₱{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->supplier ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status_badge }} text-capitalize">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('warehouse.items.show', $item) }}"
                               class="btn btn-sm btn-outline-info me-1" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('warehouse.items.edit', $item) }}"
                               class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('warehouse.items.destroy', $item) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete {{ $item->name }}? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <small class="text-muted">
                Showing {{ $items->firstItem() }}–{{ $items->lastItem() }} of {{ $items->total() }} results
            </small>
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>

@endsection