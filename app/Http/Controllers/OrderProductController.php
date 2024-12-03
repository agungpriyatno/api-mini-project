<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderProductController extends Controller
{
    public function update(Request $request, string $sale_id)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|exists:products,code',
            'quantity' => 'required|integer|min:1',
        ]);

        $sale = Order::where('id', $sale_id)->first();
        $item = Product::where('code', $validated['product_code'])->select('price')->first();
        $total_price_item = $item['price'] * $validated['quantity'];
        $total_price = $sale->total_price + $total_price_item;
        $sale->update(['total_price' => $total_price]);
        $sale->order_products()->create([
            'product_code' => $validated['product_code'],
            'quantity' => $validated['quantity'],
            'total_price' => $total_price_item,
        ]);
        
        return response()->json([
            'message' => 'Order Product updated successfully',
            'data' => $sale
        ], 200);
    }

    public function delete(Request $request, string $order_id, string $product_code)
    {
        $sale = Order::where('id', $order_id)->with('order_products')->first();
        $sale_item = OrderProduct::where('order_id', $order_id)->where('product_code', $product_code)->select('total_price')->first();
        $total_price = $sale->total_price - $sale_item['total_price'];
        $sale->update(['total_price' => $total_price]);
        $sale->order_products()->where('product_code', $product_code)->delete();

        return response()->json([
            'message' => 'Order Product deleted successfully',
            'data' => $sale
        ], 200);
    }
}
