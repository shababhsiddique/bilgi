<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 32px;
            font-size: 13px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .invoice {
            max-width: 800px;
            margin: 0 auto;
        }
        .toolbar {
            text-align: right;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            background: #ff2056;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #ff2056;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .brand-block { display: flex; align-items: center; gap: 14px; }
        .brand-logo { width: 64px; height: 64px; object-fit: contain; }
        .brand { font-size: 22px; font-weight: 700; color: #111827; }
        .doc-title { font-size: 26px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #ff2056; }
        .meta { text-align: right; color: #4b5563; }
        .meta strong { color: #111827; }
        .columns { display: flex; gap: 32px; margin-bottom: 28px; }
        .columns > div { flex: 1; }
        .columns h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin: 0 0 8px;
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border-bottom: 1px solid #d1d5db;
            padding: 8px 8px;
        }
        tbody td { padding: 10px 8px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .totals { margin-top: 16px; margin-left: auto; width: 280px; }
        .totals td { padding: 6px 8px; }
        .totals .grand td {
            border-top: 2px solid #ff2056;
            color: #ff2056;
            font-size: 16px;
            font-weight: 700;
            padding-top: 10px;
        }
        .footer { margin-top: 40px; text-align: center; color: #9ca3af; font-size: 12px; }
        .footer-links { margin-top: 8px; }
        .footer-links a { color: #ff2056; text-decoration: none; font-weight: 600; }
        @media print {
            body { padding: 0; }
            .toolbar { display: none; }
        }
    </style>
</head>
<body onload="if (window.location.hash !== '#noprint') { window.print(); }">
<div class="invoice">
    <div class="toolbar">
        <button class="btn" onclick="window.print()">Print invoice</button>
    </div>

    <div class="header">
        <div class="brand-block">
            <img class="brand-logo" src="{{ asset('images/bilgi-circle.png') }}" alt="{{ config('app.name') }}">
            <div>
                <div class="brand">{{ config('app.name') }}</div>
                <div style="color:#6b7280;">Invoice</div>
            </div>
        </div>
        <div class="meta">
            <div class="doc-title">Invoice</div>
            <div><strong>#{{ $order->order_number }}</strong></div>
            <div>{{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</div>
        </div>
    </div>

    <div class="columns">
        @if ($order->shippingAddress)
            <div>
                <h3>Shipping to</h3>
                <div><strong>{{ $order->shippingAddress->name }}</strong></div>
                @if ($order->shippingAddress->phone)
                    <div>{{ $order->shippingAddress->phone }}</div>
                @endif
                <div>{{ $order->shippingAddress->address }}</div>
                <div>
                    {{ collect([$order->shippingAddress->city, $order->shippingAddress->state, $order->shippingAddress->postcode])->filter()->implode(', ') }}
                </div>
                @if ($order->shippingAddress->country)
                    <div>{{ $order->shippingAddress->country }}</div>
                @endif
            </div>
        @endif
        <div>
            <h3>Order details</h3>
            <div>Order: <strong>{{ $order->order_number }}</strong></div>
            <div>Payment method: {{ $order->paymentMethod?->name ?? '-' }}</div>
            @if ($order->notes)
                <div style="margin-top:6px;">Notes: {{ $order->notes }}</div>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit price</th>
                <th class="text-right">Line total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name }}
                        @if ($item->variant_name)
                            <div style="color:#6b7280;font-size:12px;">{{ $item->variant_name }}</div>
                        @endif
                        @if ($item->sku)
                            <div style="color:#9ca3af;font-size:11px;">SKU: {{ $item->sku }}</div>
                        @endif
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">৳{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">৳{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">৳{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if ($order->discount_amount > 0)
            <tr>
                <td>Discount</td>
                <td class="text-right">-৳{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td>Shipping</td>
            <td class="text-right">৳{{ number_format($order->shipping_amount, 2) }}</td>
        </tr>
        <tr class="grand">
            <td>Total</td>
            <td class="text-right">৳{{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        <div>Thank you for your order — {{ config('app.name') }}</div>
        <div class="footer-links">
            <a href="https://withbilgi.com/">withbilgi.com</a>
        </div>
    </div>
</div>
</body>
</html>
