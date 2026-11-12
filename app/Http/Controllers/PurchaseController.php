<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['user', 'supplier', 'items.product']);

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                if (\Schema::hasColumn('purchases', 'po_number')) {
                    $q->where('po_number', 'like', '%' . $keyword . '%');
                }
                if (\Schema::hasColumn('purchases', 'purchase_number')) {
                    $q->orWhere('purchase_number', 'like', '%' . $keyword . '%');
                }
                $q->orWhere('id', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $purchases = $query->latest()->paginate(10);
        $suppliers = Supplier::all();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('purchases.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchased_at' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'status' => 'required|in:completed,pending',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $purchaseItemsData = [];

                foreach ($request->items as $itemData) {
                    $product = Product::findOrFail($itemData['product_id']);
                    $unitPrice = $itemData['unit_price'];
                    
                    $subtotal = $unitPrice * $itemData['quantity'];
                    $totalAmount += $subtotal;

                    $purchaseItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $itemData['quantity'],
                        'unit_cost' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];

                    // Stok hanya bertambah jika status completed
                    if ($request->status === 'completed') {
                        $product->increment('stock', $itemData['quantity']);
                    }
                }

                $purchaseData = [
                    'user_id' => auth()->id() ?? 1, // Pengaman jika auth id kosong
                    'supplier_id' => $request->supplier_id,
                    'total' => $totalAmount,
                    'status' => $request->status,
                    'created_at' => $request->purchased_at . ' ' . date('H:i:s'),
                ];

                if (\Schema::hasColumn('purchases', 'po_number')) {
                    $purchaseData['po_number'] = 'PO-' . strtoupper(Str::random(8));
                } elseif (\Schema::hasColumn('purchases', 'purchase_number')) {
                    $purchaseData['purchase_number'] = 'PO-' . strtoupper(Str::random(8));
                }

                $purchase = Purchase::create($purchaseData);

                foreach ($purchaseItemsData as $data) {
                    $purchase->items()->create($data);
                }
            });

            return redirect()->route('purchases.index')->with('success', 'Transaksi pembelian berhasil disimpan.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pembelian: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['user', 'supplier', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function cancel($id)
    {
        if (auth()->user()->role !== 'owner') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Owner yang dapat membatalkan transaksi.');
        }

        try {
            DB::transaction(function () use ($id) {
                $purchase = Purchase::with('items.product')->findOrFail($id);

                if ($purchase->status === 'cancelled') {
                    throw new \Exception('Transaksi pembelian ini sudah dibatalkan sebelumnya.');
                }

                if ($purchase->status === 'completed') {
                    foreach ($purchase->items as $item) {
                        if ($item->product) {
                            $item->product->decrement('stock', $item->quantity);
                        }
                    }
                }

                $purchase->update([
                    'status' => 'cancelled'
                ]);
            });

            return redirect()->back()->with('success', 'Transaksi pembelian berhasil dibatalkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
