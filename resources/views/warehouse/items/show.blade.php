@extends('warehouse.layouts.app')

@section('title', $item->name . ' - Warehouse IMS')
@section('page-title', 'Item Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('warehouse.items.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active">{{ $item->name }}</li>
@endsection

@section('content')
<div class="row g-4">

    {{-- Main Details --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-box me-2 text-info"></i>{{ $item->name }}</h6>
                <span class="badge bg-{{ $item->status_badge }} text-capitalize">
                    {{ str_replace('_', ' ', $item->status) }}
                </span>
            </div>
            <div class="card-body p-4">

                @if($item->image)
                    <img src="{{ route('warehouse.items.image', $item) }}"
                         class="img-fluid rounded mb-4" style="max-height:220px; object-fit:cover;">
                @endif

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">SKU</div>
                            <code class="fs-6">{{ $item->sku }}</code>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">Category</div>
                            <div class="fw-semibold">{{ $item->category }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">Quantity</div>
                            <div class="fw-bold fs-5 {{ $item->quantity < 10 ? 'text-danger' : 'text-success' }}">
                                {{ $item->quantity }} units
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">Unit Price</div>
                            <div class="fw-bold fs-5 text-primary">₱{{ number_format($item->unit_price, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">Total Value</div>
                            <div class="fw-bold fs-5 text-success">
                                ₱{{ number_format($item->quantity * $item->unit_price, 2) }}
                            </div>
                        </div>
                    </div>
                    @if($item->supplier)
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted">Supplier</div>
                            <div class="fw-semibold">{{ $item->supplier }}</div>
                        </div>
                    </div>
                    @endif
                    @if($item->location)
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>Location</div>
                            <div class="fw-semibold">{{ $item->location }}</div>
                        </div>
                    </div>
                    @endif
                    @if($item->description)
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <div class="small text-muted mb-1">Description</div>
                            <div>{{ $item->description }}</div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Sidebar Actions --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('warehouse.items.edit', $item) }}" class="btn btn-warning text-white">
                        <i class="bi bi-pencil me-1"></i> Edit Item
                    </a>
                    <a href="{{ route('warehouse.items.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
                    </a>
                    <form action="{{ route('warehouse.items.destroy', $item) }}" method="POST"
                          onsubmit="return confirm('Delete this item permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-1"></i> Delete Item
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Record Info</h6>
                <div class="small text-muted">
                    <div class="mb-2">
                        <i class="bi bi-calendar-plus me-1"></i>
                        <strong>Created:</strong><br>
                        {{ $item->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div>
                        <i class="bi bi-calendar-check me-1"></i>
                        <strong>Last Updated:</strong><br>
                        {{ $item->updated_at->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
