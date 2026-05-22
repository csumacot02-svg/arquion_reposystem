<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Sent – Inventory Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f4f8; }
        .card { max-width: 900px; margin: 50px auto; border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .icon-circle { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-5">

        {{-- STATUS --}}
        <div class="text-center mb-4">
            @if(($result['submission_status'] ?? '') === 'sent')
                <div class="icon-circle bg-success bg-opacity-10">
                    <i class="bi bi-check-lg fs-2 text-success"></i>
                </div>
                <h4 class="fw-bold">Request Sent Successfully</h4>
                <p class="text-muted">Your request was sent to <strong>Warehouse IMS</strong>.</p>
            @else
                <div class="icon-circle bg-danger bg-opacity-10">
                    <i class="bi bi-x-lg fs-2 text-danger"></i>
                </div>
                <h4 class="fw-bold">Request Failed</h4>
                <p class="text-muted">Could not send request to Warehouse.</p>
            @endif
        </div>

        {{-- SUMMARY --}}
        <div class="card bg-light border-0 rounded-3 p-3 mb-4">
            <h6 class="fw-semibold mb-3 text-muted small text-uppercase">Request Summary</h6>
            <div class="row g-3">
                <div class="col-md-4"><strong>Batch Ref:</strong> {{ $result['batch_reference'] }}</div>
                <div class="col-md-4"><strong>Requester:</strong> {{ $result['requester_name'] }}</div>
                <div class="col-md-4"><strong>Department:</strong> {{ $result['department'] }}</div>
                <div class="col-md-4"><strong>Date Needed:</strong> {{ \Carbon\Carbon::parse($result['date_needed'])->format('M d, Y') }}</div>
                <div class="col-md-4"><strong>Total Items:</strong> {{ $result['total_count'] }}</div>

                <div class="col-md-4">
                    <strong>Status:</strong>
                    @if(($result['submission_status'] ?? '') === 'sent')
                        <span class="text-success">Sent</span>
                    @else
                        <span class="text-danger">Failed</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ITEMS TABLE --}}
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result['items'] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['item_name'] }}</td>
                            <td>{{ $item['category'] }}</td>
                            <td>{{ $item['quantity_requested'] }}</td>
                            <td class="text-capitalize">{{ $item['priority'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ACTION --}}
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('request-form.create') }}" class="btn btn-outline-primary px-4">
                <i class="bi bi-plus-circle me-1"></i> New Request
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>