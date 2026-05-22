@extends('warehouse.layouts.app')

@section('title', 'Inventory Requests – Warehouse IMS')
@section('page-title', 'Inventory Requests')

@section('breadcrumb')
    <li class="breadcrumb-item active">Requests</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
                    <div class="small">Total Requests</div>
                </div>
                <i class="bi bi-clipboard-data fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-dark bg-warning">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['pending'] }}</div>
                    <div class="small">Pending</div>
                </div>
                <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['approved'] }}</div>
                    <div class="small">Approved</div>
                </div>
                <i class="bi bi-check-circle fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['rejected'] }}</div>
                    <div class="small">Rejected</div>
                </div>
                <i class="bi bi-x-circle fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('warehouse.requests.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Requester, item, department..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Priority</label>
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                    <option value="high"   {{ request('priority') == 'high'   ? 'selected' : '' }}>🟠 High</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>🟢 Normal</option>
                    <option value="low"    {{ request('priority') == 'low'    ? 'selected' : '' }}>🔵 Low</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('warehouse.requests.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-clipboard-check me-2 text-primary"></i>Incoming Requests
        </h6>
        <span class="text-muted small">{{ $requests->total() }} request(s) found</span>
    </div>

    <div class="card-body p-0">
        @if ($requests->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No requests yet. Waiting for submissions from the Request Form.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Requester</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Priority</th>
                            <th>Date Needed</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                        <tr>
                            <td class="text-muted small">#{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="fw-semibold">{{ $req->requester_name }}</div>
                                <div class="text-muted small">{{ $req->department }}</div>
                            </td>
                            <td>
                                <div>{{ $req->item_name }}</div>
                                <div class="text-muted small">{{ $req->category }}</div>
                            </td>
                            <td class="fw-semibold">{{ $req->quantity_requested }}</td>
                            <td>
                                <span class="badge bg-{{ $req->priority_badge }} text-capitalize">
                                    {{ $req->priority }}
                                </span>
                            </td>
                            <td>{{ $req->date_needed->format('M d, Y') }}</td>
                            <td class="small text-muted">{{ $req->created_at->format('M d, h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ $req->status_badge }} text-capitalize">
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{-- View --}}
                                <button class="btn btn-sm btn-outline-info me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $req->id }}"
                                        title="View">
                                    <i class="bi bi-eye"></i>
                                </button>

                                {{-- Remove --}}
                                <form action="{{ route('warehouse.requests.destroy', $req) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to remove this request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger me-1"
                                            title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                @if($req->status === 'pending')
                                    {{-- Approve --}}
                                    <button class="btn btn-sm btn-outline-success me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#approveModal{{ $req->id }}"
                                            title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    {{-- Reject --}}
                                    <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $req->id }}"
                                            title="Reject">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- Detail Modal --}}
                        <div class="modal fade" id="detailModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div>
                                            <h6 class="modal-title fw-bold mb-1">
                                                <i class="bi bi-clipboard me-2"></i>
                                                Request #{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }} — Details
                                            </h6>
                                            <small class="text-muted">
                                                Submitted by {{ $req->requester_name }} • {{ $req->created_at->format('M d, Y h:i A') }}
                                            </small>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <small class="text-muted d-block">Requester Name</small>
                                                <strong>{{ $req->requester_name }}</strong>
                                            </div>

                                            <div class="col-md-6">
                                                <small class="text-muted d-block">Email</small>
                                                {{ $req->requester_email }}
                                            </div>

                                            <div class="col-md-6">
                                                <small class="text-muted d-block">Department</small>
                                                {{ $req->department }}
                                            </div>

                                            <div class="col-md-6">
                                                <small class="text-muted d-block">Date Submitted</small>
                                                {{ $req->created_at->format('M d, Y h:i A') }}
                                            </div>

                                            <div class="col-md-6">
                                                <small class="text-muted d-block">Summary Item Name</small>
                                                <strong>{{ $req->item_name }}</strong>
                                            </div>

                                            <div class="col-md-3">
                                                <small class="text-muted d-block">Summary Category</small>
                                                {{ $req->category }}
                                            </div>

                                            <div class="col-md-3">
                                                <small class="text-muted d-block">Total Quantity</small>
                                                <span class="fw-bold fs-5">{{ $req->quantity_requested }}</span>
                                            </div>

                                            <div class="col-md-4">
                                                <small class="text-muted d-block">Priority</small>
                                                <span class="badge bg-{{ $req->priority_badge }} text-capitalize">
                                                    {{ $req->priority }}
                                                </span>
                                            </div>

                                            <div class="col-md-4">
                                                <small class="text-muted d-block">Date Needed</small>
                                                {{ $req->date_needed->format('M d, Y') }}
                                            </div>

                                            <div class="col-md-4">
                                                <small class="text-muted d-block">Status</small>
                                                <span class="badge bg-{{ $req->status_badge }} text-capitalize">
                                                    {{ $req->status }}
                                                </span>
                                            </div>

                                            <div class="col-12">
                                                <small class="text-muted d-block">Purpose / Justification</small>
                                                <div class="p-3 bg-light rounded mt-1">{{ $req->purpose }}</div>
                                            </div>

                                            @if($req->remarks)
                                            <div class="col-12">
                                                <small class="text-muted d-block">Additional Remarks</small>
                                                <div class="p-3 bg-light rounded mt-1">{{ $req->remarks }}</div>
                                            </div>
                                            @endif

                                            @if($req->warehouse_notes)
                                            <div class="col-12">
                                                <small class="text-muted d-block">Warehouse Notes</small>
                                                <div class="p-3 rounded mt-1 border-start border-4 {{ $req->status === 'approved' ? 'border-success bg-light' : 'border-danger bg-light' }}">
                                                    {{ $req->warehouse_notes }}
                                                </div>
                                            </div>
                                            @endif

                                            @if($req->reviewed_at)
                                            <div class="col-12">
                                                <small class="text-muted">
                                                    Reviewed at: {{ $req->reviewed_at->format('M d, Y h:i A') }}
                                                </small>
                                            </div>
                                            @endif

                                            {{-- Requested Items Table --}}
                                            @if($req->requestItems && $req->requestItems->count())
                                            <div class="col-12 mt-2">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted d-block mb-0">Requested Items</small>
                                                    <small class="text-muted">
                                                        {{ $req->requestItems->count() }} item(s)
                                                    </small>
                                                </div>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm align-middle mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th style="width: 60px;">#</th>
                                                                <th>Item Name</th>
                                                                <th>Category</th>
                                                                <th style="width: 100px;">Qty</th>
                                                                <th style="width: 120px;">Priority</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($req->requestItems as $item)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $item->item_name }}</td>
                                                                <td>{{ $item->category }}</td>
                                                                <td class="fw-bold">{{ $item->quantity_requested }}</td>
                                                                <td>
                                                                    <span class="badge bg-{{ $item->priority_badge }}">
                                                                        {{ ucfirst($item->priority) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot class="table-light">
                                                            <tr>
                                                                <th colspan="3" class="text-end">Total Quantity</th>
                                                                <th colspan="2">{{ $req->requestItems->sum('quantity_requested') }}</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($req->status === 'pending')
                        {{-- Approve Modal --}}
                        <div class="modal fade" id="approveModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('warehouse.requests.approve', $req) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-success text-white">
                                            <h6 class="modal-title fw-bold">
                                                <i class="bi bi-check-circle me-2"></i>Approve Request
                                            </h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-3">
                                                Approving: <strong>{{ $req->item_name }}</strong>
                                                ({{ $req->quantity_requested }} total units)
                                                from <strong>{{ $req->requester_name }}</strong>.
                                            </p>

                                            @if($req->requestItems && $req->requestItems->count())
                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">Items to approve</small>
                                                    <ul class="mb-0 ps-3">
                                                        @foreach($req->requestItems as $item)
                                                            <li>
                                                                {{ $item->item_name }} — {{ $item->quantity_requested }}
                                                                <span class="text-muted">({{ ucfirst($item->priority) }})</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <label class="form-label fw-semibold">
                                                Warehouse Notes <span class="text-muted small">(Optional)</span>
                                            </label>
                                            <textarea name="warehouse_notes" rows="3" class="form-control"
                                                      placeholder="e.g. Ready for pickup at Rack A-1 on Monday..."></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success px-4">
                                                <i class="bi bi-check-lg me-1"></i> Confirm Approval
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Reject Modal --}}
                        <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('warehouse.requests.reject', $req) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-danger text-white">
                                            <h6 class="modal-title fw-bold">
                                                <i class="bi bi-x-circle me-2"></i>Reject Request
                                            </h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-3">
                                                Rejecting: <strong>{{ $req->item_name }}</strong>
                                                from <strong>{{ $req->requester_name }}</strong>.
                                            </p>

                                            @if($req->requestItems && $req->requestItems->count())
                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">Items in this request</small>
                                                    <ul class="mb-0 ps-3">
                                                        @foreach($req->requestItems as $item)
                                                            <li>
                                                                {{ $item->item_name }} — {{ $item->quantity_requested }}
                                                                <span class="text-muted">({{ ucfirst($item->priority) }})</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <label class="form-label fw-semibold">
                                                Reason for Rejection <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="warehouse_notes" rows="3" class="form-control" required
                                                      placeholder="Explain why this request is rejected..."></textarea>
                                            <div class="form-text">Required — the requester will see this note.</div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger px-4">
                                                <i class="bi bi-x-lg me-1"></i> Confirm Rejection
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <small class="text-muted">
                    Showing {{ $requests->firstItem() }}–{{ $requests->lastItem() }} of {{ $requests->total() }}
                </small>
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>

@endsection