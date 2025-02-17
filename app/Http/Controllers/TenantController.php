<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function listProducts()
    {
        return response()->json(Product::all());
    }

    public function createProduct(Request $request)
    {
         $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json(['message' => 'Product Added ']);
    }

    public function placeOrder(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        $product = Product::find($request->product_id);
        $totalPrice = $product->price * $request->quantity;

        $order = Order::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
        ]);

        return response()->json(['message' => 'Order placed']);
    }
}
