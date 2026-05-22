<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Request Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f4f8; }
        .form-card { max-width: 1100px; margin: 40px auto; border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .form-header { background: linear-gradient(135deg, #1a2035, #2d3a5e); color: #fff; border-radius: 16px 16px 0 0; padding: 28px 32px; }
        .section-label { font-size: 11px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #6c757d; margin-bottom: 12px; }
        .form-control:focus, .form-select:focus { border-color: #2d3a5e; box-shadow: 0 0 0 .2rem rgba(45,58,94,.15); }
        .btn-submit { background: #1a2035; color: #fff; padding: 12px 32px; font-weight: 600; }
        .btn-submit:hover { background: #2d3a5e; color: #fff; }
        .required-star { color: #dc3545; }
        .picker-card { border: 1px solid #dee2e6; border-radius: 14px; padding: 18px; background: #fafbfc; }
        .table-items td, .table-items th { vertical-align: middle; }
        .empty-list {
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            color: #64748b;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="form-card card">

        <div class="form-header">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-clipboard-plus fs-2 text-info"></i>
                <div>
                    <h4 class="mb-0 fw-bold">Inventory Request Form</h4>
                    <small class="opacity-75">Select items, add them to the request list, then submit once</small>
                </div>
            </div>
        </div>

        <div class="card-body p-4">

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form id="request-form" method="POST" action="{{ route('request-form.store') }}">
                @csrf

                <p class="section-label"><i class="bi bi-person-badge me-1"></i>Requester Information</p>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Requester Name <span class="required-star">*</span></label>
                        <input type="text" name="requester_name"
                               class="form-control @error('requester_name') is-invalid @enderror"
                               value="{{ old('requester_name') }}"
                               placeholder="Enter your full name">
                        @error('requester_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email <span class="required-star">*</span></label>
                        <input type="email" name="requester_email"
                               class="form-control @error('requester_email') is-invalid @enderror"
                               value="{{ old('requester_email') }}"
                               placeholder="Enter your email">
                        @error('requester_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Department <span class="required-star">*</span></label>
                        <select name="department" class="form-select @error('department') is-invalid @enderror">
                            <option value="">-- Select Department --</option>
                            @foreach(['Procurement', 'Operations', 'HR', 'Finance', 'IT', 'Logistics'] as $department)
                                <option value="{{ $department }}" {{ old('department') == $department ? 'selected' : '' }}>
                                    {{ $department }}
                                </option>
                            @endforeach
                        </select>
                        @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date Needed <span class="required-star">*</span></label>
                        <input type="date" name="date_needed"
                               class="form-control @error('date_needed') is-invalid @enderror"
                               value="{{ old('date_needed') }}">
                        @error('date_needed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">

                <p class="section-label d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box-seam me-1"></i>Item Picker</span>
                </p>

                <div class="picker-card mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Item Name <span class="required-star">*</span></label>
                            <select id="item_select" class="form-select">
                                <option value="">-- Select Available Item --</option>
                                @foreach($availableItems as $availableItem)
                                    <option value="{{ $availableItem['name'] }}"
                                            data-id="{{ $availableItem['id'] ?? '' }}"
                                            data-category="{{ $availableItem['category'] }}"
                                            data-stock="{{ $availableItem['quantity'] }}">
                                        {{ $availableItem['name'] }} (Stock: {{ $availableItem['quantity'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Category</label>
                            <input type="text" id="item_category" class="form-control" readonly>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Quantity <span class="required-star">*</span></label>
                            <input type="number" id="item_quantity" class="form-control" min="1" value="1">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Priority <span class="required-star">*</span></label>
                            <select id="item_priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="normal" selected>Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="addRequestedItem()">
                                <i class="bi bi-plus-circle me-1"></i> Add Item to List
                            </button>
                        </div>
                    </div>
                </div>

                <p class="section-label"><i class="bi bi-list-check me-1"></i>Requested Items</p>

                <div id="empty-items-state" class="empty-list mb-3">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    No items added yet.
                </div>

                <div id="items-table-wrapper" class="table-responsive mb-3" style="display:none;">
                    <table class="table table-bordered table-items align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th style="width: 120px;">Qty</th>
                                <th style="width: 140px;">Priority</th>
                                <th style="width: 110px;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="requested-items-body"></tbody>
                    </table>
                </div>

                <div id="hidden-items-wrapper"></div>

                <hr class="my-3">

                <p class="section-label"><i class="bi bi-card-text me-1"></i>Additional Details</p>
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Purpose / Justification <span class="required-star">*</span></label>
                        <textarea name="purpose" rows="4"
                                  class="form-control @error('purpose') is-invalid @enderror"
                                  placeholder="Explain why these items are needed and how they will be used...">{{ old('purpose') }}</textarea>
                        @error('purpose') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Additional Remarks</label>
                        <textarea name="remarks" rows="2"
                                  class="form-control @error('remarks') is-invalid @enderror"
                                  placeholder="Any additional notes...">{{ old('remarks') }}</textarea>
                        @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted"><span class="required-star">*</span> Required fields</small>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="clearItemPicker()">
                            <i class="bi bi-eraser me-1"></i> Clear Picker
                        </button>
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send me-1"></i> Send Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script id="old-items-data" type="application/json">
{!! json_encode(old('items', [])) !!}
</script>
<script >

    let requestedItems = [];

    const dataElement = document.getElementById('old-items-data');

    if (dataElement) {
        try {
            requestedItems = JSON.parse(dataElement.textContent);
        } catch (e) {
            requestedItems = [];
        }
    }

    function syncPickerCategory() {
        const select = document.getElementById('item_select');
        const option = select.options[select.selectedIndex];
        const category = option?.dataset?.category || '';
        document.getElementById('item_category').value = category;
    }

    function clearItemPicker() {
        document.getElementById('item_select').value = '';
        document.getElementById('item_category').value = '';
        document.getElementById('item_quantity').value = 1;
        document.getElementById('item_priority').value = 'normal';
    }

    function addRequestedItem() {
        const select = document.getElementById('item_select');
        const option = select.options[select.selectedIndex];

        const itemName = select.value;
        const itemId = option?.dataset?.id || '';
        const category = option?.dataset?.category || '';
        const quantity = parseInt(document.getElementById('item_quantity').value, 10);
        const priority = document.getElementById('item_priority').value;

        if (!itemName) {
            alert('Please select an item first.');
            return;
        }

        if (!quantity || quantity < 1) {
            alert('Quantity must be at least 1.');
            return;
        }

        requestedItems.push({
            item_id: itemId,
            item_name: itemName,
            category: category,
            quantity_requested: quantity,
            priority: priority
        });

        renderRequestedItems();
        clearItemPicker();
    }

    function removeRequestedItem(index) {
        requestedItems.splice(index, 1);
        renderRequestedItems();
    }

    function renderRequestedItems() {
        const body = document.getElementById('requested-items-body');
        const hiddenWrapper = document.getElementById('hidden-items-wrapper');
        const tableWrapper = document.getElementById('items-table-wrapper');
        const emptyState = document.getElementById('empty-items-state');

        body.innerHTML = '';
        hiddenWrapper.innerHTML = '';

        if (requestedItems.length === 0) {
            tableWrapper.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }

        tableWrapper.style.display = 'block';
        emptyState.style.display = 'none';

        requestedItems.forEach((item, index) => {
            const priorityLabel = item.priority.charAt(0).toUpperCase() + item.priority.slice(1);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.item_name}</td>
                <td>${item.category}</td>
                <td>${item.quantity_requested}</td>
                <td>${priorityLabel}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRequestedItem(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            body.appendChild(row);

            hiddenWrapper.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="items[${index}][item_id]" value="${item.item_id ?? ''}">
                <input type="hidden" name="items[${index}][item_name]" value="${escapeHtml(item.item_name)}">
                <input type="hidden" name="items[${index}][category]" value="${escapeHtml(item.category)}">
                <input type="hidden" name="items[${index}][quantity_requested]" value="${item.quantity_requested}">
                <input type="hidden" name="items[${index}][priority]" value="${item.priority}">
            `);
        });
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('item_select').addEventListener('change', syncPickerCategory);
        renderRequestedItems();
        syncPickerCategory();
    });
</script>
</body>
</html>