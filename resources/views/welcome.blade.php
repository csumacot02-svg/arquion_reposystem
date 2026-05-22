<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merged Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #f0f4f8, #dbeafe); }
        .hero-card { max-width: 950px; margin: 90px auto; border: 0; border-radius: 24px; box-shadow: 0 20px 60px rgba(15,23,42,.12); overflow: hidden; }
        .hero-header { background: #1a2035; color: white; padding: 42px; }
        .action-card { border: 1px solid #e5e7eb; border-radius: 18px; padding: 26px; height: 100%; transition: .2s; text-decoration: none; color: inherit; display: block; }
        .action-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(15,23,42,.10); }
        .icon-box { width: 54px; height: 54px; border-radius: 16px; display:flex; align-items:center; justify-content:center; }
    </style>
</head>
<body>
<div class="container">
    <div class="hero-card bg-white">
        <div class="hero-header">
            <h1 class="fw-bold mb-2"><i class="bi bi-box-seam text-info me-2"></i>Merged Inventory System</h1>
            <p class="mb-0 opacity-75">Warehouse inventory management + inventory request form in one Laravel project.</p>
        </div>
        <div class="p-4 p-md-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <a href="{{ route('warehouse.items.index') }}" class="action-card">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mb-3"><i class="bi bi-boxes fs-3"></i></div>
                        <h4 class="fw-bold">Warehouse Dashboard</h4>
                        <p class="text-muted mb-0">Manage items, stock, images, and approve or reject inventory requests.</p>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('request-form.create') }}" class="action-card">
                        <div class="icon-box bg-success bg-opacity-10 text-success mb-3"><i class="bi bi-clipboard-plus fs-3"></i></div>
                        <h4 class="fw-bold">Inventory Request Form</h4>
                        <p class="text-muted mb-0">Submit item requests that appear directly in the warehouse request list.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
