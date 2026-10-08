<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $from = request('from');
        $to = request('to');
        $currency = request('currency', 'USD');

        $decimals = $currency === 'MMK' ? 0 : 2;
        $symbol   = $currency === 'MMK' ? 'Ks.' : '$';

        $query = Order::query();

        // Date Filter
        if ($from && $to) {
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay()
            ]);
        }

        // Decide which column to use
        if ($currency === 'USD') {
            $amountExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN (subtotal_usd + tax + shipping_fee)
                    ELSE
                        (subtotal_mmk + tax + shipping_fee) / " . \App\Models\Currency::getRate('MMK') . "
                END
            ";
        } else {
            $amountExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN (subtotal_mmk + tax + shipping_fee)
                    ELSE
                        (subtotal_usd + tax + shipping_fee) * " . \App\Models\Currency::getRate('MMK') . "
                END
            ";
        }

        // ===== Totals =====
        $totalOrders = (clone $query)->count();
        $paidOrders = (clone $query)->where('payment_status', 'paid')->count();
        $cancelledOrders = (clone $query)->where('status', 'cancelled')->count();

        $totalSales = (clone $query)
            ->where('payment_status', 'paid')
            ->selectRaw("SUM($amountExpression) as total")
            ->value('total') ?? 0;

        $totalSales = round($totalSales, $decimals);

        // ===== Sales By Payment Type =====
        $salesByPayment = (clone $query)
                ->where('payment_status', 'paid')
                ->select(
                    'payment_method',
                    DB::raw('COUNT(*) as total_orders'),
                    DB::raw("SUM($amountExpression) as total_amount")
                )
                ->groupBy('payment_method')
                ->get();

        $salesByPayment->transform(function ($row) use ($decimals) {
            $row->total_amount = round($row->total_amount, $decimals);
            return $row;
        });

        // ===== Monthly Sales =====
        $year = Carbon::now()->year;

        $monthlySalesRaw = (clone $query)
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $year)
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw("SUM($amountExpression) as total")
                )
                ->groupBy('month')
                ->pluck('total', 'month');

        $monthlyLabels = [];
        $monthlyData = [];

        for ($m = 1; $m <= 12; $m++) {

            $monthlyLabels[] = Carbon::create()->month($m)->format('M');

            $value = $monthlySalesRaw[$m] ?? 0;

            $monthlyData[] = round($value, $decimals);
        }

        return view('admin.sales.index', compact(
            'currency',
            'totalOrders',
            'paidOrders',
            'cancelledOrders',
            'totalSales',
            'salesByPayment',
            'monthlyLabels',
            'monthlyData',
            'decimals',
            'symbol',
        ));
    }

    public function generateReport()
    {
        $from = request('from');
        $to = request('to');
        $currency = request('currency', 'USD');

        $decimals = $currency === 'MMK' ? 0 : 2;
        $symbol   = $currency === 'MMK' ? 'Ks.' : '$';

        $query = Order::with('user');

        if ($from && $to) {
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay()
            ]);
        }

        // ===== AMOUNT EXPRESSION =====
        if ($currency === 'USD') {
            $amountExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN (subtotal_usd + tax + shipping_fee)
                    ELSE
                        (subtotal_mmk + tax + shipping_fee) / " . \App\Models\Currency::getRate('MMK') . "
                END
            ";
        } else {
            $amountExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN (subtotal_mmk + tax + shipping_fee)
                    ELSE
                        (subtotal_usd + tax + shipping_fee) * " . \App\Models\Currency::getRate('MMK') . "
                END
            ";
        }

        // ===== KPI SUMMARY =====
        $totalOrders     = (clone $query)->count();
        $cancelledOrders = (clone $query)->where('status', 'cancelled') ->count();

        $paidOrders = (clone $query)
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->count();

        $unpaidOrders = (clone $query)
            ->where('payment_status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->count();

        $totalSales = (clone $query)
            ->where('payment_status', 'paid')
            ->selectRaw("SUM($amountExpression) as total")
            ->value('total') ?? 0;

        // ===== PAYMENT METHOD SUMMARY =====
        $paymentSummary = (clone $query)
            ->where('payment_status', 'paid')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as orders'),
                DB::raw("SUM($amountExpression) as amount")
            )
            ->groupBy('payment_method')
            ->get();

        $totalSales = round($totalSales, $decimals);

        $fileName = "sales_report_" . now()->format('Y_m_d_His') . ".csv";

        return response()->streamDownload(function () use (
            $query,
            $currency,
            $from,
            $to,
            $totalOrders,
            $paidOrders,
            $cancelledOrders,
            $unpaidOrders,
            $totalSales,
            $paymentSummary,
            $decimals,
            $symbol
        ) {

            $file = fopen('php://output', 'w');

            // ===== HEADER =====
            fputcsv($file, ['SUPERMARKET SALES REPORT']);
            fputcsv($file, ['Period', ($from ?? 'All Time') . ' to ' . ($to ?? 'Now')]);
            fputcsv($file, ['Currency', $currency]);
            fputcsv($file, []);

            // ===== SUMMARY =====
            fputcsv($file, ['SUMMARY']);
            fputcsv($file, ['Total Orders', $totalOrders]);
            fputcsv($file, ['Paid Orders', $paidOrders]);
            fputcsv($file, ['Cancelled Orders', $cancelledOrders]);
            fputcsv($file, ['Unpaid Orders', $unpaidOrders]);
            fputcsv($file, ['Total Revenue (' . $currency . ')', number_format($totalSales, $decimals)]);
            fputcsv($file, []);

            // ===== PAYMENT METHOD SUMMARY =====
            fputcsv($file, ['PAYMENT METHOD SUMMARY']);
            fputcsv($file, ['Payment Method', 'Orders', 'Amount (' . $currency . ')']);

            foreach ($paymentSummary as $row) {
                fputcsv($file, [
                    strtoupper($row->payment_method),
                    $row->orders,
                    number_format($row->amount, $decimals)
                ]);
            }

            fputcsv($file, []);

            // ===== DETAILED SALES =====
            fputcsv($file, ['DETAILED SALES']);
            fputcsv($file, [
                'Order ID',
                'Customer',
                'Payment Method',
                'Payment Status',
                'Order Status',
                'Amount (' . $currency . ')',
                'Date'
            ]);

            $query->latest()->chunk(200, function ($orders) use ($file, $currency, $decimals) {
                foreach ($orders as $order) {

                    if ($currency === 'USD') {
                        $amount = $order->currency_code === 'USD'
                            ? ($order->subtotal_usd + $order->tax + $order->shipping_fee)
                            : ($order->subtotal_mmk + $order->tax + $order->shipping_fee) / \App\Models\Currency::getRate('MMK');
                    } else {
                        $amount = $order->currency_code === 'MMK'
                            ? ($order->subtotal_mmk + $order->tax + $order->shipping_fee)
                            : ($order->subtotal_usd + $order->tax + $order->shipping_fee) * \App\Models\Currency::getRate('MMK');
                    }

                    fputcsv($file, [
                        $order->id,
                        $order->user->name ?? 'Guest',
                        strtoupper($order->payment_method),
                        $order->payment_status,
                        $order->status,
                        number_format($amount, $decimals),
                        $order->created_at->format('Y-m-d H:i')
                    ]);
                }
            });

            fclose($file);

        }, $fileName, [
            "Content-Type" => "text/csv",
        ]);
    }
}
