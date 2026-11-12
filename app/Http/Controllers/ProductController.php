<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk dengan pencarian, filter kategori, stok rendah, dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $lowStock = $request->input('low_stock');

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($lowStock, function ($query) {
                return $query->whereColumn('stock', '<=', 'minimum_stock');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Tampilkan form tambah produk.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Simpan produk baru ke database dengan validasi server-side.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);

        try {
            Product::create($request->all());

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail produk.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Tampilkan form edit produk.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update data produk ke database.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $product->update($request->all());

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    /**
     * Hapus produk (pastikan aturan bisnis: produk yang sudah bertransaksi tidak boleh dihapus).
     */
    public function destroy(Product $product)
    {
        try {
            if (method_exists($product, 'saleItems') && $product->saleItems()->count() > 0 || 
                method_exists($product, 'purchaseItems') && $product->purchaseItems()->count() > 0) {
                return back()->with('error', 'Produk tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
            }

            $product->delete();

            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
