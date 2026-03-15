<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue     = Transaction::sum('grand_total');
        $totalTransactions = Transaction::count();
        $totalProducts    = Product::count();
        $totalCategories  = Category::count();
        $recentTransactions = Transaction::with('cashier')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($t) {
                return [
                    'id'           => $t->id,
                    'invoice_code' => $t->invoice_code,
                    'cashier_name' => $t->cashier->name ?? '-',
                    'grand_total'  => $t->grand_total,
                    'grand_total_formatted' => number_format($t->grand_total, 0, ',', '.'),
                    'created_at'   => $t->created_at->format('d M Y, H:i'),
                ];
            });

        return Inertia::render('admin/dashboard/index', [
            "title" => 'POS | Dashboard',
            "additional" => [
                'total_revenue'      => $totalRevenue,
                'total_transactions' => $totalTransactions,
                'total_products'     => $totalProducts,
                'total_categories'   => $totalCategories,
                'recent_transactions' => $recentTransactions,
            ]
        ]);
    }
}
