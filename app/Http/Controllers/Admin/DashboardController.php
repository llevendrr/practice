<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'orders' => Order::count(),
            'users' => User::count(),
        ];

        $totalRevenue = $this->paidOrders()->sum('total');
        $paidOrdersCount = $this->paidOrders()->count();
        $averageCheck = $paidOrdersCount ? $totalRevenue / $paidOrdersCount : 0;

        $analytics = [
            'total_revenue' => $totalRevenue,
            'revenue_week' => $this->paidOrders()->where('created_at', '>=', now()->subDays(7))->sum('total'),
            'orders_7' => $this->paidOrders()->where('created_at', '>=', now()->subDays(7))->count(),
            'orders_30' => $this->paidOrders()->where('created_at', '>=', now()->subDays(30))->count(),
            'avg_check' => $averageCheck,
        ];

        $chartStart = Carbon::now()->subDays(13)->startOfDay();
        $rawChart = $this->paidOrders()
            ->selectRaw('DATE(created_at) as day, SUM(total) as total')
            ->where('created_at', '>=', $chartStart)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $chartData = collect(range(0, 13))
            ->map(function (int $offset) use ($chartStart, $rawChart) {
                $day = $chartStart->copy()->addDays($offset);
                $key = $day->format('Y-m-d');

                return [
                    'day' => $day,
                    'total' => (float) ($rawChart[$key]->total ?? 0),
                ];
            })
            ->values();

        $chartMax = $chartData->max('total') ?: 1;

        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where(function ($query) {
                $query->where('orders.payment_status', Order::PAYMENT_PAID)
                    ->orWhere('orders.status', Order::STATUS_DONE);
            })
            ->select(
                'products.id',
                'products.name',
                'products.brand',
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM((order_items.price - order_items.discount) * order_items.quantity) as revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.brand')
            ->orderByDesc('quantity')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'analytics', 'chartData', 'chartMax', 'topProducts'));
    }

    private function paidOrders()
    {
        return Order::where(function ($query) {
            $query->where('payment_status', Order::PAYMENT_PAID)
                ->orWhere('status', Order::STATUS_DONE);
        });
    }
}
