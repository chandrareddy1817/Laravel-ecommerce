<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('status', true)
            ->whereHas('category', function ($categoryQuery) {
                $categoryQuery->where('status', true);
            })
            ->with('category');

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%');

            });
        }

        // Category filter
        if ($request->filled('category')) {

            $query->whereHas('category', function ($categoryQuery) use ($request) {

                $categoryQuery->where('slug', $request->category);

            });
        }

        $products = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        return view('frontend.shop', compact(
            'products',
            'categories'
        ));
    }

    public function show(Product $product)
    {
        abort_if(
            !$product->status || !$product->category->status,
            404
        );

        return view('frontend.product', compact('product'));
    }
}