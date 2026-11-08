<?php

namespace App\Http\Controllers;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }
    public function create()
    {
        return view('products.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'satuan' => 'required|in:kg,butir,peti',
            'harga' => 'required|numeric|min:0',
        ]);
        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Produk ditambahkan.');
    }
    public function show(Product $product) { return redirect()->route('products.edit', $product); }
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'satuan' => 'required|in:kg,butir,peti',
            'harga' => 'required|numeric|min:0',
        ]);
        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Produk diperbarui.');
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk dihapus.');
    }
}