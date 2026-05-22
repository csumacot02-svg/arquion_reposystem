@extends('warehouse.layouts.app')

@section('title', 'Add Item - Warehouse IMS')
@section('page-title', 'Add New Item')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('warehouse.items.index') }}">Inventory</a></li>
    <li class="breadcrumb-item active">Add Item</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-plus-circle me-2 text-primary"></i>New Inventory Item</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('warehouse.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

                {{-- Name --}}
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="e.g. Steel Bolt M8">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- SKU --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                    <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                           value="{{ old('sku') }}" placeholder="e.g. BOLT-M8-001">
                    @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Category --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <input type="text" name="category" list="category-list"
                           class="form-control @error('category') is-invalid @enderror"
                           value="{{ old('category') }}" placeholder="e.g. Hardware, Electronics">
                    <datalist id="category-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Supplier --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Supplier</label>
                    <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror"
                           value="{{ old('supplier') }}" placeholder="e.g. ABC Supplies Inc.">
                    @error('supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Quantity --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" min="0"
                           class="form-control @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', 0) }}">
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Unit Price --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Unit Price (₱) <span class="text-danger">*</span></label>
                    <input type="number" name="unit_price" min="0" step="0.01"
                           class="form-control @error('unit_price') is-invalid @enderror"
                           value="{{ old('unit_price') }}" placeholder="0.00">
                    @error('unit_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Location --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Warehouse Location</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                           value="{{ old('location') }}" placeholder="e.g. Rack A-3, Shelf 2">
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Optional: additional details about this item...">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Image Upload (Bonus) --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Item Image <span class="text-muted small">(Optional)</span></label>
                    <input type="file" name="image" accept="image/*"
                           class="form-control @error('image') is-invalid @enderror"
                           id="imageInput">
                    <div class="form-text">JPG, PNG, WEBP — max 2MB</div>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <img id="imagePreview" src="#" class="mt-2 rounded d-none"
                         style="max-height:120px; object-fit:cover;">
                </div>

            </div>{{-- /row --}}

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-floppy me-1"></i> Save Item
                </button>
                <a href="{{ route('warehouse.items.index') }}" class="btn btn-outline-secondary px-4">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

</div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const file = e.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
});
</script>
@endpush
