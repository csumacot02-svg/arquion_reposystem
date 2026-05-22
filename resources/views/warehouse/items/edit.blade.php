@extends('warehouse.layouts.app')

@section('title', 'Edit Item - Warehouse IMS')
@section('page-title', 'Edit Item')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('warehouse.items.index') }}">Inventory</a></li>
    <li class="breadcrumb-item"><a href="{{ route('warehouse.items.show', $item) }}">{{ $item->name }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-pencil me-2 text-warning"></i>Edit: {{ $item->name }}
        </h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('warehouse.items.update', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $item->name) }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                    <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                           value="{{ old('sku', $item->sku) }}">
                    @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <input type="text" name="category" list="category-list"
                           class="form-control @error('category') is-invalid @enderror"
                           value="{{ old('category', $item->category) }}">
                    <datalist id="category-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Supplier</label>
                    <input type="text" name="supplier" class="form-control @error('supplier') is-invalid @enderror"
                           value="{{ old('supplier', $item->supplier) }}">
                    @error('supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" min="0"
                           class="form-control @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', $item->quantity) }}">
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Unit Price (₱) <span class="text-danger">*</span></label>
                    <input type="number" name="unit_price" min="0" step="0.01"
                           class="form-control @error('unit_price') is-invalid @enderror"
                           value="{{ old('unit_price', $item->unit_price) }}">
                    @error('unit_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Warehouse Location</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                           value="{{ old('location', $item->location) }}">
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Image --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Item Image</label>
                    @if($item->image)
                        <div class="mb-2">
                            <img src="{{ route('warehouse.items.image', $item) }}" class="rounded"
                                 style="max-height:100px; object-fit:cover;">
                            <div class="form-text">Current image. Upload a new one to replace it.</div>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="form-control @error('image') is-invalid @enderror"
                           id="imageInput">
                    <img id="imagePreview" src="#" class="mt-2 rounded d-none"
                         style="max-height:120px; object-fit:cover;">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning px-4 text-white">
                    <i class="bi bi-arrow-clockwise me-1"></i> Update Item
                </button>
                <a href="{{ route('warehouse.items.show', $item) }}" class="btn btn-outline-secondary px-4">
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
