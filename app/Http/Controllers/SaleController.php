<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    /**
     * Menampilkan daftar transaksi penjualan beserta filter pencarian, status, dan tanggal.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'items.product']);

        // Filter berdasarkan pencarian No. Transaksi
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where('sale_number', 'like', '%' . $keyword . '%');
        }

        // Filter berdasarkan Status (Pending, Completed, Cancelled)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Tanggal
        if ($request->filled('date')) {
            $query->whereDate('sold_at', $request->date);
        }

        $sales = $query->latest()->paginate(10);

        return view('sales.index', compact('sales'));
    }

    /**
     * Menampilkan form halaman pembuatan transaksi baru.
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    /**
     * Menyimpan transaksi penjualan baru ke database dengan DB::transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'status' => 'nullable|in:completed,pending',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $saleItemsData = [];

                foreach ($request->items as $itemData) {
                    // Mengunci baris produk untuk mencegah race condition (overselling)
                    $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);

                    // Validasi stok mencukupi
                    if ($product->stock < $itemData['quantity']) {
                        throw new \Exception("Stok untuk produk '{$product->name}' tidak mencukupi. Sisa stok: {$product->stock}");
                    }

                    // Ambil harga dari database, bukan dari browser
                    $unitPrice = $product->selling_price; 
                    if ($unitPrice <= 0) {
                        throw new \Exception("Harga produk '{$product->name}' tidak valid.");
                    }

                    // Kalkulasi subtotal dengan benar
                    $subtotal = $unitPrice * $itemData['quantity'];
                    $totalAmount += $subtotal;

                    $saleItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];

                    // Kurangi stok produk
                    $product->decrement('stock', $itemData['quantity']);
                }

                $sale = Sale::create([
                    'user_id' => auth()->id(),
                    'sale_number' => 'TRX-' . strtoupper(Str::random(8)),
                    'total' => $totalAmount,
                    'status' => $request->status ?? 'completed',
                    'sold_at' => now(),
                ]);

                foreach ($saleItemsData as $data) {
                    $sale->items()->create($data);
                }
            });

            return redirect()->route('sales.index')->with('success', 'Transaksi berhasil disimpan dan stok telah diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail transaksi tertentu.
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Membatalkan transaksi penjualan (Khusus Owner) dan mengembalikan stok produk.
     */
    public function cancel($id)
    {
        // Proteksi Server-Side Role Owner
        if (auth()->user()->role !== 'owner') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Owner yang dapat membatalkan transaksi.');
        }

        try {
            DB::transaction(function () use ($id) {
                $sale = Sale::with('items.product')->findOrFail($id);

                if ($sale->status === 'cancelled') {
                    throw new \Exception('Transaksi ini sudah dibatalkan sebelumnya.');
                }

                foreach ($sale->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }

                $sale->update([
                    'status' => 'cancelled'
                ]);
            });

            return redirect()->back()->with('success', 'Transaksi berhasil dibatalkan dan stok produk telah dikembalikan ke display.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
