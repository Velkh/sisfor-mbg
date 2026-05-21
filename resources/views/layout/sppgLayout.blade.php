<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dashboard SPPG</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0066cc;
            --primary-dark: #0052a3;
            --secondary: #f0f3f7;
            --text: #1a1a1a;
            --text-muted: #666;
            --border: #e0e0e0;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--secondary);
            color: var(--text);
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        /* Main Content */
        .main-content {
            padding: 30px;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-card-icon {
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .stat-card-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 5px;
        }

        .stat-card-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Cards */
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card-header {
            background: white;
            border-bottom: 2px solid var(--primary);
            padding: 15px 20px;
        }

        .card-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }

        .card-body {
            padding: 20px;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--secondary);
            font-weight: 700;
            color: var(--text);
            border-bottom: 2px solid var(--border);
            padding: 12px;
            font-size: 13px;
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .table tbody tr:hover {
            background-color: var(--secondary);
        }

        .badge {
            padding: 5px 10px;
            font-weight: 600;
            font-size: 11px;
            border-radius: 5px;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                margin-left: 0;
            }

            .sidebar-sppg {
                position: fixed;
                left: -250px;
                transition: left 0.3s ease;
                z-index: 1100;
            }

            .sidebar-sppg.show {
                left: 0;
            }

            .main-content {
                padding: 20px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>
<body class="sppg-layout">
    <!-- Sidebar -->
    @include('layout.sidebarSppg')

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add any custom scripts here
        });
    </script>
</body>
</html>