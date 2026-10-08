<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body, table, td, p, a {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
    </style>
</head>
<body style="margin:0;padding:0;background:#eef2f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#eef2f5;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff;max-width:720px;">

                    <!-- HEADER -->
                    <tr>
                        <td style="background:#81c408;padding:20px 30px;">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <span style="color:#ffffff;font-size:22px;font-weight:700;letter-spacing:0.5px;">
                                            {{ setting('app_name', config('app.name')) }}
                                        </span>
                                    </td>
                                    <td align="right">
                                        <span style="background:#ffffff;color:#2f6b2f;font-size:13px;font-weight:600;padding:6px 14px;border-radius:20px;">
                                            APPROVED
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding:28px 30px;color:#1e293b;">

                            <!-- Greeting -->
                            <p style="margin:0 0 8px;font-size:16px;">
                                Hello <strong>{{ $order->user->name ?? 'Customer' }}</strong>,
                            </p>

                            <p style="margin:0 0 22px;font-size:14.5px;line-height:1.6;color:#334155;">
                                Your payment has been successfully approved. Your order is now confirmed and being prepared for shipment.
                            </p>

                            <!-- Order Info -->
                            <table width="100%" cellpadding="12" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:18px;">
                                <tr>
                                    <td>
                                        <div style="font-size:13px;color:#64748b;">Order Number</div>
                                        <div style="font-size:15px;font-weight:600;">{{ $order->order_number }}</div>
                                    </td>
                                    <td align="right">
                                        <div style="font-size:13px;color:#64748b;">Order Date</div>
                                        <div style="font-size:15px;font-weight:600;">{{ $order->created_at->format('d M Y') }}</div>
                                    </td>
                                </tr>
                            </table>

                            <!-- ITEMS -->
                            <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#ff9800;color:#ffffff;">
                                        <th align="left">Product</th>
                                        <th align="center">Qty</th>
                                        <th align="right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr style="border-bottom:1px solid #e5e7eb;">
                                        <td>{{ $item->product_name }}</td>
                                        <td align="center">{{ $item->quantity }}</td>
                                        <td align="right">{{ format_money($item->total_price, $order->currency_code) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- TOTALS -->
                            <table width="100%" cellpadding="6" cellspacing="0" style="margin-top:16px;">
                                <tr>
                                    <td align="right" style="color:#64748b;">Subtotal</td>
                                    <td align="right">
                                        {{ format_money($order->grand_total - $order->tax - $order->shipping_fee, $order->currency_code) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" style="color:#64748b;">Tax</td>
                                    <td align="right">{{ format_money($order->tax, $order->currency_code) }}</td>
                                </tr>

                                @if($order->shipping_fee > 0)
                                <tr>
                                    <td align="right" style="color:#64748b;">Shipping</td>
                                    <td align="right">{{ format_money($order->shipping_fee, $order->currency_code) }}</td>
                                </tr>
                                @endif

                                <tr>
                                    <td colspan="2" style="border-top:2px solid #81c408;padding-top:10px;"></td>
                                </tr>

                                <tr style="font-size:16px;font-weight:700;">
                                    <td align="right">Total</td>
                                    <td align="right" style="color:#81c408;">
                                        {{ format_money($order->grand_total, $order->currency_code) }}
                                    </td>
                                </tr>
                            </table>

                            <!-- MESSAGE -->
                            <p style="margin-top:22px;font-size:14.5px;line-height:1.6;color:#334155;">
                                Your order will be shipped shortly. Thank you for choosing sustainable shopping.
                            </p>

                            <!-- SIGNATURE -->
                            <p style="margin-top:18px;font-size:14.5px;">
                                Best regards,<br>
                                <strong>{{ setting('site_name', config('app.name')) }} Team</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background:#f1f5f9;padding:18px 30px;border-top:1px solid #e2e8f0;">
                            <table width="100%">
                                <tr>
                                    <td style="font-size:12.5px;color:#64748b;">
                                        This electronic message helps reduce paper usage.
                                        © {{ date('Y') }} {{ setting('site_name', config('app.name')) }}
                                    </td>
                                    <td align="right" style="font-size:12.5px;color:#81c408;">
                                        Eco-friendly Product Marketplace
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>
