<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::latest()->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'located_at' => 'required|string|max:255',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'buy_date' => 'required|date',
            'used_at' => 'nullable|date|after_or_equal:buy_date'
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product stored successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show(int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product retrieved successfully'
        ], 200);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Product not found'
            ], 404);
        }
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'located_at' => 'sometimes|required|string|max:255',
            'buy_price' => 'sometimes|required|numeric|min:0',
            'sell_price' => 'sometimes|required|numeric|min:0',
            'buy_date' => 'sometimes|required|date',
            'used_at' => 'nullable|date|after_or_equal:buy_date'
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Product not found'
            ], 404);
        }
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }

    /**
     * Get profit report.
     */
    public function profitReport()
    {
        $products = Product::all();
        $totalRevenue = $products->sum('sell_price');
        $totalCost = $products->sum('buy_price');
        $totalProfit = $totalRevenue - $totalCost;
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => $products->count(),
                'total_cost' => $totalCost,
                'total_revenue' => $totalRevenue,
                'total_profit' => $totalProfit,
                'products' => $products
            ]
        ], 200);
    }
}
