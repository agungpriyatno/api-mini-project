<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class RecapController extends Controller
{
    public function totalPrice(Request $request)
    {
        $query = Sale::query()->with('customers', 'sale_items')->withSum('sale_items', 'quantity');

        if ($request->has('filter')) {
            $filters = $request->filter;
            foreach ($filters as $relation => $conditions) {
                if (is_array($conditions)) {
                    $query->whereHas($relation, function ($q) use ($conditions) {
                        foreach ($conditions as $field => $value) {
                            $q->where($field, 'like', '%' . $value . '%');
                        }
                    });
                } else {
                    $query->where($relation, 'like', '%' . $conditions . '%');
                }
            }
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $total_price = $query->sum('total_price');
        $total_transaction = $query->count();
        $total_quantity = $query->pluck('sale_items_sum_quantity')->sum();
        
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
