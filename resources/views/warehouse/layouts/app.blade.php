{{--
======================================================================
  REPLACE YOUR ENTIRE resources/views/warehouse/layouts/app.blade.php
  with this file. The only change is adding the "Requests" nav link
  with a live pending badge in the sidebar.
======================================================================
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warehouse Inventory Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar {
            min-height: 100vh;
            background: #1a2035;
            color: #fff;
        }
        .sidebar .nav-link {
            color: #a0aec0;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #2d3a5e;
            color: #fff;
        }
        .sidebar .brand {
            padding: 20px;
            border-bottom: 1px solid #2d3a5e;
        }
        .main-content { padding: 30px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
        .card-header { border-radius: 12px 12px 0 0 !important; }
    </style>
</head>
<body>
<div class="d-flex">

    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column" style="width:240px; min-width:240px;">
        <div class="brand">
            <h6 class="mb-0 fw-bold text-white">
                <i class="bi bi-box-seam me-2 text-info"></i>Warehouse IMS
            </h6>
            <small class="text-muted">Inventory Management</small>
        </div>
        <nav class="nav flex-column mt-3">
            {{-- Dashboard / Inventory --}}
            <a href="{{ route('warehouse.items.index') }}"
               class="nav-link {{ request()->routeIs('warehouse.items.*') ? 'active' : '' }}">
                <i class="bi bi-boxes me-2"></i> Inventory
            </a>

            <a href="{{ route('warehouse.items.create') }}" class="nav-link">
                <i class="bi bi-plus-circle me-2"></i> Add Item
            </a>

            <a href="{{ route('request-form.create') }}" class="nav-link">
                <i class="bi bi-clipboard-plus me-2"></i> Request Form
            </a>

            {{-- Requests nav link with live pending badge --}}
            <a href="{{ route('warehouse.requests.index') }}"
               class="nav-link d-flex align-items-center {{ request()->routeIs('warehouse.requests.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check me-2"></i>
                <span>Requests</span>
                @php $pendingCount = \App\Models\InventoryRequest::where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                @endif
            </a>
        </nav>
    </div>

    {{-- Main Content --}}
    <div class="flex-grow-1 main-content">

        {{-- Top Bar --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0">@yield('page-title', 'Dashboard')</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">@yield('breadcrumb')</ol>
                </nav>
            </div>
            <a href="{{ route('warehouse.items.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Item
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
