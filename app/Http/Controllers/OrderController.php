<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function find(Request $request, string $id)
    {
        $data = Order::where('id', $id)->with('customers', 'order_products.products')->first();
        if ($data == null) abort(404, 'Order not found');
        return response()->json([
            'message' => 'Order found successfully',
            'data' => $data
        ], 200);
    }

    public function findMany(Request $request)
    {
        $query = Order::query()->with('customers', 'order_products.products');

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

        if ($request->has('sort')) {
            $sorts = $request->sort;
            foreach ($sorts as $field => $order) {
                if (is_array($order)) {
                    $key =  $field . '.' . array_keys($order)[0];
                    $value = array_pop($order);
                    $query->orderBy($key, $value);
                } else {
                    $query->orderBy($field, $order);
                }
            }
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $perPage = $request->get('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json([
            'message' => 'Order found successfully',
            'data' => $data->items(),
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    function create(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|exists:customers,id',
            'products' => 'array|min:1',
            'products.*.product_code' => 'required|string|exists:products,code',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $total_price = 0;

        foreach ($validated['products'] as $key => $item) {
            $validated['products'][$key]['product_code'] = $item['product_code'];
            $validated['products'][$key]['quantity'] = $item['quantity'];
            $validated['products'][$key]['total_price'] = $item['quantity'] * Product::where('code', $item['product_code'])->first()['price'];
            $total_price += $validated['products'][$key]['total_price'];
        }

        $sale = Order::create([
            'customer_id' => $validated['customer_id'],
            'total_price' => $total_price
        ]);

        $sale->order_products()->createMany($validated['products']);


        return response()->json([
            'message' => 'Order created successfully',
            'data' => $sale,
        ]);
    }

    function delete(Request $request, string $id)
    {
        $data = Order::where('id', $id)->delete();
        if ($data == 0) abort(404, 'Order not found');

        return response()->json([
            'message' => 'Order deleted successfully',
            'data' => $data
        ], 200);
    }
}
