<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        /* Modern PDF Styling */
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }
        
        /* Header */
        .header {
            margin-bottom: 2cm;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 0.5cm;
            display: table;
            width: 100%;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 50%;
        }
        .logo {
            max-height: 50px;
        }
        .doc-type {
            font-size: 18pt;
            font-weight: bold;
            color: #0056b3;
            margin: 0;
            text-transform: uppercase;
        }
        .doc-id {
            font-size: 12pt;
            color: #666;
            margin-top: 5px;
        }

        /* Info Section */
        .info-grid {
            width: 100%;
            margin-bottom: 1cm;
        }
        .info-grid td {
            vertical-align: top;
            width: 50%;
        }
        .info-label {
            font-weight: bold;
            color: #0056b3;
            font-size: 9pt;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 11pt;
            margin-bottom: 10px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1cm;
        }
        th {
            background-color: #f8f9fa;
            color: #0056b3;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #dee2e6;
            font-size: 10pt;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }

        /* Total Section */
        .total-box {
            margin-top: 0.5cm;
            float: right;
            width: 6cm;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .total-label {
            display: table-cell;
            font-weight: bold;
        }
        .total-value {
            display: table-cell;
            text-align: right;
        }
        .grand-total {
            border-top: 1px solid #ddd;
            margin-top: 10px;
            padding-top: 10px;
            font-size: 13pt;
            color: #0056b3;
        }

        /* Badges */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: bold;
        }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-info { background-color: #cfe2ff; color: #084298; }
        .badge-success { background-color: #d1e7dd; color: #0f5132; }
        .badge-danger { background-color: #f8d7da; color: #842029; }

        /* Footer */
        .footer {
            position: fixed;
            bottom: -0.5cm;
            left: 0;
            right: 0;
            height: 1cm;
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .page-number:after { content: counter(page); }

        /* Helpers */
        .clear { clear: both; }
        .mt-4 { margin-top: 20px; }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #0056b3;
            border-bottom: 1px solid #0056b3;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <img src="{{ public_path('assets/logo/logo.png') }}" class="logo" alt="Logo">
        </div>
        <div class="header-right">
            <h1 class="doc-type">@yield('doc_type')</h1>
            <div class="doc-id">@yield('doc_id')</div>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        <div>Généré le {{ now()->format('d/m/Y à H:i') }} - GESTION AVS</div>
        <div class="page-number">Page </div>
    </div>
</body>
</html>
