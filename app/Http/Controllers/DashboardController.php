<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard beserta ringkasan statistik.
     */
    public function index()
    {
        try {
            $totalProducts = Product::count();
            $totalCategories = Category::count();
            
            // Penjualan khusus hari ini (Sesuai label card di view)
            $todaySales = Sale::whereDate('sold_at', today())
                ->where('status', '!=', 'cancelled')
                ->sum('total'); 
            
            $totalTransactions = Sale::count();
            
            // Produk yang stoknya sudah di bawah atau sama dengan minimum stock secara dinamis (Low-stock list)
            $lowStockProducts = Product::with('category')
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->get();

            return view('dashboard', compact(
                'totalProducts', 
                'totalCategories', 
                'todaySales', 
                'totalTransactions', 
                'lowStockProducts'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data dashboard: ' . $e->getMessage());
        }
    }
}
