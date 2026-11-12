<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori dengan jumlah produk dan pagination.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Tampilkan form tambah kategori.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Simpan kategori baru ke database dengan validasi server-side.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah terdaftar di database.',
        ]);

        try {
            Category::create([
                'name' => $request->name,
            ]);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan kategori: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update data kategori ke database.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah terdaftar di database.',
        ]);

        try {
            $category->update([
                'name' => $request->name,
            ]);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    /**
     * Hapus kategori (dengan aturan relasi produk).
     */
    public function destroy(Category $category)
    {
        try {
            // Cek apakah kategori masih dipakai oleh produk
            if ($category->products()->count() > 0) {
                return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
            }

            $category->delete();

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
