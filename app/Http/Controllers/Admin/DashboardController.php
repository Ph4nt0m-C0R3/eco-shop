<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Currency option (default USD)
        $currency = request('currency', 'USD');

        // Monthly + Annual earnings based on selected currency
        $paidOrders = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled');

        $rate = \App\Models\Currency::getRate('MMK');

        if ($currency === 'USD') {

            $taxExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN tax
                    ELSE
                        tax / $rate
                END
            ";

            $shippingExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN shipping_fee
                    ELSE
                        shipping_fee / $rate
                END
            ";

        } else {

            $taxExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN tax
                    ELSE
                        tax * $rate
                END
            ";

            $shippingExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN shipping_fee
                    ELSE
                        shipping_fee * $rate
                END
            ";
        }

        $monthlyEarnings = (clone $paidOrders)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->get()
            ->sum($currency === 'MMK' ? 'subtotal_mmk' : 'subtotal_usd');

        $annualEarnings = (clone $paidOrders)
            ->whereYear('created_at', $now->year)
            ->get()
            ->sum($currency === 'MMK' ? 'subtotal_mmk' : 'subtotal_usd');

        $monthlyTax = (clone $paidOrders)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($taxExpression) as total")
            ->value('total') ?? 0;

        $monthlyShipping = (clone $paidOrders)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($shippingExpression) as total")
            ->value('total') ?? 0;

        $annualTax = (clone $paidOrders)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($taxExpression) as total")
            ->value('total') ?? 0;

        $annualShipping = (clone $paidOrders)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($shippingExpression) as total")
            ->value('total') ?? 0;

        $monthlyGross = $monthlyEarnings + $monthlyTax + $monthlyShipping;
        $annualGross  = $annualEarnings + $annualTax + $annualShipping;
        // ===== Tasks % =====
        $validStatuses = ['pending_payment', 'processing', 'shipped'];

        $totalOrders = Order::whereIn('status', $validStatuses)->count();

        $completedOrders = Order::where('status', 'shipped')->count();

        $tasksPercent = $totalOrders > 0
            ? round(($completedOrders / $totalOrders) * 100)
            : 0;

        // ===== Pending Requests =====
        $pendingRequests = Order::where('status', 'pending_payment')->count();

        // ===== Chart Data (Gross = Revenue + Tax + Shipping) =====
        if ($currency === 'USD') {

            $subtotalExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN subtotal_usd
                    ELSE
                        subtotal_mmk / $rate
                END
            ";

            $taxChartExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN tax
                    ELSE
                        tax / $rate
                END
            ";

            $shippingChartExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN shipping_fee
                    ELSE
                        shipping_fee / $rate
                END
            ";

        } else {

            $subtotalExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN subtotal_mmk
                    ELSE
                        subtotal_usd * $rate
                END
            ";

            $taxChartExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN tax
                    ELSE
                        tax * $rate
                END
            ";

            $shippingChartExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN shipping_fee
                    ELSE
                        shipping_fee * $rate
                END
            ";
        }

        $chartRaw = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->whereYear('created_at', $now->year)
            ->selectRaw("
                MONTH(created_at) as month,
                SUM($subtotalExpression) as revenue,
                SUM($taxChartExpression) as tax,
                SUM($shippingChartExpression) as shipping
            ")
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $chartLabels = [];
        $chartRevenue = [];
        $chartTax = [];
        $chartShipping = [];
        $chartGross = [];

        for ($m = 1; $m <= 12; $m++) {

            $chartLabels[] = Carbon::create()->month($m)->format('M');

            $revenue  = $chartRaw[$m]->revenue  ?? 0;
            $tax      = $chartRaw[$m]->tax      ?? 0;
            $shipping = $chartRaw[$m]->shipping ?? 0;

            $gross = $revenue + $tax + $shipping;

            $decimals = $currency === 'MMK' ? 0 : 2;

            $chartRevenue[]  = round($revenue, $decimals);
            $chartTax[]      = round($tax, $decimals);
            $chartShipping[] = round($shipping, $decimals);
            $chartGross[]    = round($gross, $decimals);
        }

        // ===== Pie Chart (Group by payment method TYPE) =====
        $paymentSource = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->select('payment_method', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_method')
            ->get();

        $pieLabels = $paymentSource->pluck('payment_method');
        $pieData = $paymentSource->pluck('total');

        return view('admin.dashboard.index', compact(
            'monthlyEarnings',
            'annualEarnings',
            'monthlyTax',
            'monthlyShipping',
            'annualTax',
            'annualShipping',
            'monthlyGross',
            'annualGross',
            'tasksPercent',
            'pendingRequests',
            'chartRevenue',
            'chartTax',
            'chartShipping',
            'chartGross',
            'chartLabels',
            'pieLabels',
            'pieData',
            'currency'
        ));
    }

    public function generateReport()
    {
        $currency = request('currency', 'USD');
        $now = Carbon::now();

        $rate = \App\Models\Currency::getRate('MMK');

        if ($currency === 'USD') {

            $subtotalExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN subtotal_usd
                    ELSE
                        subtotal_mmk / $rate
                END
            ";

            $taxExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN tax
                    ELSE
                        tax / $rate
                END
            ";

            $shippingExpression = "
                CASE
                    WHEN currency_code = 'USD'
                        THEN shipping_fee
                    ELSE
                        shipping_fee / $rate
                END
            ";

        } else {

            $subtotalExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN subtotal_mmk
                    ELSE
                        subtotal_usd * $rate
                END
            ";

            $taxExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN tax
                    ELSE
                        tax * $rate
                END
            ";

            $shippingExpression = "
                CASE
                    WHEN currency_code = 'MMK'
                        THEN shipping_fee
                    ELSE
                        shipping_fee * $rate
                END
            ";
        }

        $paidOrders = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled');

        $monthlyEarnings = (clone $paidOrders)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($subtotalExpression) as total")
            ->value('total') ?? 0;

        $annualEarnings = (clone $paidOrders)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($subtotalExpression) as total")
            ->value('total') ?? 0;

        $monthlyTax = (clone $paidOrders)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($taxExpression) as total")
            ->value('total') ?? 0;

        $monthlyShipping = (clone $paidOrders)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($shippingExpression) as total")
            ->value('total') ?? 0;

        $annualTax = (clone $paidOrders)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($taxExpression) as total")
            ->value('total') ?? 0;

        $annualShipping = (clone $paidOrders)
            ->whereYear('created_at', $now->year)
            ->selectRaw("SUM($shippingExpression) as total")
            ->value('total') ?? 0;

        $monthlyGross = $monthlyEarnings + $monthlyTax + $monthlyShipping;
        $annualGross  = $annualEarnings + $annualTax + $annualShipping;

        // ===== Currency Formatting =====
        $decimals = $currency === 'MMK' ? 0 : 2;
        $symbol   = $currency === 'MMK' ? 'Ks.' : '$';

        $monthlyEarnings = round($monthlyEarnings, $decimals);
        $annualEarnings  = round($annualEarnings, $decimals);
        $monthlyTax      = round($monthlyTax, $decimals);
        $monthlyShipping = round($monthlyShipping, $decimals);
        $annualTax       = round($annualTax, $decimals);
        $annualShipping  = round($annualShipping, $decimals);
        $monthlyGross    = round($monthlyGross, $decimals);
        $annualGross     = round($annualGross, $decimals);

        $validStatuses = ['pending_payment', 'processing', 'shipped'];

        $totalOrders = Order::whereIn('status', $validStatuses)->count();

        $completedOrders = Order::where('status', 'shipped')->count();

        $pendingRequests = Order::where('status', 'pending_payment')->count();

        $tasksPercent = $totalOrders > 0
            ? round(($completedOrders / $totalOrders) * 100)
            : 0;

        $filename = "dashboard_report_" . now()->format('Y_m_d_H_i_s') . ".csv";

        return response()->streamDownload(function () use (
            $currency,
            $monthlyEarnings,
            $annualEarnings,
            $totalOrders,
            $completedOrders,
            $pendingRequests,
            $tasksPercent,
            $decimals,
            $symbol,
            $monthlyTax,
            $monthlyShipping,
            $annualTax,
            $annualShipping,
            $monthlyGross,
            $annualGross,
        ) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Dashboard Report']);
            fputcsv($handle, ['Generated At', now()]);
            fputcsv($handle, ['Currency', $currency]);
            fputcsv($handle, []);

            fputcsv($handle, ['Metric', 'Value']);

            fputcsv($handle, [
                'Monthly Earnings',
                number_format($monthlyEarnings, $decimals) . ' ' . $symbol
            ]);

            fputcsv($handle, [
                'Monthly Tax',
                number_format($monthlyTax, $decimals) . ' ' . $symbol
            ]);

            fputcsv($handle, [
                'Monthly Shipping',
                number_format($monthlyShipping, $decimals) . ' ' . $symbol
            ]);

            fputcsv($handle, [
                'Monthly Gross',
                number_format($monthlyGross, $decimals) . ' ' . $symbol
            ]);

            fputcsv($handle, []);

            fputcsv($handle, [
                'Annual Earnings',
                number_format($annualEarnings, $decimals) . ' ' . $symbol
            ]);

            fputcsv($handle, [
                'Annual Tax',
                number_format($annualTax, $decimals) . ' ' . $symbol
            ]);

            fputcsv($handle, [
                'Annual Shipping',
                number_format($annualShipping, $decimals) . ' ' . $symbol
            ]);

            fputcsv($handle, [
                'Annual Gross',
                number_format($annualGross, $decimals) . ' ' . $symbol
            ]);
            fputcsv($handle, ['Total Orders', $totalOrders]);
            fputcsv($handle, ['Completed Orders', $completedOrders]);
            fputcsv($handle, ['Tasks Completion %', $tasksPercent . '%']);
            fputcsv($handle, ['Pending Requests', $pendingRequests]);

            fclose($handle);

        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
