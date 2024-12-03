<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class RecapController extends Controller
{
    public function totalPrice(Request $request)
    {
        $query = Order::query()->with('customers', 'order_products')->withSum('order_products', 'quantity');

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);    
        }

        if ($request->has('product_code')) {
            $query->whereHas('order_products', function ($q) use ($request) {
                $q->where('product_code', $request->product_code);
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $total_price = $query->sum('total_price');
        $total_transaction = $query->count();
        $total_quantity = $query->pluck('order_products_sum_quantity')->sum();
        
        return response()->json([
            'message' => 'Total price found successfully',
            'data' => [
                'total_price' => (float) $total_price,
                'total_transaction' => $total_transaction,
                'total_quantity' => $total_quantity
            ]
        ]);
    }
}
