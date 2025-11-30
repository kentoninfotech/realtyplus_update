<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Realty Plus | Transaction Receipt #{{ $transaction->id }}</title>

  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  
  <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
    }

    .receipt-wrapper {
        padding: 20px;
    }

    .receipt-container {
        max-width: 800px;
        margin: 0 auto;
        background-color: white;
        padding: 25px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    /* Banner */
    .receipt-banner {
        text-align: center;
        margin-bottom: 20px;
    }

    .receipt-banner img {
        max-width: 100%;
        height: auto;
        max-height: 150px;
    }

    /* Header Section */
    .receipt-header {
        text-align: center;
        border-bottom: 2px solid #007bff;
        padding-bottom: 12px;
        margin-bottom: 15px;
    }

    .receipt-header.lease-type {
        border-bottom-color: #28a745;
    }

    .receipt-header.property-type {
        border-bottom-color: #0d6efd;
    }

    .receipt-header.maintenance-type {
        border-bottom-color: #ff9800;
    }

    .company-logo {
        max-width: 80px;
        height: auto;
        margin-bottom: 8px;
    }

    .company-name {
        font-size: 22px;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 5px;
    }

    .receipt-header.lease-type .company-name {
        color: #28a745;
    }

    .receipt-header.property-type .company-name {
        color: #0d6efd;
    }

    .receipt-header.maintenance-type .company-name {
        color: #ff9800;
    }

    .company-info {
        font-size: 11px;
        color: #666;
        line-height: 1.5;
        margin-bottom: 3px;
    }

    .company-info div {
        margin: 2px 0;
    }

    .receipt-title {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-top: 10px;
        margin-bottom: 3px;
    }

    .receipt-header.lease-type .receipt-title {
        color: #28a745;
    }

    .receipt-header.property-type .receipt-title {
        color: #0d6efd;
    }

    .receipt-header.maintenance-type .receipt-title {
        color: #ff9800;
    }

    .receipt-id {
        font-size: 12px;
        color: #666;
        margin-bottom: 10px;
    }

    /* Transaction Details */
    .details-section {
        margin-bottom: 15px;
    }

    .section-title {
        font-size: 11px;
        font-weight: bold;
        color: white;
        background-color: #007bff;
        padding: 6px 10px;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .details-section.lease-type .section-title {
        background-color: #28a745;
    }

    .details-section.property-type .section-title {
        background-color: #0d6efd;
    }

    .details-section.maintenance-type .section-title {
        background-color: #ff9800;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 8px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 9px;
        color: #999;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.2px;
        margin-bottom: 2px;
    }

    .detail-value {
        font-size: 12px;
        color: #333;
        font-weight: 500;
    }

    .detail-value.amount {
        font-size: 16px;
        font-weight: bold;
        color: #28a745;
    }

    .detail-value.debit {
        font-size: 16px;
        font-weight: bold;
        color: #dc3545;
    }

    /* Payer Information */
    .payer-info {
        background-color: #f9f9f9;
        padding: 10px;
        border-left: 3px solid #17a2b8;
        margin-bottom: 10px;
    }

    .payer-info .detail-label {
        color: #666;
    }

    .payer-info .detail-value {
        font-size: 12px;
        font-weight: 600;
    }

    /* Lease Details */
    .lease-section {
        background-color: #f0f8ff;
        padding: 10px;
        border-left: 3px solid #28a745;
        margin-bottom: 10px;
    }

    .lease-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px;
    }

    /* Amount Summary Box */
    .amount-box {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 12px;
        border-radius: 6px;
        text-align: center;
        margin: 12px 0;
    }

    .amount-box.lease-type {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    }

    .amount-box.property-type {
        background: linear-gradient(135deg, #0d6efd 0%, #0a47a8 100%);
    }

    .amount-box.maintenance-type {
        background: linear-gradient(135deg, #ff9800 0%, #e68900 100%);
    }

    .amount-box .label {
        font-size: 10px;
        text-transform: uppercase;
        opacity: 0.9;
        margin-bottom: 5px;
        letter-spacing: 0.3px;
    }

    .amount-box .amount {
        font-size: 28px;
        font-weight: bold;
        margin: 5px 0;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 6px;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-failed {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Type Badges */
    .type-badge {
        display: inline-block;
        background: #d4edda;
        color: #155724;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 9px;
        font-weight: bold;
        margin-right: 5px;
    }

    .type-badge.property-badge {
        background: #cfe2ff;
        color: #084298;
    }

    .type-badge.maintenance-badge {
        background: #fff3cd;
        color: #856404;
    }

    /* Action Buttons */
    .receipt-actions {
        text-align: right;
        margin-bottom: 15px;
    }

    .action-btn {
        background-color: #007bff;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        margin-left: 8px;
        text-decoration: none;
        display: inline-block;
    }

    .action-btn:hover {
        background-color: #0056b3;
    }

    .action-btn.btn-back {
        background-color: #6c757d;
    }

    .action-btn.btn-back:hover {
        background-color: #5a6268;
    }

    /* Footer */
    .receipt-footer {
        border-top: 1px solid #e0e0e0;
        padding-top: 10px;
        margin-top: 15px;
        text-align: center;
        font-size: 9px;
        color: #999;
    }

    .footer-note {
        margin-bottom: 5px;
        font-style: italic;
    }

    .footer-timestamp {
        font-size: 8px;
        color: #bbb;
    }

    /* Separator */
    .separator {
        height: 1px;
        background-color: #e0e0e0;
        margin: 8px 0;
    }

    .separator-small {
        height: 1px;
        background-color: #e0e0e0;
        margin: 6px 0;
    }

    /* Print Styles */
    @media print {
        body {
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .receipt-wrapper {
            padding: 0;
        }

        .receipt-container {
            box-shadow: none;
            margin: 0;
            padding: 15px;
            max-width: 100%;
        }

        .receipt-actions {
            display: none;
        }
    }

    /* Responsive */
    @media (max-width: 600px) {
        .receipt-container {
            padding: 15px;
        }

        .details-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .lease-grid {
            grid-template-columns: 1fr;
        }

        .company-name {
            font-size: 18px;
        }

        .amount-box .amount {
            font-size: 24px;
        }
    }
</style>

</head>
<body>
      <div style="text-align: center !important"><img src="{{asset('dist/img/banner.png')}}" alt="kenton" height="200" width="auto"></div>


    

    <section class="content">
        @yield('content')
    </section>

</body>
</html>
