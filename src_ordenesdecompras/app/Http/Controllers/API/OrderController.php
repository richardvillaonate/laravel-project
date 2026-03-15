<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('products')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            // 'user_id' => auth()->id(),
        ]);

        foreach ($request->products as $item) {
            $order->products()->attach($item['id'], ['quantity' => $item['quantity']]);
        }

        return response()->json($order->load('products'), 201);
    }
}
